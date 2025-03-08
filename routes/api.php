<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TodoListController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/todolist', [TodoListController::class, 'index']);
    Route::post('/todolist', [TodoListController::class, 'store']);
    Route::delete('/todolist/{todo_id}', [TodoListController::class, 'destroy']);
});