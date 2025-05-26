<?php

namespace App\Http\Controllers;

use App\Models\Country;

class GameController extends Controller
{
    public function index()
    {
        $countries = Country::all(['name', 'code']);

        return view('game', compact('countries'));
    }
}
