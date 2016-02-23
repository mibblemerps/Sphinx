<?php

namespace App\Realms;
use App\Facades\MinecraftAuth;
use Laravel\Lumen\Application;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Player object.
 *
 * @package App
 */
class Player
{
    /**
     * Mojang API endpoint.
     */
    const MOJANG_API = 'https://api.mojang.com';

    /**
     * The player's current username.
     * Note that this can change and shouldn't relied on. The UUID should be used internally.
     * @var string
     */
    public $username;

    /**
     * Player's UUID.
     * Must *not* contain hyphens!
     * @var string
     */
    public $uuid;

    /**
     * Create new Player object.
     * Any of the parameters are optional and can be replaced with null if the value is unknown.
     * @param Application $app
     * @param string $uuid UUID, without hyphens.
     * @param string $username Username.
     * @throws \Exception
     */
    public function __construct($uuid, $username) {
        // Quickly check if UUID doesn't contain hypens...
        if (strpos($uuid, '-')) {
            throw new \Exception('Player UUID contains hypens');
        }
        $this->uuid = ($uuid == null) ? null : $uuid;

        $this->username = ($username == null) ? null : $username;
    }

    /**
     * Get current logged in player.
     *
     * @return Player
     */
    public static function current()
    {
        return MinecraftAuth::user();
    }

    /**
     * Is a player logged in?
     *
     * @return bool
     */
    public static function isLoggedIn()
    {
        return MinecraftAuth::check();
    }

    /**
     * Get an array of this users pending invites.
     *
     * @param bool $includingAccepted Include accepted invites in the results?
     * @return Invite[]
     */
    public function getInvites($includingAccepted = false)
    {
        $invites = [];

        // Loop through all invites
        $allInvites = Invite::all();
        foreach ($allInvites as $invite)
        {
            if ($invite->accepted & !$includingAccepted) {
                // Invite already accepted.
                continue;
            }

            if ($invite->to->uuid == $this->uuid) {
                $invites[] = $invite;
            }
        }

        return $invites;
    }

    /**
     * Fill in the UUID or username (whatever's missing from the Mojang API).
     */
    public function lookupFromApi()
    {
        $query = is_null($this->uuid) ? $this->username : $this->username;
        if ($query === null) {
            // Needs either uuid or username to perform lookup!
            throw new \Exception('Needs UUID or username to perform API lookup!');
        }

        // Contact Mojang's servers.
        $resp = file_get_contents(self::MOJANG_API . '/users/profiles/minecraft/' . urlencode($query));
        $resp = json_decode($resp);
        if ($resp === null) {
            // Request failed.
            abort(503, 'Mojang\'s API unreachable.');
        }

        // Fill in details.
        $this->uuid = $resp->id;
        $this->username = $resp->name;
    }
}
