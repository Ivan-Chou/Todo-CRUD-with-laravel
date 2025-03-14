<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use PHPUnit\Framework\Attributes\Test;

use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * register successfully
     *
     */
    #[Test]
    public function register_success(): void
    {
        $response = $this->postJson('/api/register', [
            'username' => 'test',
            'password' => 'test0000',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas(User::class, ['username' => 'test']);
    }

    /**
     * register failed because username is too short
     * should be blocked by FormRequest (UserInfoRequest)
     * 
     */
    #[Test]
    public function register_failed_usernameTooShort(): void
    {
        $response = $this->postJson('/api/register', [
            'username'=> 't',
            'password'=> 'test0000',
        ]);

        // FormRequest 阻擋的預設行為是回傳 422
        $response->assertStatus(422);
        
        // 確認資料庫中沒新增
        $this->assertDatabaseMissing(User::class, ['username'=> 't']);
        
        // 確認錯誤欄目是 username
        $response->assertJsonValidationErrors(['username']);
    }

    /**
     * register failed because password is too short
     * should be blocked by FormRequest (UserInfoRequest)
     * 
     */
    #[Test]
    public function register_failed_passwordTooShort(): void
    {
        $response = $this->postJson('/api/register', [
            'username'=> 'test',
            'password'=> 't',
        ]);

        // FormRequest 阻擋的預設行為是回傳 422
        $response->assertStatus(422);

        // 確認資料庫中沒新增
        $this->assertDatabaseMissing(User::class, ['username'=> 'test']);

        // 確認錯誤欄目是 password
        $response->assertJsonValidationErrors(['password']);
    }
}
