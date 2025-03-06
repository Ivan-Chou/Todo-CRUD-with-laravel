<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Todo;

class TodoListController extends Controller
{
    public function showList(Request $request)
    {
        $user = $request->session()->get('user');

        if (!$user) {
            return redirect()->intended('/login');
        }

        $user->load('todos');

        return view('todolist', ['username' => $user->username, 'todos' => $user->todos]);
    }

    public function addNewTodo(Request $request)
    {
        $user = $request->session()->get('user');

        if (!$user) {
            return redirect()->intended('/login');
        }

        $content = $request->only('task', 'deadline');

        // $task = $request->input('task');
        // $deadline = $request->input('deadline');

        $todo = new Todo();
        $todo->user_id = $user->id;
        $todo->task = $content['task'];
        $todo->deadline = $content['deadline'];
        $todo->save();

        return redirect()->intended('/todolist');
    }

    public function deleteTodo(Request $request, string $todo_id){
        $user = $request->session()->get('user');

        if (!$user) {
            return redirect()->intended('/login');
        }

        $toRemove = Todo::find($todo_id);
        
        if ($toRemove) {
            $toRemove->delete();
        }
        
        return redirect()->intended('/todolist');
    }

    // public function editTodo(Request $request, Todo $todo){}
}