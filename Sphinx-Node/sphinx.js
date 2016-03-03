/*
 * Sphinx Node.
 * Runs Minecraft Servers for Sphinx.
 */

var fs = require("fs");
var colors = require("colors");
var Server = require("./server.js");
var SphinxServer = require('./sphinxserver.js');

require("dotenv").config();

// Staged start mode enabled?
var stagedStartEnabled = (process.env.STAGED_START.toLowerCase() == "true");

// Minecraft server data.
var serverdata = []

// Minecraft server instances.
var servers = [];

// Queue of servers to be started.
var serverStartQueue = [];
// Server currently starting.
var serverStartQueueCurrent;

// Sphinx server object.
var sphinxserver;

/**
 * Start websockets server to listen for requests from the Sphinx server.
 */
function startWebsocketServer() {
	console.log(("Starting websocket server...").cyan);
	sphinxserver = new SphinxServer(servers, serverStartQueue, process.env.SPHINX_IP, process.env.BIND_TO);
	sphinxserver.startServer();
	console.log(("Websocket server active on " + sphinxserver.bindip + ":" + sphinxserver.bindport).cyan);
}

/**
 * Stop all servers.
 */
function stopServers() {
	// Clear start queue.
	serverStartQueue = [];
	
	// Stop all currently running servers.
	Object.keys(servers).forEach(function (id) {
		servers[id].stop();
	});
}

/**
 * Start the server start queue.
 * The queue will start servers one after the other (provided STAGED_START is enabled).
 * If STAGED_START is disabled, the queue will start each server all at once.
 */
function startServerStartQueue()
{
	if (stagedStartEnabled) {
		console.log(("Staged start mode enabled.").cyan);
	}
	
	setInterval(function () {	
		// Check if previous server has finished starting.
		if (stagedStartEnabled && (typeof serverStartQueueCurrent !== "undefined") && !serverStartQueueCurrent.started) {
			// Not yet.
			return;
		}
		
		// Previous server started. Start the next one!
		var next = serverStartQueue.shift();
		if (typeof next === "undefined") {
			// Oh. There is no next one. :(
			return;
		}
		
		serverStartQueueCurrent = next;
		
		// Restart (starting if not already started) this server.
		serverStartQueueCurrent.restart();
	}, 100);
}

/**
 * Are any servers running?
 */
function isServersRunning() {
	var running = false;
	Object.keys(servers).forEach(function (id) {
		if (servers[id].running) {
			running = true; // a server is still running.
		}
	});
	
	return running;
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
	
	bindShutdownHandler();
	
	startServerStartQueue();
}

// Start Sphinx Node.
init();
