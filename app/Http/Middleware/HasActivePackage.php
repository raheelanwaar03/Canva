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

        // Whitelisted onboarding routes - DO NOT REDIRECT if user is already on these
        if ($request->routeIs([
            'packages.index',
            'packages.pending',
            'packages.rejected',
            'packages.checkout',
            'packages.submitPayment',
            'packages.resubmit',
            'profile.*',
            'logout'
        ])) {
            return $next($request);
        }

        // If user already has an active, approved package, let them proceed
        if ($user->activePackage) {
            return $next($request);
        }

        // Check the latest payment attempt
        $latestPayment = $user->packagePayments()->latest()->first();

        if ($latestPayment) {
            if ($latestPayment->status === 'pending') {
                return redirect()->route('packages.pending');
            }

            if ($latestPayment->status === 'rejected') {
                return redirect()->route('packages.rejected');
            }
        }

        // No payment made yet -> send to package selection
        return redirect()->route('packages.index')->with('error', 'Please choose a package to activate your account.');
    }
}
