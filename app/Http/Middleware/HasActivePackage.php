<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasActivePackage
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Allow access if user has ever purchased a plan (active OR expired)
        if (!$user->hasPurchasedPlan()) {
            return redirect()->route('packages.index')
                ->with('error', 'Please activate a package first to access your dashboard.');
        }

        return $next($request);
    }
}
