<?php

namespace App\Auth;

use App\Realms\Player;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Request;

class MinecraftAuth
{
    /**
     * The current request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Are we currently logged in?
     *
     * @var bool
     */
    protected $loggedIn;

    /**
     * Currently logged in player.
     *
     * @var Player
     */
    protected $player = null;

    /**
     * Minecraft session key for player.
     *
     * @var string
     */
    protected $sessionKey;

    /**
     * MojangAuthGuard constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        // Check if client is logged in.
        $this->loggedIn = (
            !is_null($this->request->cookies->get('sid')) &&
            !is_null($this->request->cookies->get('user'))
        );

        if ($this->loggedIn) {
            // Extract information from cookies.
            $sessionCookie = $this->request->cookies->get('sid');
            $username = $this->request->cookies->get('user');
            $this->sessionKey = explode(':', $sessionCookie)[1];
            $uuid = explode(':', $sessionCookie)[2];

            // Store player object.
            $this->player = new Player($uuid, $username);
        }
    }

    /**
     * Set a player as authentication. Generally for testing.
     *
     * @param App\Realms\Player
     */
    public function set($player)
    {
        $this->loggedIn = true;
        $this->player = $player;
    }

    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return $this->loggedIn;
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->player;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        return null;
    }

    /**
     * Do not use for authenticating players!
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        return false;
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->player = $user;
    }
}