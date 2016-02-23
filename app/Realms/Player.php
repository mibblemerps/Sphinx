<?php

namespace App\Realms;
use App\Facades\MinecraftAuth;
use Laravel\Lumen\Application;

/**
 * Player object.
 *
 * @package App
 */
class Player
{
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
}
