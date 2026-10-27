<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If not logged in, redirect to login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. If the user has 'admin' role AND is NOT currently impersonating someone
        if (auth()->user()->role === 'admin' && !session()->has('admin_impersonator_id')) {
            return redirect()->route('admin.dashboard')->with('error', 'Admins cannot access member pages.');
        }

        return $next($request);
    }
}
