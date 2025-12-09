<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Required for auto-login
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;     // Required for Password defaults
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Handle a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // Use Laravel's Password object for better security defaults
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer', 
        ]);

        event(new Registered($user));

        // 1. AUTO-LOGIN: Critical for SPAs
        // Since we use cookies, we log them in immediately. 
        // This sets the session cookie in the browser.
        Auth::login($user);

        // 2. NO TOKEN:
        // We removed $user->createToken(). It is insecure and unnecessary 
        // for a web-only SPA.

        return response()->json([
            'message' => 'Registration successful.',
            'user' => $user,
            // No 'token' returned here.
        ], 201);
    }
}