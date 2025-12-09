<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Check Rate Limiting (Production Security)
        $throttleKey = Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
        
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // 3. Attempt Login (with 'Remember Me' support)
        // We check for a boolean 'remember' field in the request
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            
            // Increment the failure counter
            RateLimiter::hit($throttleKey);

            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        // 4. Clear Rate Limiter on Success
        RateLimiter::clear($throttleKey);

        // 5. Regenerate Session (Prevent Fixation)
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login successful.',
            'user' => Auth::user()
        ], 200);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out successfully']);
    }
}