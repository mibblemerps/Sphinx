<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 21/02/2016
 * Time: 10:02 PM
 */

namespace App\Realms;


/**
 * Minecraft server object.
 *
 * @author Mitchfizz05
 */
class Server {
    const STATE_ADMINLOCK = 'ADMIN_LOCK';
    const STATE_CLOSED = 'CLOSED';
    const STATE_OPEN = 'OPEN';
    const STATE_UNINITIALIZED = 'UNINITIALIZED';

    const GAMEMODE_SURVIVAL = 0;
    const GAMEMODE_CREATIVE = 1;
    const GAMEMODE_ADVENTURE = 2;
    const GAMEMODE_SPECTATOR = 3;

    const DIFFICULTY_PEACEFUL = 0;
    const DIFFICULTY_EASY = 1;
    const DIFFICULTY_NORMAL = 2;
    const DIFFICULTY_HARD = 3;

    /**
     * Server ID, must be unique.
     * @var integer
     */
    public $id;

    /**
     * Human readable name of server.
     * @var string
     */
    public $name;

    /**
     * Server MOTD.
     * @var string
     */
    public $motd;

    /**
     * Server state. Defaults to Server::STATE_UNINITIALIZED
     * @var string
     */
    public $state = self::STATE_UNINITIALIZED;

    /**
     * Server gamemode.
     * @var integer
     */
    public $gamemode = self::GAMEMODE_SURVIVAL;

    /**
     * Is the server a minigame server?
     * @var boolean
     */
    public $minigame_server = false;

    /**
     * Server IP. Using format "127.0.0.1:25565"
     * @var string
     */
    public $ip;

    /**
     * An array of connected players.
     * @var array
     */
    public $players = array();

    /**
     * The server owner.
     * @var string
     */
    public $owner;

}
