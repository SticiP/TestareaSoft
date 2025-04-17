<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function ()
{
    Route::get('/login', [UserController::class, 'login_page'])->name('login.page');
    Route::get('/register', [UserController::class, 'register_page'])->name('register.page');

    Route::post('/register', [UserController::class, 'register'])->name('register');
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

Route::middleware('user')->group(function ()
{
    Route::get('/', function () {
        return view('index');
    })->name('home');
});

Route::post('/logout', [UserController::class, 'logout'])->name('logout');


