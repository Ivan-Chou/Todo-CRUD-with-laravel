<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TodoListController;

Route::get('/', function (){
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login');
});
Route::post('/login', [AuthController::class, 'login']);

Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/register', function () {
    return view('register');
});
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/todolist', [TodoListController::class, 'showList']);
Route::post('/todolist/create', [TodoListController::class, 'addNewTodo']);
Route::delete('/todolist/delete/{todo_id}', [TodoListController::class, 'deleteTodo']);
