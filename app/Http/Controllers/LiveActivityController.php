<?php

namespace App\Http\Controllers;
use App\Realms\Server;

/**
 * Class LiveActivityController
 * @package App\Http\Controllers
 */
class LiveActivityController extends Controller
{
    /**
     * Generate JSON response for server activity.
     *
     * @param Server $server
     * @return array
     */
    protected function generateActivityJSON($server)
    {
        // Formulate JSON response.
        $json = array(
            'serverId' => $server->id,
            //'playerList' => $server->players,
        );

        return $json;
    }

    /**
     * Fetch player list.
     *
     * @return array
     */
    public function playerlist()
    {
        $server = new Server();
        $server->id = 1;

        return [
            'lists' => [
                $this->generateActivityJSON($server)
            ]
        ];
    }
}