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
 * Client session with Realms API.
 *
 * @author Mitchfizz05
 */
class Session {
    /**
     * Minecraft session ID. The client obtains this token when they login at the launcher.
     * @var string 
     */
    public $sessionid;
    
    /**
     * The players username. This can change due to name changes, therefore do not rely on this to stay the same.
     * @var string
     */
    public $user;
    
    /**
     * The version of Minecraft the player is using.
     * This is *not* the Minecraft Realms library version, this is the client version, eg. "1.8.3"
     * @var string
     */
    public $version;
}
