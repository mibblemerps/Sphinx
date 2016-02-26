/*
 * Sphinx server file.
 */

var fs = require("fs");
var spawn = require("child_process").spawn;
var sanitizefs = require("sanitize-filename");

var regexPatterns = {
	started: /\[[0-9]{1,2}:[0-9]{1,2}:[0-9]{1,2}\] \[Server thread\/INFO\]: Done \([0-9]*\.[0-9]*s\)! For help, type "help" or "\?"/g
};

var serverCommands = {
	stop: "stop", // command to stop the server
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
}

/**
 * Provison a Minecraft server.
 * Creates it's directory, sets config and automatically agrees to the EULA.
 */
Server.prototype.provision = function (server) {
	fs.mkdirSync(this.serverPath); // make server directory
	
	// Agree to EULA
	fs.writeFileSync(this.serverPath + "/eula.txt", "eula=true");
	
	// Create server.properties
	var template = fs.readFileSync("server.properties.template", "utf-8");
	template = template.replace("{SERVER_PORT}", this.serverdata.port); // set port
	fs.writeFileSync(this.serverPath + "/server.properties", template);
	
	// Write provision time to file.
	fs.writeFileSync(this.serverPath + "/provisioned.txt", new Date().toString());
}

/**
* Initialize a server's files, ready to launch.
*/
Server.prototype.init = function (server) {
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
	
	console.log(("Server " + this.serverdata.id + " good to go!").green);
}

/**
 * Start the Minecraft server.
 */
Server.prototype.start = function () {
	var _this = this;
	
	console.log(("Starting server " + this.serverdata.id + "...").yellow);
	
	var jarfile = __dirname + "/jars/" + this.serverdata.jar;
	
	// Spawn Java process.
	this.process = spawn("java", ["-jar", jarfile, "-Xmx512M", "nogui"], {
		cwd: __dirname + "\\" + this.serverPath
	});
	
	this.started = true;
	
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
				console.log("Server " + _this.serverdata.id + " has started.");
			}
		}
	}
	this.process.stdout.on("data", handleData);
	this.process.stderr.on("data", handleData);
	this.process.on("close", function (code) {
		// Server exited.
		_this.started = false;
		_this.running = false;
		console.log("Server " + _this.serverdata.id + " has stopped. Code: " + code);
	})
}

/**
 * Stop the Minecraft server.
 */
Server.prototype.stop = function () {
	this.process.stdin.write(serverCommands.stop + "\n");
}

/**
 * Check if the server is running.
 */
Server.prototype.isRunning = function () {
	return this.running;
}

module.exports = Server;
