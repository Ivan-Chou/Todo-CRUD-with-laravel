<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * 獲取表中所有 users
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getUserById(int $id)
    {
        return $this->userRepository->getUserById($id);
    }

    public function getUserByName(string $name)
    {
        return $this->userRepository->getUserByName($name);
    }
    /**
     * 創建新使用者
     * 
     * @param array ['username' => string, 'password' => string]
     * 
     * @return array ['success' => bool, 'message' => string]
     */
    public function createNewUser(array $data)
    {

        ['username' => $username, 'password' => $password] = $data;

        $stat = [
            "success" => true,
            "message" => "User created successfully",
        ];

        // 檢查使用者名稱合法與否 => by FormRequest

        $user = $this->userRepository->getUserByName($username);

        if ($user) {
            $stat["success"] = false;
            $stat["message"] = "User already exists";
            
            // early return
            return $stat; 
        }
        
        $user = $this->userRepository->create([
            "username" => $username, 
            "password" => Hash::make($password),
        ]);

        if(!$user) {
            $stat["success"] = false;
            $stat["message"] = "Failed to create user";
        }

        return $stat;
    }

    /**
     * 驗證使用者
     * 
     * @param array ['username' => string, 'password' => string]
     * 
     * @return array ['success' => bool, 'message' => string, 'user' => User]
     */
    public function verifyUser(array $data)
    {
        ['username' => $username, 'password' => $password] = $data;

        $stat = [
            "success"=> true,
            "message"=> "Logged in successfully",
            "user"=> null,
        ];

        $user = $this->userRepository->getUserByName($username);

        if (!$user) {
            $stat["success"] = false;
            $stat["message"] = "User not found";
        }
        else if (!Hash::check($password, $user->password)){
            $stat["success"] = false;
            $stat["message"] = "Password incorrect";
        }

        $stat["user"] = $user;

        return $stat;
    }
}