<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Rule 1: If an Admin tries to access user pages, send them back to the Admin Portal!
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Rule 2: If user is banned, logout and redirect with error
        if (auth()->user()->status === 'banned') {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Please contact support.',
            ]);
        }

        return $next($request);
    }
}
