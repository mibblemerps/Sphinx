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
        Server::saved(function ($server) {
            SphinxNode::sendManifest([$server->id]);
        });
        Server::created(function ($server) {
            SphinxNode::sendManifest([$server->id]);
        });
    }
}
