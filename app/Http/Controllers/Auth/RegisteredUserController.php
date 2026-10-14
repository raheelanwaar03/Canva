<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\user\Wallet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Client requirement: Registration MUST have a valid referral code or link
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['required', 'string', 'exists:users,referral_code'],
        ], [
            'referral_code.exists' => 'The referral link/code is invalid. A valid inviter is required.',
        ]);

        $referrer = User::where('referral_code', $request->referral_code)->first();

        $user = DB::transaction(function () use ($request, $referrer) {
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'referral_code' => strtoupper(Str::random(8)),
                'referred_by' => $referrer->id,
                'role' => 'user',
                'status' => 'active',
            ]);

            // Auto-create initial wallet
            $newUser->wallet()->create([
                'main_balance' => 0.00,
                'task_earnings' => 0.00,
                'referral_earnings' => 0.00,
                'staff_earnings' => 0.00,
                'withdrawable_balance' => 0.00,
            ]);

            return $newUser;
        });

        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
