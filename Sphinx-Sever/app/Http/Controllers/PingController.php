<?php

namespace App\Http\Controllers;

/**
 * Class PingController
 * @package App\Http\Controllers
 */
class PingController extends Controller
{
    /**
     * Check if the regions are online, or something, I guess?
     * I don't really know what this does.
     *
     * @return string
     */
    public function ping()
    {
        return 'true';
    }
}
