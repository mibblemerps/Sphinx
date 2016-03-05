<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Realms\Player;
use App\Realms\Server;
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
        return view('realms', ['realms' => Server::all()]);
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
        $realm = new Server();
        $realm->name = $realmName;
        $realm->owner = $player;
        $realm->state = Server::STATE_UNINITIALIZED;
        $realm->save();

        // All good!
        return [
            'success' => true
        ];
    }
}
