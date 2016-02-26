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
		var myserver = new Server(serverdata[i]);
		myserver.init();
	}
}

/**
 * Start Sphinx node
 */
function init() {
	console.log(("Starting Sphinx Node...").cyan);
	
	loadServerData();
	
	initServers();
}

// Start server.
init();

