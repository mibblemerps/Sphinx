/*
 * Sphinx Node.
 * Runs Minecraft Servers for Sphinx.
 */

var fs = require("fs");
var spawn = require("child_process").spawn;
var colors = require("colors");

// Minecraft server data.
var serverdata = []

// Minecraft server instances.
var servers = [];

/**
 * Load server data from disk.
 */
function loadServerData()
{
	serverdata = JSON.parse(fs.readFileSync("serverdata.json"));
}

/**
 * Start Sphinx node
 */
function init()
{
	console.log(("Starting Sphinx Node...").cyan);
	
	loadServerData();
}

// Start server.
init();

