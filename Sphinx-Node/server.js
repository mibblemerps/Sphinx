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

function provision(server) {
	var serverPath = "servers/" + parseInt(server.id);

	fs.mkdirSync(serverPath); // make server directory
	
	// Agree to EULA
	fs.writeFileSync(serverPath + "/eula.txt", "eula=true");
	
	// Create server.properties
	var template = fs.readFileSync("server.properties.template", "utf-8");
	template = template.replace("{SERVER_PORT}", server.port); // set port
	fs.writeFileSync(serverPath + "/server.properties", template);
	
	// Write provision time to file.
	fs.writeFileSync(serverPath + "/provisioned.txt", new Date().toString());
}

/**
* Initialize a server's files, ready to launch.
*/
function init(server) {
	var serverPath = "servers/" + parseInt(server.id);
	
	// Verify the server has the neccesary jar file available.
	if (!fileExists("jars/" + sanitizefs(server.jar))) {
		console.log(("Server " + server.id + " is missing it's neccesary jar: " + server.jar + "!").red);
		return;
	}
	
	// Verify the server has been provisioned.
	if (!fileExists(serverPath)) {
		// Server not yet provisioned!
		console.log("Server " + server.id + " does not have a directory. Generating one now...");
		provision(server);
	}
	
	console.log(("Server " + server.id + " good to go!").green);
}

module.exports = {
	init: init
};
