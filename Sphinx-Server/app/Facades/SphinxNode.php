<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Sphinx node facade.
 *
 * @package App\Facades
 */
class SphinxNode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sphinxnode';
    }
}