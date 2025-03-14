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
     * @param array ['task' => string, 'deadline' => string]
     * @param int $userId
     * 
     * @return array ['success' => bool, 'message' => string]
     **/
    public function createNewTodo(array $data, int $userId){
        $stat = [
            "success" => true,
            "message" => "Todo created successfully",
        ];
        
        // TODO: use FormRequest instead of Request to validate the input
        
        // // check whether the task is empty
        // if(!isset($data["task"])){
        //     $stat['success'] = false;
        //     $stat['message'] = 'Task cannot be empty';
        //     return $stat;
        // }

        // // check whether the deadline is valid (not empty && later than "now")
        // if(!isset($data['deadline']) || strtotime($data['deadline']) < time()){
        //     $stat['success'] = false;
        //     $stat['message'] = 'Invalid deadline';
        //     return $stat;
        // }

        $todo_info = $data;

        $todo_info['user_id'] = $userId;

        $todo = $this->todoRepository->create($todo_info);

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
     * @return array ['success' => bool, 'message' => string]
     **/
    public function deleteTodo(int $id, int $userId): array{
        $stat = [
            "success" => true,
            "message" => "Todo deleted successfully",
        ];

        $toDelete = $this->todoRepository->getTodoById($id);

        // check whether the todo exists
        if(!$toDelete){
            $stat["success"] = true;
            $stat["message"] = "Todo not found";
            return $stat;
        }

        // check whether the user has the permission to delete that todo
        if($userId != $toDelete->user_id){
            $stat["success"] = false;
            $stat["message"] = "Not the owner of this todo";
            return $stat;
        }

        $this->todoRepository->delete($id);

        return $stat;
    }
}