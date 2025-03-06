<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        
        // check sqlite database for user
        $user = User::where('username', $credentials['username'])->first();
        
        if (!$user) {
            // tell the user that the username does not exist

            return redirect()->back()->with('error','UserDoesNotExist');
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            // tell the user that the password is incorrect

            return redirect()->back()->with('error','IncorrectPassword');
        }
        
        $request->session()->put('user', $user);

        // give the user a cookie
        $cookie = cookie('user', $user->username, 60);

        return redirect()->intended('/todolist')->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // remove the cookie
        $cookie = cookie('user', null, -1);

        return redirect()->intended('/login')->withCookie($cookie);
    }
}

// intended('/login')