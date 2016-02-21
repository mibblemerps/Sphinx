<?php

namespace App\Http\Controllers;

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
        if ($id == 0) {
            // Hardcoded server IP - debug purposes.
            return [
                'address' => 'us.mineplex.com:25565'
            ];
        }

        abort(404); // 404 Not Found
    }
}