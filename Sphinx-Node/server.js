/*
 * Sphinx server file.
 */

var fs = require("fs");
var spawn = require("child_process").spawn;
var sanitizefs = require("sanitize-filename");
var McProperties = require("./mcproperties.js");
var mcping = require("mc-ping-updated");
var EventEmitter = require("events");
var util = require("util");

var regexPatterns = {
	started: /\[[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\] \[Server thread\/INFO\]: Done \([0-9]*\.[0-9]*s\)! For help, type "help" or "\?"/g,
};

var serverCommands = {
	stop: "stop", // command to stop the server
	whitelist_add: "whitelist add {PLAYER}", // command to whitelist a player
	whitelist_remove: "whitelist remove {PLAYER}",
	op_add: "op {PLAYER}", // op a player
	op_remove: "deop {PLAYER}", // deop a player,
	kick: "kick {PLAYER}", // kick a player from the server
}

function fileExists(file) {
	var exists = false;
	try {
		if (fs.statSync(file)) {
			exists = true;
		}
	} catch (e) {  }
	
	return exists;
}

var Server = function (serverdata) {
	this.serverdata = serverdata;
	
	this.serverPath = "servers/" + parseInt(serverdata.id);
	
	this.running = false; // is the server currently running?
	this.started = false; // has the server finished starting up?
	this.restarting = false; // is the server restarting?
    this.timeSinceLastPlayer = 0; // how many seconds since a player was last online?
    
    // Server IP and port.
    this.serverport = parseInt(process.env.SERVER_PORT_START) + parseInt(this.serverdata.id) - 1;
    this.serverip = process.env.SERVER_CONNECT_IP;
    
    EventEmitter.call(this);
}
util.inherits(Server, EventEmitter);

/**
 * Provison a Minecraft server.
 * Creates it's directory, sets config and automatically agrees to the EULA.
 */
Server.prototype.provision = function (server) {
	fs.mkdirSync(this.serverPath); // make server directory
	
	// Agree to EULA
	fs.writeFileSync(this.serverPath + "/eula.txt", "eula=true");
	
	// Create server.properties
	this.updateServerProperties();
	
	// Write provision time to file.
	fs.writeFileSync(this.serverPath + "/provisioned.txt", new Date().toString());
}

/**
* Initialize a server's files, ready to launch.
*/
Server.prototype.init = function (server) {
    var _this = this;
    
	// Verify the server has the neccesary jar file available.
	if (!fileExists("jars/" + sanitizefs(this.serverdata.jar))) {
		console.log(("Server " + this.serverdata.id + " is missing it's neccesary jar: " + this.serverdata.jar + "!").red);
		return;
	}
	
	// Verify the server has been provisioned.
	if (!fileExists(this.serverPath)) {
		// Server not yet provisioned!
		console.log("Server " + this.serverdata.id + " does not have a directory. Generating one now...");
		this.provision();
	}
    
    if (process.env.INACTIVITY_TIMER != "-1") {
        // Start a timer for last time player was online.
        setInterval(function () {
            _this.automaticServerShutdown(_this);
        }, 1000);
    }
}

/**
 * Should be called every second.
 * Turns off server if it's been inactive for more than 5 minutes.
 */
Server.prototype.automaticServerShutdown = function (_this) {
    if (this.started) {
        this.getPlayerCount(function (error, players) {
            if (!error) {
                if (players.online > 0) {
                    // A player is online - reset timer to 0.
                    _this.timeSinceLastPlayer = 0;
                } else {
                    // No players online, increment counter.
                    _this.timeSinceLastPlayer++;
                    
                    if (_this.timeSinceLastPlayer > parseInt(process.env.INACTIVITY_TIMER)) {
                        // No one has been online for a while. Shutdown server.
                        _this.stop();
                    }
                }
            }
        });
    } else {
        // Server not running - reset timer to 0.
        _this.timeSinceLastPlayer = 0;
    }
}

/**
 * Start the Minecraft server.
 */
Server.prototype.start = function (startedCallback) {
	var _this = this;
	
	console.log(("Starting server " + this.serverdata.id + "...").yellow);
	
	var jarfile = __dirname + "/jars/" + this.serverdata.jar;
	
	// Spawn Java process.
	this.process = spawn("java", ["-jar", jarfile, "-Xmx512M", "nogui"], {
		cwd: __dirname + "/" + this.serverPath
	});
    
    // Emit event.
    this.emit("starting");
	
	this.running = true;
	
	this.process.stdout.setEncoding("utf-8");
	this.process.stdin.setEncoding("utf-8");
	
	var handleData = function (data) {
		// Received output from server!
		var str = data.toString();
		var lines = str.split("\n");
		
		for (var i = 0; i < lines.length; i++) {
			var line = lines[i];
			
			//console.log(line); // uncomment for server output
			
			if (regexPatterns.started.test(line)) {
				// Server has started.
				_this.started = true;
				_this.restarting = false;
				console.log(("Server " + _this.serverdata.id + " has started.").yellow);
				
                // Emit event
                _this.emit("started");
                
                // Run callback
				if (typeof startedCallback !== "undefined") {
					startedCallback();
				}
			}
		}
	}
	this.process.stdout.on("data", handleData);
	this.process.stderr.on("data", handleData);
	this.process.on("close", function (code) {
		// Server exited.
		_this.started = false;
		_this.running = false;
		console.log(("Server " + _this.serverdata.id + " has stopped. Code: " + code).yellow);
        
        // Emit event.
        _this.emit("stopped");
		
		if (_this.restarting) {
			// Server restarting, start server back up.
			_this.start();
		}
	})
}

/**
 * Send a command to the server.
 */
Server.prototype.sendCommand = function (command) {
	this.process.stdin.write(command + "\n");
}

/**
 * Stop the Minecraft server.
 */
Server.prototype.stop = function () {
    _this.emit("stopping");
    
	if (this.running) {
		this.sendCommand("stop");
	}
}

/**
 * Restart the server.
 * If the server is not already running, it'll simply be started instead.
 */
Server.prototype.restart = function () {
	if (this.running) {
		this.restarting = true;
		this.stop();
	} else {
		this.start();
	}
}

/**
 * Check if the server is running.
 */
Server.prototype.isRunning = function () {
	return this.running;
}

/**
 * Update the server properties
 */
Server.prototype.updateServerProperties = function () {
	var _this = this;
	
	// Open server properties file for changes.
	if (fileExists(this.serverPath + "/server.properties")) {
		var props = new McProperties(fs.readFileSync(this.serverPath + "/server.properties", "utf-8"));
	} else {
		// Properties don't exist. Creating blank one...
		var props = new McProperties("");
	}
	
	// Modify values.
	Object.keys(this.serverdata.properties).forEach(function (key) {
		var value = _this.serverdata.properties[key];
		
		props.set(key, value);
	});
	
	// Set IP/port information.
	props.set("server-port", this.serverport);
	props.set("server-ip", process.env.SERVER_BIND_IP);
	
	// Save
	fs.writeFileSync(this.serverPath + "/server.properties", props.compile());
}

/**
 * Update server whitelist/ops file.
 */
Server.prototype.updateServerLists = function (mode) {
	if (mode == "ops") {
		var currentlist = this.serverdata.ops;
		var listfile = this.serverPath + "/ops.json";
	} else if (mode == "whitelist") {
		var currentlist = this.serverdata.whitelist;
		var listfile = this.serverPath + "/whitelist.json";
	} else {
		this.updateServerLists("whitelist");
		
		mode = "ops";
		var currentlist = this.serverdata.ops;
		var listfile = this.serverPath + "/ops.json";
	}
	
	if (this.running) {
		// Server is running. Read list from file, compare difference, and op/whitelist via console.
		// This won't be fully up-to-date, but duplicate /whitelist and /op commands don't harm anything (much).
		
		var disklist = JSON.parse(fs.readFileSync(listfile, "utf-8"));
		
		// Flatten disk list.
		var newdisklist = [];
		for (var i = 0; i < disklist.length; i++) {
			newdisklist.push(disklist[i].name);
		}
		
		// Flatten current list.
		var newcurrentlist = [];
		for (var i = 0; i < currentlist.length; i++) {
			newcurrentlist.push(currentlist[i].name);
		}
		
		var toRemove = newdisklist.filter(function (player) {
			return newcurrentlist.indexOf(player) < 0;
		});
		
		var toAdd = newcurrentlist.filter(function (player) {
			return newdisklist.indexOf(player) < 0;
		});
		
		// Select appropiate command for mode.
		if (mode == "whitelist") {
			var addCommand = serverCommands.whitelist_add;
			var removeCommand = serverCommands.whitelist_remove;
		} else {
			var addCommand = serverCommands.op_add;
			var removeCommand = serverCommands.op_remove;
		}
			
		// Remove players.
		for (var i = 0; i < toRemove.length; i++) {
			this.sendCommand(removeCommand.replace("{PLAYER}", toRemove[i]));
			
			if (mode == "whitelist") {
				// Demote and kick the player.
				this.sendCommand(serverCommands.op_remove.replace("{PLAYER}", toRemove[i]));
				this.sendCommand(serverCommands.kick.replace("{PLAYER}", toRemove[i]));
			}
		}
		
		// Add players.
		for (var i = 0; i < toAdd.length; i++) {
			this.sendCommand(addCommand.replace("{PLAYER}", toAdd[i]));
		}
	} else {
		if (mode == "ops") {
			// Add op level to JSON structure.
			for (var i = 0; i < currentlist.length; i++) {
				currentlist[i]["level"] = 4; // op level 4
			}
		}
		
		// Server not running, simply overwrite the file.
		fs.writeFileSync(listfile, JSON.stringify(currentlist));
	}
}

/**
 * Get online player count and max player slots.
 */
Server.prototype.getPlayerCount = function (callback) {
    if (!this.started) {
        return;
    }
    
    var host = process.env.SERVER_CONNECT_IP;
    var port = parseInt(process.env.SERVER_PORT_START) + parseInt(this.serverdata.id) - 1;
    
    mcping(host, port, function (error, response) {
        if (error) {
            // Error :(
            callback(error, undefined);
        } else {
            // Success!
            callback(undefined, {
                online: response.players.online,
                max: response.players.max
            });
        }
    });
}

/**
 * Get the IP to the server.
 * Also starts the server if neccesary.
 */
Server.prototype.join = function (callback) {
    var _this = this;
    
    if (!this.serverdata.active) {
        // Server inactive. Cannot join.
        callback(undefined);
        return;
    }
    
    // Reset last player counter to eliminate chance of server shutting down while joining.
    this.timeSinceLastPlayer = 0;
    
    if (this.started) {
        // Server already started.
        callback(_this.serverip + ":" + _this.serverport);
        return;
    }
    
    if (!this.running) {
        // Server not running. Start it now.
        this.start();
    }
    
    // Wait until the server 
    this.once("started", function () {
        callback(_this.serverip + ":" + _this.serverport);
    });
}

module.exports = Server;
