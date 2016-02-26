/*
 * Sphinx server file.
 */

var fs = require("fs");
var spawn = require("child_process").spawn;
var sanitizefs = require("sanitize-filename");

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
}

Server.prototype.provision = function (server) {
	var serverPath = "servers/" + parseInt(this.serverdata.id);

	fs.mkdirSync(serverPath); // make server directory
	
	// Agree to EULA
	fs.writeFileSync(serverPath + "/eula.txt", "eula=true");
	
	// Create server.properties
	var template = fs.readFileSync("server.properties.template", "utf-8");
	template = template.replace("{SERVER_PORT}", this.serverdata.port); // set port
	fs.writeFileSync(serverPath + "/server.properties", template);
	
	// Write provision time to file.
	fs.writeFileSync(serverPath + "/provisioned.txt", new Date().toString());
}

/**
* Initialize a server's files, ready to launch.
*/
Server.prototype.init = function (server) {
	var serverPath = "servers/" + parseInt(this.serverdata.id);
	
	// Verify the server has the neccesary jar file available.
	if (!fileExists("jars/" + sanitizefs(this.serverdata.jar))) {
		console.log(("Server " + this.serverdata.id + " is missing it's neccesary jar: " + this.serverdata.jar + "!").red);
		return;
	}
	
	// Verify the server has been provisioned.
	if (!fileExists(serverPath)) {
		// Server not yet provisioned!
		console.log("Server " + this.serverdata.id + " does not have a directory. Generating one now...");
		this.provision();
	}
	
	console.log(("Server " + this.serverdata.id + " good to go!").green);
}

module.exports = Server;
