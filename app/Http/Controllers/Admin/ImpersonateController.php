<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    // Admin logs in as target user
    public function loginAs(User $user)
    {
        // Store original admin ID in session so admin can switch back
        session()->put('impersonated_by', auth()->id());

        Auth::login($user);

        return redirect()->route('dashboard')->with('status', "You are now logged in as {$user->name}.");
    }

    // Leave impersonation and return to admin
    public function leave()
    {
        $adminId = session()->pull('impersonated_by');

        if ($adminId) {
            $admin = User::find($adminId);
            if ($admin && $admin->role === 'admin') {
                Auth::login($admin);
                return redirect()->route('admin.payments.index')->with('success', 'Returned to Admin Panel.');
            }
        }

        return redirect('/');
    }
}
