<?php

namespace App\Http\Controllers\NodeApi;

use App\Facades\SphinxNode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

/**
 * Sphinx API controller for requesting the manifest.
 * The manifest will be sent via websocket to the nodejs application.
 *
 * @package App\Http\Controllers\NodeApi
 */
class ManifestController extends Controller
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
            Log::info('Denied manifest request from ' . $request->ip());
            abort(403); // 403 Forbidden.
        }

        // Send manifest.
        SphinxNode::sendManifest();
    }
}