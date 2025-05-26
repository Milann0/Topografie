<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        $land = Country::inRandomOrder()->first();

        return view('game', compact('land'));
    }

    public function indexAllGames()
    {
        $sort = request('sort');
        $direction = request('direction', 'asc');
        $games = Game::with('user');
        if ($sort === 'score') {
            $games->orderBy('score', $direction);
        } elseif ($sort === 'user') {
            // Join with users to sort by their name
            $games->join('users', 'games.user_id', '=', 'users.id')
                ->orderBy('users.name', $direction)
                ->select('games.*');
        } elseif ($sort === 'finished') {
            $games->orderBy('created_at', $direction);
        }
        $games = $games->get();
        return view('games.index', compact('games'));
    }
}
