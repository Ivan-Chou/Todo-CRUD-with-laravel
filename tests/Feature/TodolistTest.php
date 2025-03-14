<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

use Tests\TestCase;

use App\Models\User;
use App\Models\Todo;

class TodolistTest extends TestCase
{
    use RefreshDatabase;

    // TODO: 建立 unauthenticated user 的測試 (for all actions)

    /**
     * an user should see all of his/her todolists
     */
    public function user_can_see(): void
    {
        $user = User::factory()->create([
            "username"=> "test",
            "password"=> Hash::make("test0000"),
        ]);

        $token = $user->createToken("authToken")->plainTextToken;

        $response = $this->get('/api/todolist', [
            'Authorization' => "Bearer $token",
        ]);
    }

    /**
     * an user can create a todolist
     * create a todo by POST "/api/todolist" 
     * 
     * @test
     */
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
        $response_refresh = $this->get('/api/todolist', [
            'Authorization' => "Bearer $token",
        ]);

        $response_refresh->assertStatus(200);

        // TODO: 理論上會收到 array of todos，需確認此 assert 是否可正確找到剛剛新增的 todo 單項
        $response_refresh->assertJson([
            'task'=> 'test task',
            'deadline'=> '2025-12-31',
        ]);
    }

    // TODO: 建立 "因 時間非未來 或 任務名稱為空 或 任務名稱過長" 的測試

    /**
     * an user can delete a todolist
     * delete a todo by DELETE "/api/todolist/{todo_id}"
     * 
     * @test
     */
    public function user_can_delete(): void
    {
        $user = User::factory()->create([
            "username"=> "test",
            "password"=> Hash::make("test0000"),
        ]);

        $token = $user->createToken("authToken")->plainTextToken;
        
        // 先 create，再 delete 該項目
        $response_create = $this->postJson('/api/todolist', [
            'user_id'=> $user->id,
            'task' => 'test task',
            "deadline"=> "2025-12-31",
        ], [
            'Authorization'=> "Bearer $token",
        ]);

        // 取得剛剛新增的 todo 的 id
        // TODO: 雖然理論上剛剛創建的會是最後一個，但這樣的寫法仍然存在 assumption
        $response_arr = $response_create->json();
        $todo_id = $response_arr[count($response_arr) - 1]["todo_id"];

        $response_delete = $this->deleteJson("/api/todolist/$todo_id", [], [
            "Authorization" => "Bearer $token",
        ]);

        $response_delete->assertStatus(200);
        $response_delete->assertJson([
            "success" => true,
            "message" => "Todo deleted successfully",
        ]);
    }

    // TODO: 建立 "未擁有權限(並非條目擁有者)" 的測試
    public function user_update_NotOwner(): void
    {
        // ...
    }

    // TODO: 建立 "刪除欄目不存在" 的測試
}
