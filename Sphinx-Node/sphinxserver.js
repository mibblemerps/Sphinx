/*
 * Sphinx file for communicating with the Sphinx server.
 */

var ws = require("nodejs-websocket");
var http = require("http");
var Server = require("./server.js");

var SphinxServer = function (servers, serverStartQueue, remoteip, bindto) {
	this.servers = servers;
	this.sphinxIP = remoteip;
	this.serverStartQueue = serverStartQueue;
	this.bindip = bindto.split(":")[0];
	this.bindport = bindto.split(":")[1];
}

/**
 * Simple test for the Sphinx server to know if we are here.
 */
SphinxServer.prototype.handlePing = function (connection, payload) {
	// Send back a pong.
	connection.sendText(JSON.stringify({"action": "pong"}));
}

/**
 * Handle server metadata file.
 */
SphinxServer.prototype.handleServerManifest = function (connection, payload) {
	var _this = this;
	
	// Loop through received servers
	Object.keys(payload.servers).forEach(function (key) {
		var serverManifest = payload.servers[key];
		
		console.log(("Received metadata for server " + serverManifest.name + " [" + serverManifest.id + "].").yellow);
		
		var restartNeeded = serverManifest.needRestart;
		serverManifest.needRestart = undefined;
		
		var server = _this.servers[serverManifest.id];
		
		if (typeof server === "undefined") {
			// Server not defined.
			_this.servers[serverManifest.id] = new Server(serverManifest);
			server = _this.servers[serverManifest.id];
			server.init(); // initialize server
			
			if (server.serverdata.active) {
				restartNeeded = true;
			}
		} else {
			server.serverdata = serverManifest;
		}
		
		// Refresh this servers whitelist and ops list.
		server.updateServerLists();
		
		// Update server properties.
		server.updateServerProperties();
		
		if (restartNeeded) {
			if (server.serverdata.active) {
				if (server.running || (process.env.INACTIVITY_TIMER == -1)) {
                    // Server running, restart it. (Or on-demand servers is disabled, so start even if not running)
                    _this.serverStartQueue.push(server);
                }
			} else {
				// Server inactive - stop server.
				server.stop();
			}
		}
	});
}

/**
 * Handle requests to join a Realm.
 */
SphinxServer.prototype.handleJoin = function (connection, payload) {
	var serverid = payload.id;

	// Get server IP (and start server if neccesary)...
    var address;
    this.servers[serverid].join(function (ip) {
        if (!ip) {
            // Failed to get IP!
            ip = null;
        }
        
        // Send response.
        connection.sendText(JSON.stringify({
            address: ip
        }));
    });
}

/**
 * Get node statistics.
 */
SphinxServer.prototype.handleStats = function (connection, payload) {
    var _this = this;
    
    // Check how many servers are running.
    var serversRunning = 0;
    Object.keys(this.servers).forEach(function (serverid) {
        if (_this.servers[serverid].running) {
            serversRunning++;
        }
    });
    
    // Respond with stats.
    connection.sendText(JSON.stringify({
        serversRunning: serversRunning 
    }));
}

/**
 * Send a request out for the manifest to be sent.
 * The manifest will arrive seperately via the Websocket.
 */
SphinxServer.prototype.requestManifest = function () {
	var host = process.env.SPHINX_ACCESS.split(":")[0];
	var port = process.env.SPHINX_ACCESS.split(":")[1];
	
	http.get({
		host: host,
		port: port,
		path: "/sphinx/api/request-manifest"
	});
}

SphinxServer.prototype.startServer = function () {
	var _this = this;
	
	// Create server.
	this.server = ws.createServer({}, function (connection) {
		if (connection.socket.remoteAddress != process.env.SPHINX_IP) {
			// Mismatch!
			connection.close();
		}
		
		connection.on("text", function (data) {
			try {
				var payload = JSON.parse(data);
				
				switch (payload.action) {
					case "ping":
						_this.handlePing(connection, payload);
						break;
						
					case "manifest":
						_this.handleServerManifest(connection, payload);
						break;
						
					case "join":
						_this.handleJoin(connection, payload);
						break;
                        
                    case "stats":
                        _this.handleStats(connection, payload);
                        break;
						
				}
			} catch (e) {
				console.log(("Error occured whilst processing a request!").red);
			}
		});
	}).listen(this.bindport, this.bindip);
	
	// Request manifest.
	this.requestManifest();
}


module.exports = SphinxServer;
