<?php

namespace App\Http\Controllers;

use App\Facades\SphinxNode;
use App\Realms\Player;
use App\Realms\Server;
use App\Realms\Invite;

class WorldController extends Controller
{
    /**
     * Generate a JSON response to be packaged up and sent to the client.
     * NOTE: Does not return encoded JSON. JSON must manually be encoded with json_encode().
     * @param Server $server
     * @return array
     */
    public static function generateServerJSON($server) {
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
            'id' => intval($server->id),
            'remoteSubscriptionId' => "$server->id",
            'name' => $server->name,
            'players' => $players,
            'motd' => $server->motd,
            'state' => $server->state,
            'owner' => $server->owner->username,
            'ownerUUID' => $server->owner->uuid,
            'daysLeft' => intval($server->days_left),
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
            // Check if we are invited to this server.
            if (!$server->isInvited(Player::current())) {
                // Not invited. :(
                continue;
            }

            $serverlistJson[] = self::generateServerJSON($server);
        }

        return [
            'servers' => $serverlistJson
        ];
    }

    /**
     * View a single server.
     *
     * @param int $id Server ID
     * @return array
     */
    public function view($id)
    {
        return self::generateServerJSON(Server::findOrFail($id));
    }

    /**
     * Leave a Realm you've been invited to.
     *
     * @param int $id Realm ID
     * @return string
     */
    public function leave($id)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Server::find($id);

        // Ensure the owner isn't removing themselves from their own Realm.
        if (Player::current()->uuid == $server->owner->uuid) {
            abort(400); // 400 Bad Request
        }

        // Remove user from invited players list.
        $server->removePlayer(Player::current());

        return '';
    }

    /**
     * Remove a user from the Realm. As in, de-whitelist.
     *
     * @param int $id
     * @param Player $player
     * @return string
     */
    public function kick($id, $player)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Server::findOrFail($id);

        // Check user owns server.
        if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        // Remove user from Realm.
        $server->removePlayer(new Player($player, null));

        return '';
    }

	public function close($id)
    {
        $server = Server::find($id);

	    if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        // Change State.
        $server->state = "CLOSED";
        $server->silentSave(); // save without pushing changes
        SphinxNode::sendManifest([$server->id], true);

        return 'true';
    }

	public function open($id)
    {
        $server = Server::find($id);

        if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        // Change State.
        $server->state = "OPEN";
        $server->silentSave(); // save without pushing changes
        SphinxNode::sendManifest([$server->id], true);

        return 'true';
    }
}
