<?php

namespace App\Http\Controllers\Dashboard;

use App\Facades\SphinxNode;
use App\Http\Controllers\Controller;
use App\Realms\Player;
use App\Realms\Realm;
use Illuminate\Http\Request;

class RealmsController extends Controller
{
    /**
     * Get Realms management page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listing()
    {
        return view('realms', ['realms' => Realm::all()]);
    }

    /**
     * Create new Realm.
     * Should be called with AJAX.
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        // Validate request.
        $this->validate($request, [
            'name' => 'required|max:32',
            'owner' => 'required|max:64'
        ]);
        $realmName = $request->input('name');
        $owner = $request->input('owner');

        // Get owner's UUID
        $player = new Player(null, $owner);
        $player->lookupFromApi();
        if ($player->uuid === null) {
            // Bad username error.
            return [
                'success' => false,
                'error' => 'bad_username'
            ];
        }

        // Create Realm.
        Realm::create([
            'address' => '',
            'state' => Realm::STATE_UNINITIALIZED,
            'name' => $realmName,
            'days_left' => 365,
            'expired' => false,
            'invited_players' => [$player],
            'operators' => [$player],
            'minigames_server' => false,
            'motd' => 'Carrots are good for your eyesight.',
            'owner' => $player
        ]);

        // All good!
        return [
            'success' => true
        ];
    }

    /**
     * Remove a Realm, deleting it from database and removing it's associated server files.
     *
     * @param Request $request
     * @return array
     */
    public function remove(Request $request)
    {
        // Validate request.
        $this->validate($request, [
            'serverid' => 'required'
        ]);
        $serverId = $request->input('serverid');

        $server = Realm::findOrFail($serverId);

        $server->delete(); // :(

        // Let Sphinx Node know about the change.
        SphinxNode::sendManifest([$server->id]);

        // Deleted Realm!
        return [
            'success' => true
        ];
    }
}
