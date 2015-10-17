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
 * Placeholder handler for getting the realms that the player is invited to.
 *
 * @author Mitchfizz05
 */
class RequestWorlds {
    public function should_respond($request, $session) {
        return ($request->path == '/worlds');
    }
    
    /**
     * Generate a JSON response to be packaged up and sent to the client.
     * NOTE: Does not return encoded JSON. JSON must manually be encoded with json_encode().
     * @param \Realm $server
     */
    protected function generateServerJSON($server) {
        // Generate player list.
        $players = array();
        foreach ($server->invited_players as $player) {
            $players[] = array(
                'name' => $player->getUsername(),
                'uuid' => $player->getUUID(),
                'operator' => in_array($player, $server->operators)
            );
        }
        
        // Formulate JSON response.
        $json = array(
            'id' => $server->id,
            'remoteSubscriptionId' => $server->id,
            'name' => $server->server_name,
            'players' => $players,
            'motd' => $server->motd,
            'state' => $server->state,
            'owner' => $server->owner->getUsername(),
            'ownerUUID' => $server->owner->getUUID(),
            'daysLeft' => $server->days_left,
            'ip' => $server->address,
            'expired' => $server->expired,
            'minigame' => $server->minigame_server
        );
        
        return $json;
    }
    
    public function respond($request, $session) {
        $server = new Realm();
        $server->id = 1;
        $server->address = 'potatocraft.pw:25565';
        $server->state = Realm::STATE_OPEN;
        $server->server_name = 'Potatocraft';
        $server->days_left = 365;
        $server->expired = false;
        $server->invited_players = array(
            new Player('b6284cef69f440d2873054053b1a925d', 'mitchfizz05'),
            new Player('27cf5429ec01499a9edf23b47df8d4f5', 'mindlux'),
            new Player('061e5603aa7b4455910a5547e2160ebc', 'Spazzer400')
        );
        $server->operators = array(
            new Player('b6284cef69f440d2873054053b1a925d', 'mitchfizz05')
        );
        $server->minigame_server = false;
        $server->motd = 'Potatos have lots of calories.';
        $server->owner = new Player('b6284cef69f440d2873054053b1a925d', 'mitchfizz05');
        
        // Generate response.
        $json = array(
            'servers' => array(
                $this->generateServerJSON($server)
            )
        );
        
        $responsetext = json_encode($json);
        
        $resp = new Response();
        $resp->contentbody = $responsetext;
        return $resp;
    }
}
