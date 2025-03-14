<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * login successfully
     * 
     */
    #[Test]
    public function login_success(): void
    {
        $username = 'test';
        $password = 'test0000';

        $user = User::factory()->create([
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/login', [
            'username' => $username,
            'password' => $password,
        ]);

        // 登入成功
        $response->assertStatus(200);

        // 應該有 access_token (by sanctum)
        $response->assertJson(['success' => true]);
        $response->assertJsonStructure(['access_token', 'token_type']);
    }

    /**
     * login failed because user not exist
     * 
     */
    #[Test]
    public function login_failed_userNotExist(): void
    {
        $username = 'test';
        $password = 'test0000';

        // 未創建，直接使用不存在的帳號登入
        $response = $this->postJson('/api/login', [
            'username' => $username,
            'password' => $password,
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => false,
            'message' => 'User not found',
        ]);
    }

    /**
     * login failed because password is wrong
     * 
     */
    #[Test]
    public function login_failed_passwordNotCorrect(): void
    {
        $username = 'test';
        $password = 'test0000';

        $user = User::factory()->create([
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/login', [
            'username'=> $username,
            'password'=> $password . '1', // 某個錯誤的密碼
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => false,
            'message' => 'Password incorrect',
        ]);
    }

    /**
     * logout successfully
     * 
     */
    #[Test]
    public function logout_success(): void
    {
        $username = 'test';
        $password = 'test0000';

        $user = User::factory()->create([
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        // create token 後，在伺服器端就已經視為登入
        $token = $user->createToken('authToken')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200);
    }
}
