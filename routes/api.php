<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Public API
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

// Sanctum session-protected user route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
