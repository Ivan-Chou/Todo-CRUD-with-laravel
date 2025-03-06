<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $credentials = $request->only('username', 'password');

        error_log("Creating user with username=" . $credentials['username'] . ", password=" . $credentials['password']);

        // check if username obeys the rules: only letters and numbers, 3-20 characters
        if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $credentials['username'])) {
            // tell the user that the username is invalid

            return redirect()->back()->with('error','InvalidUsername');
        }
        
        // check sqlite database for user
        $user = User::where('username', $credentials['username'])->first();
        
        if ($user) {
            // tell the user that the username already exists
            
            return redirect()->back()->with('error','UserAlreadyExists');
        }

        
        $user = new User();
        $user->username = $credentials['username'];
        $user->password = Hash::make($credentials['password']);
        $user->save();

        return redirect()->back()->with('success','UserCreated');
    }
}

// intended('/register')