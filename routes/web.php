<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

Route::middleware(['web'])->group(function () {

    // Authentication (Session-based)
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

    // Protected user info
    Route::get('/user', function () {
        return auth()->user();   // returns currently logged-in session user
    })->middleware('auth');
});
