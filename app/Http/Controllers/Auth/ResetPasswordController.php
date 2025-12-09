<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Attempt password reset
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update password
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        // Response for API
        return response()->json([
            'message' => __($status),
        ], $status === Password::PASSWORD_RESET ? 200 : 400);
    }
}
