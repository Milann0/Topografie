<?php
namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $direction = request('direction', 'desc');
        $search = request('search');
        $gameType = request('game_type');
        
        $games = Game::with('user');
        
        // Apply search filter
        if ($search) {
            $games->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%");
            });
        }
        
        // Apply game type filter
        if ($gameType) {
            $games->where('game_type', $gameType);
        }
        
        // Apply sorting
        if ($sort === 'score') {
            $games->orderBy('score', $direction);
        } elseif ($sort === 'user') {
            $games->join('users', 'games.user_id', '=', 'users.id')
                ->orderBy('users.name', $direction)
                ->select('games.*');
        } elseif ($sort === 'finished') {
            $games->orderBy('created_at', $direction);
        } elseif ($sort === 'game_type') {
            $games->orderBy('game_type', $direction);
        } else {
            // Default sorting by most recent
            $games->orderBy('created_at', 'desc');
        }
        
        $games = $games->get();
        return view('games.index', compact('games'));
    }
    
    /**
     * Save game score to database
     */
    public function saveScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer|min:0',
            'total_questions' => 'required|integer|min:1|max:100',
            'game_type' => 'required|string|in:countries,capitals'
        ]);
        
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        // Validate score is not higher than total questions
        if ($request->score > $request->total_questions) {
            return response()->json([
                'success' => false,
                'message' => 'Score cannot be higher than total questions'
            ], 422);
        }
        
        try {
            $percentage = $request->total_questions > 0 
                ? round(($request->score / $request->total_questions) * 100, 2) 
                : 0;
            
            $game = Game::create([
                'user_id' => Auth::id(),
                'score' => $request->score,
                'total_questions' => $request->total_questions,
                'game_type' => $request->game_type,
                'percentage' => $percentage
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Score saved successfully',
                'game_id' => $game->id,
                'percentage' => $percentage
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to save game score: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save score. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Get user's game statistics
     */
    public function getUserStats()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        $userId = Auth::id();
        
        $stats = [
            'total_games' => Game::where('user_id', $userId)->count(),
            'average_score' => Game::where('user_id', $userId)->avg('percentage'),
            'best_score' => Game::where('user_id', $userId)->max('percentage'),
            'countries_games' => Game::where('user_id', $userId)->where('game_type', 'countries')->count(),
            'capitals_games' => Game::where('user_id', $userId)->where('game_type', 'capitals')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    public function exportCsv()
    {
        $games = Game::with('user')
            ->when(request('search'), fn($query) => $query->whereHas('user', fn($q) => $q->where('name', 'like', '%'.request('search').'%')))
            ->when(request('game_type'), fn($query) => $query->where('game_type', request('game_type')))
            ->get();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="games.csv"');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($output, ['ID', 'SCORE', 'PERCENTAGE (%)', 'USER NAME', 'GAME TYPE', 'FINISHED']);

        foreach ($games as $game) 
        {
            fputcsv($output, [
                $game->id,
                $game->score . '/' . ($game->total_questions ?? 'N/A'),
                number_format($game->percentage ?? 0, 2),  
                $game->user->name . ' ' . $game->user->lastname,
                ucfirst($game->game_type),
                $game->finished ? 'Yes' : 'No',
            ]);
        }

        fclose($output);
        exit;
    }
}