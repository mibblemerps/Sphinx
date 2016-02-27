<?php

namespace App\Http\Controllers;

use App\Realms\Server;
use App\Realms\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Class OpController
 * @package App\Http\Controllers
 */
class OpController extends Controller
{
    /**
     * Get op list. Should be sent back after oping/deoping a player to update the client.
     *
     * @param Server $server
     * @return array
     */
    public function oplist($serverid)
    {
        $server = Server::findOrFail($serverid);

        // Make list of all op usernames.
        $opNames = [];
        foreach ($server->operators as $op) {
            $opNames[] = $op->username;
        }

        // Send response
        $resp = [
            'ops' => $opNames
        ];

        Log::info(print_r($resp, true));

        return $resp;
    }

    public function op(Request $request, $id, $player)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Server::findOrFail($id);

        // Check user owns server.
        if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        // Op player.
        $player = new Player($player, null);
        $player->lookupFromApi();
        $server->opPlayer($player);

        return $this->oplist($id);
    }

    public function deop($id, $player)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Server::findOrFail($id);

        // Check user owns server.
        if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        // Deop player.
        $player = new Player($player, null);
        $player->lookupFromApi();
        $server->deopPlayer($player);

        return $this->oplist($id);
    }
}