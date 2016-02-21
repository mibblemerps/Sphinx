<?php

namespace Sphinx\Requests;

use Sphinx\Http\Response;
use Sphinx\Realms\Server;

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
 * Handler for Live Activity.
 */
class RequestLiveActivity implements Request {
    public function should_respond($request, $session){
        return ($request->path == '/activities/liveplayerlist');
    }

    /**
     * Generate JSON response for server activity.
     *
     * @param Server $server
     * @return array
     */
    protected function generateActivityJSON($server) {
        // Formulate JSON response.
        $json = array(
            'serverId' => $server->id,
            //'playerList' => $server->players,

        );

        return $json;
    }

    public function respond($request, $session) {
        $server = new Server();
        $server->id = 1;


        // Generate response.
        $json = array(
            'lists' => array(
                $this->generateActivityJSON($server)
            )
        );

        $resp = new Response();
        $resp->contenttype = 'application/json';
        $resp->contentbody = json_encode($json);
        return $resp;
    }
}
