<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;

use App\Models\User;
use App\Models\Todo;
use function PHPUnit\Framework\assertJson;

class TodolistTest extends TestCase
{
    use RefreshDatabase;

    // TODO: 建立 unauthenticated user 的測試 (for all actions)

    /**
     * an user should see all of his/her todolists
     * 
     */
    #[Test]
    public function user_can_see(): void
    {
        $user = User::factory()->create([
            "username"=> "test",
            "password"=> Hash::make("test0000"),
        ]);

        $token = $user->createToken("authToken")->plainTextToken;

        // 以 TodoFactory 建立一筆資料
        $todo = Todo::factory()->create([
            'user_id'=> $user->id,
            'task'=> 'test task',
            'deadline'=> '2025-12-31',
        ]);

        // 先檢查是否可見
        $response = $this->getJson('/api/todolist', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);

        $response->assertJson([[
            'task'=> 'test task',
            'deadline'=> '2025-12-31',
        ]]);
    }

    /**
     * an user can create a todolist
     * create a todo by POST "/api/todolist" 
     * 
     */
    #[Test]
    public function user_can_create(): void
    {
        $user = User::factory()->create([
            "username"=> "test",
            "password"=> Hash::make("test0000"),
        ]);

        $token = $user->createToken("authToken")->plainTextToken;

        $response = $this->postJson('/api/todolist', [
            'user_id'=> $user->id,
            'task' => 'test task',
            "deadline"=> "2025-12-31",
        ], [
            'Authorization'=> "Bearer $token",
        ]);

        // 1. 確認創建成功 i.e. 資料庫中存在 & 回應訊息正確
        $response->assertStatus(200);

        $response->assertJson([
            "success" => true,
            "message" => "Todo created successfully",
        ]);

        $this->assertDatabaseHas(Todo::class, [
            'user_id'=> $user->id,
            'task'=> 'test task',
            'deadline'=> '2025-12-31',
        ]);
        
        // 2. 確認使用者刷新後可見
        $response_refresh = $this->getJson('/api/todolist', [
            'Authorization' => "Bearer $token",
        ]);

        $response_refresh->assertStatus(200);

        // 在 target Json 外再包一層 array 以在 array of todos 裡尋找
        $response_refresh->assertJson([[
            'task'=> 'test task',
            'deadline'=> '2025-12-31',
        ]]);
    }

    // TODO: 建立 "因 時間非未來 或 任務名稱為空 或 任務名稱過長" 的測試

    /**
     * an user can delete a todolist
     * delete a todo by DELETE "/api/todolist/{todo_id}"
     *
     */
    #[Test]
    public function user_can_delete(): void
    {
        $user = User::factory()->create([
            "username"=> "test",
            "password"=> Hash::make("test0000"),
        ]);

        $token = $user->createToken("authToken")->plainTextToken;
        
        // 以 TodoFactory 建立一筆資料
        $todo = Todo::factory()->create([
            'user_id'=> $user->id,
            'task'=> 'test task',
            'deadline'=> '2025-12-31',
        ]);

        $response_delete = $this->deleteJson("/api/todolist/$todo->id", [], [
            "Authorization" => "Bearer $token",
        ]);

        $response_delete->assertStatus(200);
        $response_delete->assertJson([
            "success" => true,
            "message" => "Todo deleted successfully",
        ]);
    }

    /** 
     * "未擁有權限(並非條目擁有者)" 的測試
     * Created by user1, but deleted by user2 
     */
    #[Test]
    public function user_delete_NotOwner(): void
    {
        $user1 = User::factory()->create([
            "username"=> "test",
            "password"=> Hash::make("test0000"),
        ]);

        $user2 = User::factory()->create([
            "username"=> "test2",
            "password"=> Hash::make("test0000"),
        ]);

        $todo = Todo::factory()->create([
            'user_id'=> $user1->id,
            'task'=> 'test task',
            'deadline'=> '2025-12-31',
        ]);

        $token = $user2->createToken("authToken")->plainTextToken;

        $response = $this->delete("/api/todolist/$todo->id", [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);

        $response->assertJson([
            "success" => false,
            "message" => "Not the owner of this todo",
        ]);
    }

    // TODO: 建立 "刪除欄目不存在" 的測試
}
