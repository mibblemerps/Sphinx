<?php

namespace Sphinx\Requests;

use Sphinx\Http\Response;

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
 * Description of RequestJoin
 *
 * @author Mitchfizz05
 */
class RequestJoin {
    public function should_respond($request, $session) {
        if (preg_match('/\/worlds\/([0-9]{1,5})\/join/', $request->path)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function respond($request, $session) {
        // Get the Realm ID the user wants to connect to..
        $preg_matches = array();
        preg_match('/\/worlds\/([0-9]{1,5})\/join/', $request->path, $preg_matches);
        $realm_id = $preg_matches[0];
        
        $resp = new Response();
        
        if ($realm_id == 0) {
            $resp->statuscode = 200;
            // Tempoary debug, hardcored server. Join if you like. :)
            $resp->contentbody = json_encode(array(
                'address' => 'potatocraft.pw:25565'
            ));
        } else {
            // Server not found! Return blank 404.
            $resp->statuscode = 404;
            $resp->contentbody = '';
        }
        
        return $resp;
    }
}
