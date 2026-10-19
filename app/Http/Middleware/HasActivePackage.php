<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasActivePackage
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Admins bypass package requirement
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user has an active, unexpired package
        if (!$user->activePackage) {
            // Check if user submitted a payment that is pending approval
            $hasPending = $user->packagePayments()->where('status', 'pending')->exists();

            if ($hasPending) {
                return redirect()->route('packages.pending');
            }

            return redirect()->route('packages.index')->with('error', 'Please choose a membership package to activate your account.');
        }

        return $next($request);
    }
}
