<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\TodoService;

class TodoListController extends Controller
{
    protected $todoService;

    public function __construct(TodoService $todoService){
        $this->todoService = $todoService;
    }

    public function index(Request $request)
    {
        // Todo: use FormRequest instead of Request to validate the input
        $user = $request->user();

        $user->load('todos');

        return response()->json($user->todos);
    }

    public function store(Request $request)
    {
        // Todo: use FormRequest instead of Request to validate the input

        $data = $request->only('task', 'deadline');

        $data['user_id'] = $request->user()->id;
        
        $createStatus = $this->todoService->createNewTodo($data);

        return response()->json($createStatus);
    }

    public function destroy(Request $request, string $todo_id){
        $deleteStatus = $this->todoService->deleteTodo($todo_id);
        
        return response()->json($deleteStatus);
    }

    // public function editTodo(Request $request, Todo $todo){}
}