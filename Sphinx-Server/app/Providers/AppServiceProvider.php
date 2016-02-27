<?php

namespace App\Providers;

use App\Realms\Server;
use App\Facades\SphinxNode;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['sphinxnode'] = new \App\SphinxNode\SphinxNode('ws://' . env('SPHINX_NODE_ADDRESS', '127.0.0.1:8000') . '/');

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
        Server::saved($update);
        Server::created($update);
    }
}
