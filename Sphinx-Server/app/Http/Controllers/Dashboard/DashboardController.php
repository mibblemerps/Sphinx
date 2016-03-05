<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Facades\SphinxNode;
use App\Realms\Server;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Get stats.
        $nodeOnline = SphinxNode::ping();

        // Get stats from Node.
        if ($nodeOnline) {
            $stats = SphinxNode::stats();
            $stats['nodeOnline'] = true;
        } else {
            $stats = [
                'nodeOnline' => $nodeOnline,
                'serversRunning' => 'N/A',
                'onlinePlayers' => 'N/A'
            ];
        }

        // Get other stats.
        $stats['realmCount'] = Server::count();

        return view('dashboard', ['stats' => $stats]);
    }
}
