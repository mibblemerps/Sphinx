<?php

namespace App\Providers;

use App\SphinxNode\SphinxNode;
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
        $this->app['sphinxnode'] = new SphinxNode('ws://' . env('SPHINX_NODE_ADDRESS', '127.0.0.1:8000') . '/');
    }
}
