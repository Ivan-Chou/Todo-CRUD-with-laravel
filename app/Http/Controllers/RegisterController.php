<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use App\Http\Requests\UserInfoRequest;

use App\Services\UserService;

class RegisterController extends Controller
{
    protected $userServices;
    
    public function __construct(UserService $userServices){
        $this->userServices = $userServices;
    }

    public function register(UserInfoRequest $request)
    {
        // $request->validated returns value, but not used since we'll extract data by $request->only(...)
        $request->validated();

        $credentials = $request->only("username", "password");
        
        $createStatus = $this->userServices->createNewUser($credentials);

        return response()->json($createStatus);
    }
}

// intended('/register')