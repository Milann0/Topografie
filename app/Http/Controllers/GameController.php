<?php

namespace App\Http\Controllers;

use App\Models\Country;

class GameController extends Controller
{
    public function index()
    {
        $land = Country::inRandomOrder()->first();

        return view('game', compact('land'));
    }
}
