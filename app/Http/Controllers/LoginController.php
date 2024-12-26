<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function formLogin()
    {
        $key = "login.".request()->ip();
        return view('auth.login',[
            'key'=>$key,
            'retries'=>RateLimiter::retriesLeft($key, 5),
            'seconds'=>RateLimiter::availableIn($key),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $key = "login." . $request->ip();
        $maxAttempts = 5;
        $decayMinutes = 5;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in ' . $decayMinutes . ' minutes.',
            ]);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            RateLimiter::hit($key, $decayMinutes * 60);

            $retries = RateLimiter::retriesLeft($key, $maxAttempts);

            throw ValidationException::withMessages([
                'email' => 'Invalid email or password. ' . $retries . ' attempts remaining.',
            ]);
        }

        RateLimiter::clear($key);

        return redirect('/dashboard');
    }
}
