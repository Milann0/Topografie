<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
public function login(Request $request)
{
    $credentials = $request->only('name', 'lastname');

    $user = \App\Models\User::where('name', $credentials['name'])
        ->where('lastname', $credentials['lastname'])
        ->first();

    Auth::login($user);
    return view('welcome');

    return back()->withErrors([
        'name' => 'Ongeldige inloggegevens.',
    ]);
}

}
