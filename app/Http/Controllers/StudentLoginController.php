<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StudentLoginController extends Controller
{
    public function login(Request $request)
    {
        // Valideer de input
        $request->validate([
            'name' => 'required|string',
            'lastname' => 'required|string',
        ]);

        $credentials = $request->only('name', 'lastname');
        
        // Zoek de gebruiker
        $user = User::where('name', $credentials['name'])
            ->where('lastname', $credentials['lastname'])
            ->first();

        // Check of gebruiker bestaat
        if ($user) {
            Auth::login($user);
            return redirect()->intended('/'); // Redirect naar intended pagina of home
        }

        // Als login faalt, ga terug met error
        return back()->withErrors([
            'name' => 'Ongeldige inloggegevens. Controleer je voor- en achternaam.',
        ])->withInput();
    }
}