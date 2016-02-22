<?php

namespace App\Http\Controllers;

use App\Realms\Player;
use App\Realms\Server;

class WorldController extends Controller
{
    /**
     * Generate a JSON response to be packaged up and sent to the client.
     * NOTE: Does not return encoded JSON. JSON must manually be encoded with json_encode().
     * @param Realm $server
     * @return array
     */
    protected function generateServerJSON($server) {
        // Generate player list.
        $players = array();
        foreach ($server->invited_players as $player) {
            $players[] = array(
                'name' => $player->username,
                'uuid' => $player->uuid,
                'operator' => in_array($player, $server->operators)
            );
        }

        // Formulate JSON response.
        $json = array(
            'id' => $server->id,
            'remoteSubscriptionId' => $server->id,
            'name' => $server->name,
            'players' => $players,
            'motd' => $server->motd,
            'state' => $server->state,
            'owner' => $server->owner->username,
            'ownerUUID' => $server->owner->uuid,
            'daysLeft' => $server->days_left,
            'ip' => $server->address,
            'expired' => !!$server->expired,
            'minigame' => !!$server->minigames_server
        );

        return $json;
    }

    public function viewall()
    {
        $servers = Server::all();

        // Generate JSON
        $serverlistJson = [];
        foreach ($servers as $server) {
            $serverlistJson[] = $this->generateServerJSON($server);
        }

        return [
            'servers' => $serverlistJson
        ];
    }
}