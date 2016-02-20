<?php
//error_reporting(0);
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
 * Placeholder handler for Live Activity.
 *
 */
class RequestLiveActivity {
    public function should_respond($request, $session) {
        return ($request->path == '/activities/liveplayerlist');
    }
    
    protected function generateActivityJSON($Activity) {
       /*         $players = array();
        foreach ($Activity->invited_players as $player) {
            $players[] = array(
                'name' => $player->getUsername(),
                'uuid' => $player->getUUID(),
            );
        }*/
        // Formulate JSON response.
        $json = array(
            'serverId' => $Activity->serverId,
            //'playerList' => $Activity->playerList,

        );
        
        return $json;
    }
    
    public function respond($request, $session) {
       // error_reporting(0);
		$Activity = new lists();
        $Activity->serverId = 1;
        //$Activity->playerList = '';

        
        // Generate response.
        $json = array(
            'lists' => array(
                $this->generateActivityJSON($Activity)
            )
        );
        
        $responsetext = json_encode($json);
        
        $resp = new Response();
        $resp->contenttype = 'application/json';
        $resp->contentbody = $responsetext;
        return $resp;
    }
}
