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
 * For the "Buy Realm" button located in-game.
 *
 * @author Mitchfizz05
 */
class RequestBuy implements Request {
    public function should_respond($request, $session) {
        return ($request->path == '/mco/buy');
    }
    
    public function respond($request, $session) {
        // Generate message to display to end user.
        $message = Realms::$config->get('messages', 'buy_realm');
        $message = str_replace('{EMAIL}', Realms::$config->get('general', 'contact'), $message);
        $message = str_replace('&', '§', $message); // colour codes
        
        // Forge response
        $resp = new Response();
        $resp->contentbody = json_encode(array(
            'statusMessage' => $message
        ));
        
        return $resp;
    }
}
