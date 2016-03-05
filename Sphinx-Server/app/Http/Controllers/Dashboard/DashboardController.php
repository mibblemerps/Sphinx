<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Facades\SphinxNode;
use App\Realms\Server;

class DashboardController extends Controller
{
    /**
     * Create an array of stats.
     *
     * @return array
     */
    protected function fetchStats()
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

        return $stats;
    }

    /**
     * View dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        return view('dashboard', ['stats' => $this->fetchStats()]);
    }

    public function statsApi()
    {
        return $this->fetchStats();
    }
}
