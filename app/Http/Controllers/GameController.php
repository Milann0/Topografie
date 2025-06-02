<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Game;

class GameController extends Controller
{
    public function index()
    {
        $countries = Country::all(['name', 'code']);

        return view('game', compact('countries'));
    }

        public function cityIndex()
    {
        $capitals = Country::all(['name', 'code']);

        return view('citiesGame', compact('capitals'));
    }

    public function indexAllGames()
    {
        $sort = request('sort');
        $direction = request('direction', 'asc');
        $search = request('search');

        $games = Game::with('user');

        if ($search) {
            $games->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%");
            });
        }

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
