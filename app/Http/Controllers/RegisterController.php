<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Services\UserService;

class RegisterController extends Controller
{
    protected $userServices;
    
    public function __construct(UserService $userServices){
        $this->userServices = $userServices;
    }

    public function register(Request $request)
    {
        // Todo: use FormRequest instead of Request to validate the input
        // // check if username obeys the rules: only letters and numbers, 3-20 characters
        // if (!preg_match('/^[a-zA-Z0-9]{3,20}$/', $credentials['username'])) {
        //     // tell the user that the username is invalid

        //     return redirect()->back()->with('error','InvalidUsername');
        // }

        $credentials = $request->only('username', 'password');
        
        $createStatus = $this->userServices->createNewUser($credentials);

        return response()->json($createStatus);
    }
}

// intended('/register')