<?php

namespace App\Http\Controllers;

class AvailabilityController
{
    /**
     * Is the Realms service available?
     *
     * @return string
     */
    public function available()
    {
        return 'true';
    }

    /**
     * Are the Minecraft Realms developer tools available?
     *
     * @return string
     */
    public function stagingAvailable()
    {
        return 'true';
    }

    /**
     * Is the client compatible with this Realms server?
     *
     * @return string
     */
    public function compatible()
    {
        return 'COMPATIBLE';
    }

    /**
     * Check if the regions are online, or something, I guess?
     * I don't really know what this does.
     *
     * @return string
     */
    public function regionPing()
    {
        return 'true';
    }

    /**
     * Check if a trial is available for the user.
     *
     * @return string
     */
    public function trial()
    {
        return 'false';
    }
}
