<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Public API (no session)
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

// Protected API routes (requires session cookie)
// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/profile', function () {
//         return auth()->user();
//     });
// });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});