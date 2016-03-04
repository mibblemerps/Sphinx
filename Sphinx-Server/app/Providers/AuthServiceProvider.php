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

        // Register dashboard authentication services.
        config('auth.defaults.guard', 'web');
        config('auth.guards', [
            'web' => ['driver' => 'session', 'provider' => 'users']
        ]);
        config('auth.providers', [
            'users' => [
                'driver' => 'eloquent',
                'model' => \App\User::class
            ]
        ]);
    }
}
