<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Create a new user.
     *
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        // Validate request.
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:32|unique:users',
            'email' => 'required|email|max:96|unique:users',
            'password' => 'required|max:96|min:6',
            'confirm_password' => 'required|same:password'
        ]);
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');

        if ($validator->fails()) {
            // Validation failed.
            return [
                'success' => false,
                'errors' => $validator->errors()
            ];
        }

        // Create user.
        User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        // All done!
        return [
            'success' => true
        ];
    }

    /**
     * Remove a user.
     *
     * @param Request $request
     * @return mixed
     */
    public function remove(Request $request)
    {
        // Validate request.
        $this->validate($request, [
            'userid' => 'required|exists:users,id'
        ]);

        // Remove user.
        $user = User::find($request->input('userid'));
        $user->delete(); // :(

        // Done.
        return [
            'success' => true
        ];
    }
}
