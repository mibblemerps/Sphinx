/*
 * Sphinx Node.
 * Runs Minecraft Servers for Sphinx.
 */

var fs = require("fs");
var colors = require("colors");
var Server = require("./server.js");
var SphinxServer = require('./sphinxserver.js');

require("dotenv").config();

// Minecraft server data.
var serverdata = []

// Minecraft server instances.
var servers = [];

// Sphinx server object.
var sphinxserver;

/**
 * Save server data.
 */
function saveServerData() {
	fs.writeFileSync("serverdata.json", JSON.stringify(serverdata));
}

/**
 * Start websockets server to listen for requests from the Sphinx server.
 */
function startWebsocketServer() {
	console.log(("Starting websocket server...").cyan);
	sphinxserver = new SphinxServer(servers, process.env.SPHINX_IP, process.env.SPHINX_SECRET, process.env.BIND_TO);
	sphinxserver.startServer();
	console.log(("Websocket server active on " + sphinxserver.bindip + ":" + sphinxserver.bindport).cyan);
}

/**
 * Initialize all the servers directories ready to be used.
 */
function initServers() {
	for (var i = 0; i < serverdata.length; i++) {
		var id = serverdata[i].id;
		
		servers[id] = new Server(serverdata[i]);
		servers[id].init(); // initialize server
		
		servers[id].updateServerProperties(); // update server properties
		servers[id].updateServerLists(); // update server whitelist and ops list.
	}
}

function startServers() {
	Object.keys(servers).forEach(function (id) {
		servers[id].start();
	});
}

/**
 * Stop all servers.
 */
function stopServers() {
	Object.keys(servers).forEach(function (id) {
		servers[id].stop();
	});
}

/**
 * Are any servers running?
 */
function isServersRunning() {
	Object.keys(servers).forEach(function (id) {
		if (servers[id].isRunning()) {
			return true; // a server is still running.
		}
	});
	
	return false;
}

function bindShutdownHandler() { // Thanks - http://stackoverflow.com/a/14861513 !
	if (process.platform === "win32") {
		var rl = require("readline").createInterface({
			input: process.stdin,
			output: process.stdout
		});
		
		rl.on("SIGINT", function () {
			process.emit("SIGINT");
		});
	}
	
	process.on("SIGINT", function () {
		// Gracefully shutdown servers.
		console.log(("Shutting down Minecraft servers...").cyan);
		stopServers();
		
		var shutdownPoller = setInterval(function () {
			// Check if servers have shutdown yet.
			if (!isServersRunning()) {
				console.log(("All servers shutdown. Exiting...").cyan);
				clearInterval(shutdownPoller);
				process.exit();
			}
		}, 100);
	});
}

/**
 * Start Sphinx node
 */
function init() {
	console.log(("Starting Sphinx Node...").cyan);
	
	startWebsocketServer();
	
	initServers();
	
	bindShutdownHandler();
	
	startServers();
}



// Start server.
init();

