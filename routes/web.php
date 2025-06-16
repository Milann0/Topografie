<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\StudentController;

// Root: check ingelogd, anders redirect naar student login
Route::get('/', function () {
    if (Auth::check()) {
        return view('welcome'); // of dashboard
    }
    return redirect()->route('student.login');
});

// Dashboard alleen bereikbaar als ingelogd én verified
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profiel routes (alleen ingelogd)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Games
Route::get('/game', [GameController::class, 'index']);
Route::get('/capitals', [GameController::class, 'cityIndex']);
Route::post('/check', [GameController::class, 'check']);
Route::get('/games', [GameController::class, 'indexAllGames'])->name('games.allIndex');

// Studentenbeheer - Complete CRUD operations
Route::get('/students', [StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');
Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

// Student login GET + POST
Route::get('/student/login', function () {
    return view('student.login');
})->name('student.login');
Route::post('/student/login', [StudentLoginController::class, 'login'])->name('student.login.submit');

// Admin login GET + POST
Route::get('/login', function () {
    return view('auth.login'); // Laravel standaard admin login view
})->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout route
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login'); // of redirect naar student.login als je wilt
})->name('logout');