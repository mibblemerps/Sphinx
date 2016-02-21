<?php

namespace Sphinx;

/*
 * The MIT License
 *
 * Copyright 2015 Mitchfizz05.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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
