<?php

namespace Sphinx\Realms;

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
 * A Minecraft Realm instance.
 * Represents a single Minecraft Realm.
 *
 * @author Mitchfizz05
 */
class Realm {
    // Realm states.
    const STATE_OPEN = 'OPEN';
    const STATE_CLOSED = 'CLOSED';
    const STATE_ADMINLOCK = 'ADMIN_LOCK';
    const STATE_UNINITIALIZED = 'UNINITIALIZED';
    
    /**
     * ID of the Realm.
     * Must be an integer.
     * @var integer 
     */
    public $id;
    
    /**
     * Human readable server name.
     * @var string
     */
    public $server_name;
    
    /**
     * The remote IP address of the Realm server.
     * Format: [ip address]:[port]
     * @var string
     */
    public $address;
    
    /**
     * Array of invited players.
     * @var Player[]
     */
    public $invited_players;
    
    /**
     * Array of server ops.
     * @var Player[]
     */
    public $operators;
    
    /**
     * The Realm owner.
     * @var Player
     */
    public $owner;
    
    /**
     * Server message of the day. The text that appears under the Realm listing.
     * @var string
     */
    public $motd;
    
    /**
     * The state of the Realm.
     * @var string
     */
    public $state;
    
    /**
     * Number of days left in the server subscription.
     * @var integer
     */
    public $days_left;
    
    /**
     * Has the server expired? If true this will prevent people joining the Realm.
     * @var boolean
     */
    public $expired;
    
    /**
     * Is the server a minigame server?
     * @var boolean
     */
    public $minigame_server = false;
    
    
    public static function getList() {
        
    }
}
