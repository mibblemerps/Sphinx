<?php

namespace App\Providers;

use App\Auth\MinecraftAuth;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Register Minecraft authenticator.
        $this->app['minecraft_auth'] = new MinecraftAuth($this->app['request']);
    }
}
