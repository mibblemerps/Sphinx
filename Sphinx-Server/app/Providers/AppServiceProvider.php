<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Auth\MinecraftAuth;
use App\Realms\Realm;
use App\Facades\SphinxNode;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Event handler to send manifest on server update.
        $update = function ($server) {
            try {
                if ($server->autoPush) {
                    SphinxNode::sendManifest([$server->id]);
                }
            } catch (\Exception $e) {
                // Failed to send manifest. Not end of the world.
            }
        };
        Realm::saved($update);
        Realm::created($update);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['sphinxnode'] = new \App\SphinxNode\SphinxNode('ws://' . env('SPHINX_NODE_ADDRESS', '127.0.0.1:8000') . '/');

		// Register Minecraft authenticator.
        $this->app['minecraft_auth'] = new MinecraftAuth($this->app['request']);
    }
}
