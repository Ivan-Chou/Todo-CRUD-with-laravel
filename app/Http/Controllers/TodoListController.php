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

    /**
     * Get the full list of todos of the user
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $user->load('todos');

        return response()->json($user->todos);
    }

    /**
     * Create a new todo
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Todo: use FormRequest instead of Request to validate the input

        $data = $request->only('task', 'deadline');
        
        $createStatus = $this->todoService->createNewTodo($data, $request->user()->id);

        return response()->json($createStatus);
    }

    /**
     * Delete a todo
     * 
     * @param \Illuminate\Http\Request $request
     * @param string $todo_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, string $todo_id){
        $deleteStatus = $this->todoService->deleteTodo($todo_id, $request->user()->id);
        
        return response()->json($deleteStatus);
    }

    // public function editTodo(Request $request, Todo $todo){}
}