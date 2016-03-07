<?php

namespace App\Http\Controllers;

use App\Facades\SphinxNode;
use App\Realms\Player;
use App\Realms\Realm;
use App\Realms\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RealmController extends Controller
{
    /**
     * Generate a JSON response to be packaged up and sent to the client.
     * NOTE: Does not return encoded JSON. JSON must manually be encoded with json_encode().
     * @param Realm $server
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

        // Generate slots JSON.
        $slots = [];
        $firstSlotId = null;
        foreach ($server->worlds as $world) {
            if ($firstSlotId === null) {
                $firstSlotId = $world->slot_id;
            }

            $slots[] = [
                'slotId' => $world->slot_id,

                'options' => json_encode([
                    'slotName' => $world->name,
                    'minecraftVersion' => '1.9',

                    'pvp' => !!$world->pvp,
                    'spawnAnimals' => !!$world->spawn_animals,
                    'spawnMonsters' => !!$world->spawn_monsters,
                    'spawnNPCs' => !!$world->spawn_npcs,
                    'spawnProtection' => $world->spawn_protection,
                    'commandBlocks' => !!$world->command_blocks,
                    'forceGameMode' => !!$world->force_gamemode,
                    'difficulty' => $world->difficulty,
                    'gameMode' => $world->gamemode
                ])
            ];
        }

        if ($firstSlotId === null) {
            $firstSlotId = 1;
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
            'minigame' => !!$server->minigames_server,
            'activeSlot' => $firstSlotId,
            'slots' => $slots
        );

        Log::info(json_encode($json));

        return $json;
    }

    /**
     * Return a listing of all Realms available to the player.
     *
     * @return array
     */
    public function listing()
    {
        $servers = Realm::all();

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
     * @param int $serverId Server ID
     * @return array
     */
    public function view($serverId)
    {
        return self::generateServerJSON(Realm::findOrFail($serverId));
    }

    /**
     * Leave a Realm you've been invited to.
     *
     * @param int $serverId Server ID
     * @return mixed
     */
    public function leave($serverId)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Realm::find($serverId);

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
     * @param int $serverId Server ID
     * @param Player $playerUuid Player UUID
     * @return mixed
     */
    public function kick($serverId, $playerUuid)
    {
        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        $server = Realm::findOrFail($serverId);

        // Check user owns server.
        if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        // Remove user from Realm.
        $server->removePlayer(new Player($playerUuid, null));

        return '';
    }

    /**
     * Close the Realm, making it unavailable to join and shutting down the server.
     *
     * @param int $serverId Server ID
     * @return mixed
     */
	public function close($serverId)
    {
        $server = Realm::find($serverId);

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

    /**
     * Open the Realm, making it available to join once more.
     *
     * @param int $serverId Server ID
     * @return mixed
     */
	public function open($serverId)
    {
        $server = Realm::find($serverId);

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

    /**
     * Join a server.
     *
     * @param int $serverId
     * @return mixed
     */
    public function join($serverId)
    {
        $server = Realm::findOrFail($serverId);
        if (!$server->isInvited(Player::current())) {
            // Not invited. Sorry! :(
            abort(403); // 403 Forbidden.
        }

        return [
            'address' => SphinxNode::joinServer($server->id)
        ];
    }
	
	public function UpdateServerInfo(Request $request,$serverId)
    {
        $server = Realm::find($serverId);

		if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        // Change Server name and desc.
        $server->name = $request->input('name');
		$server->motd = $request->input('description');
        $server->Save(); // save!

        return 'true';
    }
	
	public function InitServer(Request $request,$serverId)
    {
        $server = Realm::find($serverId);

		if (Player::current()->uuid != $server->owner->uuid) {
            abort(403); // 403 Forbidden
        }

        if (!Player::isLoggedIn()) {
            abort(401); // 401 Unauthorized - not logged in!
        }

        // setting the realm to closed and setting the name and desc.
        $server->name = $request->input('name');
		$server->motd = $request->input('description');
		$server->state = "CLOSED";
        $server->Save(); // save!

        return 'true';
    }

}


