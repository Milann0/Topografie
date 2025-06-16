<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
            return redirect()->intended('/dashboard');

        Auth::logout();

        return back()->withErrors([
            'email' => 'Je hebt geen toegang tot het admin dashboard.',
        ]);
    }

    return back()->withErrors([
        'email' => 'De opgegeven gegevens zijn incorrect.',
    ]);
}
}
