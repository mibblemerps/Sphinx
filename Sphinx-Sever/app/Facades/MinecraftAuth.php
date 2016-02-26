<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Minecraft auth service facade.
 *
 * @package App\Facades
 */
class MinecraftAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'minecraft_auth';
    }
}