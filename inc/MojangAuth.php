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
 * Mojang authentication library.
 *
 * @author Mitchfizz05
 */
class MojangAuth {
    /**
     * The URL to server Mojang authentication server.
     * Generally there is no reason to change this.
     * @var string
     */
    public $endpoint = 'https://authserver.mojang.com';
    
    public function validate($sessionid, $verifyssl = true) {
        // Request payload to be sent to the endpoint.
        $request = array(
            'accessToken' => $sessionid
        );
        
        // Create request.
        $ch = curl_init($this->endpoint . '/validate');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifyssl);
        $resp = curl_exec($ch); // execute request
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        file_put_contents('auth.txt', 'result:'.$httpcode . "\r\n" . curl_error($ch)); // dump results
        
        curl_close($ch);
    }
}
