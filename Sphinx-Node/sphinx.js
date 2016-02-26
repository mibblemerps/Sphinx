/*
 * Sphinx Node.
 * Runs Minecraft Servers for Sphinx.
 */

var fs = require("fs");
var colors = require("colors");
var Server = require("./server.js");
var SphinxServer = require('./sphinxserver.js');

// Minecraft server data.
var serverdata = []

// Minecraft server instances.
var servers = [];

// Sphinx server object.
var sphinxserver;

/**
 * Load server data from disk.
 */
function loadServerData() {
	serverdata = JSON.parse(fs.readFileSync("serverdata.json", "utf-8"));
}

/**
 * Start websockets server to listen for requests from the Sphinx server.
 */
function startWebsocketServer() {
	console.log(("Starting websocket server...").cyan);
	sphinxserver = new SphinxServer(servers, "127.0.0.1", "127.0.0.1:8000");
	console.log(("Websocket server active.").cyan);
}

/**
 * Initialize all the servers directories ready to be used.
 */
function initServers() {
	for (var i = 0; i < serverdata.length; i++) {
		servers[i] = new Server(serverdata[i]);
		servers[i].init(); // initialize server
		
		servers[i].updateServerProperties(); // update server properties
		servers[i].updateServerLists(); // update server whitelist and ops list.
	}
}

function startServers() {
	for (var i = 0; i < servers.length; i++) {
		servers[i].start();
	}
}

/**
 * Stop all servers.
 */
function stopServers() {
	for (var i = 0; i < servers.length; i++) {
		servers[i].stop();
	}
}

/**
 * Are any servers running?
 */
function isServersRunning() {
	for (var i = 0; i < servers.length; i++) {
		if (servers[i].isRunning()) {
			return true; // a server is still running.
		}
	}
	
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
	
	loadServerData();
	
	startWebsocketServer();
	
	initServers();
	
	bindShutdownHandler();
	
	startServers();
}



// Start server.
init();

