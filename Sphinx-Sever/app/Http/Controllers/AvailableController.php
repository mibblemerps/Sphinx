<?php

namespace App\Http\Controllers;

/**
 * Class RealmsController
 * @package App\Http\Controllers
 */
class AvailableController
{
    public function available()
    {
        return 'true';
    }

    public function stagingAvailable()
    {
        return 'true';
    }
}
