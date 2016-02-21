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
 * Player object
 *
 * @author Mitchfizz05
 */
class Player {
    /**
     * The player's current username.
     * Note that this can change and shouldn't relied on. The UUID should be used internally.
     * @var string
     */
    private $username;
    
    /**
     * Player's UUID.
     * Must *not* contain hyphens!
     * @var string
     */
    private $uuid;
    
    /**
     * Create new Player object.
     * Any of the parameters are optional and can be replaced with null if the value is unknown.
     * @param string $uuid UUID, without hyphens.
     * @param string $username Username.
     */
    public function __construct($uuid, $username) {
        // Quickly check if UUID doesn't contain hypens...
        if (strpos($uuid, '-')) {
            throw new Exception('Player UUID contains hypens');
        }
        $this->uuid = ($uuid == null) ? null : $uuid;
        
        $this->username = ($username == null) ? null : $username;
    }
    
    /**
     * Get player's UUID.
     * Returns null if UUID unknown.
     * @return string
     */
    public function getUUID() {
        return $this->uuid;
    }
    
    /**
     * Get players username.
     * Returns null if username unknown.
     * @return string
     */
    public function getUsername() {
        return $this->username;
    }
}
