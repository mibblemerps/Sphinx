<?php

namespace App\Http\Controllers;

use App\Realms\Realm;
use App\Realms\Player;

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
            'name' => $server->server_name,
            'players' => $players,
            'motd' => $server->motd,
            'state' => $server->state,
            'owner' => $server->owner->username,
            'ownerUUID' => $server->owner->uuid,
            'daysLeft' => $server->days_left,
            'ip' => $server->address,
            'expired' => $server->expired,
            'minigame' => $server->minigame_server
        );

        return $json;
    }

    public function viewall()
    {
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

        return [
            'servers' => [
                $this->generateServerJSON($server)
            ]
        ];
    }
}