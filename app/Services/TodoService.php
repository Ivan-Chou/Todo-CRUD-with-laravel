<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use function PHPUnit\Framework\isNull;

class TodoService{
    protected $todoRepository;

    public function __construct(TodoRepository $todoRepository){
        $this->todoRepository = $todoRepository;
    }

    /**
     * create new todo in the database
     * 
     * @param array ['user_id' => int, task => string, deadline => string]
     * 
     * @return array [success, message]
     **/
    public function createNewTodo(array $data){
        // [$task, $deadline] = $data;
        $stat = [
            "success" => true,
            "message" => "Todo created successfully",
        ];
        
        // Thinking: 如果我需要去查 User Table 以取得 User ID 甚或 object，我是否可以引入 UserRepository 來處理？
        $todo = $this->todoRepository->create($data);

        if(!$todo){
            $stat['success'] = false;
            $stat['message'] = 'Todo creation failed';
        }

        return $stat;
    }

    /**
     * delete todo by id
     * 
     * @param int $id
     * 
     * @return array [success, message]
     **/
    public function deleteTodo(int $id): array{
        $stat = [
            "success" => true,
            "message" => "Todo deleted successfully",
        ];

        // Todo: (?)check whether the user has the permission to delete that todo

        $delResult = $this->todoRepository->delete($id);

        if(!$delResult){
            $stat["success"] = true;
            $stat["message"] = "Todo not found";
        }

        return $stat;
    }
}