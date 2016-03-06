<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    /**
     * View listing
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listing()
    {
        return view('users', ['users' => User::all()]);
    }
}
