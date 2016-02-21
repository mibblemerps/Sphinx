<?php

namespace App\Realms;

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
}
