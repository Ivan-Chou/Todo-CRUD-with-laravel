<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository {
    /**
     * Get the model name
     * 
     * @return string
     */
    public function model(): string {
        return Todo::class;
    }

    /**
     * find single todo by id
     * @param int $todo_id
     * @return Todo|null
     */
    public function getTodoById(int $todo_id): Todo{
        // guarantee: 若 todo 存在，則必只有一個(因為 id 是 primary key)
        return Todo::find($todo_id);
    }

    public function create(array $data): Todo {
        return Todo::create($data);
    }

    public function delete(int $id): bool {
        $todo = Todo::find($id);
        $ret = false;

        if($todo){
            $ret = $todo->delete();
        }
        
        return $ret;
    }
}