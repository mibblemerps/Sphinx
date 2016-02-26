<?php

namespace App\Http\Controllers\NodeApi;
use App\Facades\SphinxNode;

/**
 * Sphinx API controller for requesting the manifest.
 * The manifest will be sent via websocket to the nodejs application.
 *
 * @package App\Http\Controllers\NodeApi
 */
class ManifestController
{
    /**
     * Get the entire server manifest.
     */
    public function request()
    {
        SphinxNode::sendManifest();
        return 'sent';
    }
}