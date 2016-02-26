/*
 * Sphinx Node.
 * Runs Minecraft Servers for Sphinx.
 */

var fs = require("fs");
var colors = require("colors");
var Server = require("./server.js");

// Minecraft server data.
var serverdata = []

// Minecraft server instances.
var servers = [];

/**
 * Load server data from disk.
 */
function loadServerData() {
	serverdata = JSON.parse(fs.readFileSync("serverdata.json", "utf-8"));
}

/**
 * Initialize all the servers directories ready to be used.
 */
function initServers() {
	for (var i = 0; i < serverdata.length; i++) {
		servers[i] = new Server(serverdata[i]);
		servers[i].init(); // initialize server
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
	
	initServers();
	
	bindShutdownHandler();
	
	startServers();
}



// Start server.
init();

