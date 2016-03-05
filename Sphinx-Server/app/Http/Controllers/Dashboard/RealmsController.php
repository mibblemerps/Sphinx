<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Realms\Server;

class RealmsController extends Controller
{
    public function listing()
    {
        return view('realms', ['realms' => Server::all()]);
    }
}
