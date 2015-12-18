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
 * Realms API response
 *
 * @author Mitchfizz05
 */
class Response {
    /**
     * The HTTP status code to return.
     * Default to '200'.
     * @var int
     */
    public $statuscode = 200; // ("HTTP/1.1 200 OK")
    
    /**
     * Content type that the response is encoded in.
     * Should a MIME type value. Defaults to "text/plain".
     * @var string
     */
    public $contenttype = 'application/json'; // Defaults to JSON.
    
    /**
     * The content body to return as a string.
     * @var string
     */
    public $contentbody;
    
    /**
     * Was they a failure caused by a user error during the processing of the request.
     * @var boolean 
     */
    public $failure = false;
}
