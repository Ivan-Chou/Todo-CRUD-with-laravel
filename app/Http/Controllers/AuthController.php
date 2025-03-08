<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    /**
     * 判斷使用者是否登入成功
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Todo: use FormRequest instead of Request to validate the input

        $credentials = $request->only('username', 'password');

        //
        
        $loginStatus = $this->userService->verifyUser($credentials);
        
        // Todo: add token to the user

        if($loginStatus['success']){
            $user = $loginStatus['user'];

            $token = $user->createToken('authToken')->plainTextToken;

            $loginStatus['access_token'] = $token;
            $loginStatus['token_type'] = 'Bearer';
        }

        // Thinking: maybe remove the User from the login status?

        return response()->json($loginStatus);
    }

    public function logout(Request $request)
    {
        // remove the token?

        $request->user()->tokens()->delete();

        return response()->json();
    }
}

// intended('/login')