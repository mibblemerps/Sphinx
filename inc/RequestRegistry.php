<?php

namespace Sphinx;

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
 * Registry of request handlers.
 *
 * @author Mitchfizz05
 */
class RequestRegistry {
    /**
     * Array of request handlers
     * @var \Request[]
     */
    protected $handlers;
    
    /**
     * Register a request handler
     * @param \Request $handler
     */
    public function register($handler) {
        $this->handlers[] = $handler;
    }
    
    /**
     * Handle API request.
     * Ensure request handlers have been registered first.
     * @param \HTTPRequest $request
     * @return \Response
     */
    public function handle($request) {
        // Check each API handler.
        foreach ($this->handlers as $handler) {
            // Check if handler should respond.
            if ($handler->should_respond($request, null)) {
                return $handler->respond($request, null); // allow handler to respond...
            }
        }
    }
}
