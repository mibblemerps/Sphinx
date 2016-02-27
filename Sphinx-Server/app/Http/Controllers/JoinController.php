<?php

namespace App\Http\Controllers;
use App\Facades\SphinxNode;
use App\Realms\Player;
use App\Realms\Server;

/**
 * Class ControllerJoin
 * @package App\Http\Controllers
 */
class JoinController extends Controller
{
    /**
     * Join a server.
     *
     * @param int $id
     * @return array
     */
    public function join($id)
    {
        $server = Server::findOrFail($id);
        if (!$server->isInvited(Player::current())) {
            // Not invited. Sorry! :(
            abort(403); // 403 Forbidden.
        }

        return [
            'address' => SphinxNode::joinServer($server->id)
        ];
    }
}