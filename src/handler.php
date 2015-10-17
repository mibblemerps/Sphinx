<?php

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
 * Dynamic loading of classes.
 * @param string $classname
 */
function __autoload($classname) {
    if (file_exists('inc/' . $classname . '.php')) {
        include 'inc/' . $classname . '.php';
    } elseif (file_exists('inc/Requests/' . $classname . '.php')) {
        include 'inc/Requests/' . $classname . '.php';
    }
}

require_once 'inc/Realms.php';
Realms::init(); // initilize Realms.

// Sent our footprint.
header('X-Powered-By: Realms ' . Realms::VERSION . ' by Mitchfizz05');


// Find the client session if applicable.
$session = new Session();
if (isset($_COOKIE['sid'], $_COOKIE['user'], $_COOKIE['version'])) {
    // Everything passed in.
    file_put_contents('user.txt', $_COOKIE['user']);
}


// Handle request.
$requestpath = '/' . $_GET['path'];
$request = new HTTPRequest($requestpath, apache_request_headers()); // create http request object
$resp = Realms::$requestRegistry->handle($request);

if ($resp === null) {
    // Bad request.
    http_response_code(404); // 404 Not Found
    exit; // blank response.
}


http_response_code($resp->statuscode); // send HTTP status code
header('Content-Type: ' . $resp->contenttype);
echo $resp->contentbody; // send content body


