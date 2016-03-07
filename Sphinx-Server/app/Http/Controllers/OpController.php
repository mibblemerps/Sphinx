<?php

namespace App\Http\Controllers;

use App\Realms\Realm;
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
     * @param int $serverId Server ID
     * @return array
     */
    public function oplist($serverId)
    {
        $server = Realm::findOrFail($serverId);

        // Make list of all op usernames.
        $opNames = [];
        foreach ($server->operators as $op) {
            $opNames[] = $op->username;
        }

        // Send response
        $resp = [
            'ops' => $opNames
        ];

        return $resp;
    }

    /**
     * Give a player operator status.
     *
     * @param int $serverId Server ID
     * @param string $playerUuid Player's UUID
     * @return mixed
     */
    public function op($serverId, $playerUuid)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Realm::findOrFail($serverId);

        // Check user owns server.
        if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        // Op player.
        $player = new Player($playerUuid, null);
        $player->lookupFromApi();
        $server->opPlayer($player);

        return $this->oplist($serverId);
    }

    /**
     * Revoke operator status from a player.
     *
     * @param int $serverId Server ID
     * @param string $playerUuid Player's UUID
     * @return mixed
     */
    public function deop($serverId, $playerUuid)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Realm::findOrFail($serverId);

        // Check user owns server.
        if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        // Deop player.
        $player = new Player($playerUuid, null);
        $player->lookupFromApi();
        $server->deopPlayer($player);

        return $this->oplist($serverId);
    }
}