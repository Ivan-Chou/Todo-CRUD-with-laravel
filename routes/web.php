<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function (){
    return redirect('/login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

// Todo: change callback func to return some view (maybe not used)
// Route::get('/logout', [AuthController::class, 'logout']);

Route::get('/register', function () {
    return view('register');
})->name('register');

// Todo: add an auto verify middleware and change get(todolist) to return some view 
