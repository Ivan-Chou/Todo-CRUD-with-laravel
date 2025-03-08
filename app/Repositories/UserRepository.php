<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Get the model name
     * 
     * @return string
     */
    public function model(): string{
        return User::class;
    }

    /**
     * Get all users
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * 以 id 取得使用者
     * 
     * @param int $id
     * @return \App\Models\User
     */
    public function getUserById(int $id){
        return User::find($id);
    }

    /**
     * 以 username 取得使用者
     * 
     * @param string $username
     * @return \App\Models\User
     */
    public function getUserByName(string $username){
        return User::where('username', $username)->first();
    }

    /**
     * 建立新使用者
     * 
     * @param string $username
     * @param string $password
     * @return \App\Models\User
     */
    public function create(array $data){
        // P.S.: 
        $user = User::create($data);

        return $user;
    }
}