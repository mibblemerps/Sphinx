/*
 * Sphinx file for communicating with the Sphinx server.
 */

var ws = require("nodejs-websocket");
var Server = require("./server.js");

var SphinxServer = function (servers, remoteip, bindto) {
	this.servers = servers;
	this.sphinxIP = remoteip;
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
		
		console.log("Manifest received for " + serverManifest.id);
		
		var restartNeeded = serverManifest.needRestart;
		serverManifest.needRestart = undefined;
		
		var server = _this.servers[serverManifest.id];
		
		if (typeof server === "undefined") {
			// Server not defined.
			server = new Server(serverManifest);
			server.init(); // initialize server
		} else {
			server.serverdata = serverManifest;
		}
		
		// Refresh this servers whitelist and ops list.
		server.updateServerLists();
		
		// Update server properties.
		server.updateServerProperties();
		
		// Restart if neccesary.
		if (restartNeeded && server.serverdata.active) {
			server.restart();
		} else if (!server.serverdata.active) {
			// Server inactive, stop it if it's running.
			server.stop();
		}
	});
}

SphinxServer.prototype.startServer = function () {
	var _this = this;
	
	// Create server.
	this.server = ws.createServer({}, function (connection) {
		connection.on("text", function (data) {
			//try {
				var payload = JSON.parse(data);
				
				switch (payload.action) {
					case "ping":
						_this.handlePing(connection, payload);
						break;
						
					case "manifest":
						_this.handleServerManifest(connection, payload);
						break;
				}
				
				connection.close();
			//} catch (e) {
			//	console.log(("Error occured whilst processing a request!").red);
			//	throw e;
			//}
		});
	}).listen(this.bindport, this.bindip);
}


module.exports = SphinxServer;
