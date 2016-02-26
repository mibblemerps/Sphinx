/*
 * Sphinx file for communicating with the Sphinx server.
 */

var ws = require("nodejs-websocket");

/**
 * Simple test for the Sphinx server to know if we are here.
 */
function handlePing(connection, payload) {
	// Send back a pong.
	connection.sendText(JSON.stringify({"action": "pong"}));
}

var SphinxServer = function (servers, remoteip, bindto) {
	this.servers = servers;
	this.sphinxIP = remoteip;
	this.bindip = bindto.split(":")[0];
	this.bindport = bindto.split(":")[1];
	
	// Create server.
	var server = ws.createServer({}, function (connection) {
		connection.on("text", function (data) {
			try {
				var payload = JSON.parse(data);
				
				switch (payload.action) {
					case "ping":
						handlePing(connection, payload);
						
						break;
				}
			} catch (e) {
				console.log(("Error occured whilst processing a request!").red);
			}
		});
	}).listen(this.bindport, this.bindip);
}


module.exports = SphinxServer;
