<?php

namespace App\Http\Controllers\NodeApi;
use App\Facades\SphinxNode;
use Illuminate\Http\Request;

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
     *
     * @param Request $request
     */
    public function request(Request $request)
    {
        if ($request->ip() != explode(':', env('SPHINX_NODE_ADDRESS'))[0]) {
            // IP address mismatch.
            abort(403); // 403 Forbidden.
        }

        // Send manifest.
        SphinxNode::sendManifest();
    }
}