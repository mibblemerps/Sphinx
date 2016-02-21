<?php

namespace Sphinx;

use Sphinx\Http\Request;
use Sphinx\Realms\Session;

/* 
 * The MIT License
 *
 * Copyright 2015 Mitchfizz05.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Handles incoming API requests.
 */

/**
 * Autoloader
 */
require 'vendor/autoload.php';

// Load helpers
foreach (scandir('inc/_helpers') as $filename) {
    if (pathinfo($filename, PATHINFO_EXTENSION) == 'php') {
        require 'inc/_helpers/' . $filename;
    }
}

require_once 'inc/Realms.php';
Realms::init(); // initilize Realms.

/**
 * Convenience function for logging.
 *
 * @return \Logger
 */
function logger() {
    return Realms::$logger;
}

// Sent our footprint.
header('X-Powered-By: Realms ' . Realms::VERSION . ' http://github.com/mitchfizz05/Realms');


// Find the client session if applicable.
$session = new Session();
if (isset($_COOKIE['sid'], $_COOKIE['user'], $_COOKIE['version'])) {
    // Everything passed in.
	
    file_put_contents('user.txt',$_COOKIE['user'].'_'.$_COOKIE['sid']);
}


// Handle request.
$requestpath = $_SERVER['REQUEST_URI'];
$request = new Request($requestpath, apache_request_headers()); // create http request object
$resp = Realms::$requestRegistry->handle($request);

if ($resp === null) {
    // Bad request.
    logger()->warn('Unrouted request: "' . $requestpath . '"!');
    http_response_code(404); // 404 Not Found
    exit; // blank response.
} else {
    // Request successfully processed
    logger()->debug('Request: "' . $requestpath . '".');
}


http_response_code($resp->statuscode); // send HTTP status code
header('Content-Type: ' . $resp->contenttype);


echo $resp->contentbody; // send content body

