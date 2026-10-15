<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // If user is NOT an admin, block them completely
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Access denied. You do not have administrator permissions.');
        }

        return $next($request);
    }
}
