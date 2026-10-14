<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $referrals = $user->referrals()->with('activePackage.package')->latest()->get();

        return view('team', compact('user', 'referrals'));
    }
}
