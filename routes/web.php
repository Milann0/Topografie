<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return Auth::check()
        ? view('welcome')
        : redirect()->route('student.login');
})->name('home');

function checkAdmin() {
    if (Auth::user()->role !== 'admin') {
        abort(403, 'Access denied');
    }
}

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        checkAdmin();
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('students')->group(function () {
        Route::get('/', function () { checkAdmin(); return app(StudentController::class)->index(); })->name('students.index');
        Route::get('/create', function () { checkAdmin(); return app(StudentController::class)->create(); })->name('students.create');
        Route::post('/store', function () { checkAdmin(); return app(StudentController::class)->store(request()); })->name('students.store');
        Route::get('/{student}', function (User $student) {
            checkAdmin();
            return app(StudentController::class)->show($student);
        })->name('students.show');

        Route::get('/{student}/edit', function (User $student) {
            checkAdmin();
            return app(StudentController::class)->edit($student);
        })->name('students.edit');

        Route::put('/{student}', function (User $student) {
            checkAdmin();
            return app(StudentController::class)->update(request(), $student);
        })->name('students.update');

        Route::delete('/{student}', function (User $student) {
            checkAdmin();
            return app(StudentController::class)->destroy($student);
        })->name('students.destroy');
    });

    Route::get('/games', function () {
        checkAdmin();
        return app(GameController::class)->indexAllGames();
    })->name('games.allIndex');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Game routes without admin check
    Route::get('/game', [GameController::class, 'index']);
    Route::get('/capitals', [GameController::class, 'cityIndex']);
    Route::post('/check', [GameController::class, 'check']);
    Route::post('/api/games/save-score', [GameController::class, 'saveScore'])->name('games.saveScore');
});

Route::middleware('guest')->group(function () {
    Route::get('/student/login', fn() => view('student.login'))->name('student.login');
    Route::post('/student/login', [StudentLoginController::class, 'login'])->name('student.login.submit');

    Route::get('/admin/login', fn() => view('auth.login'))->name('login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('student.login');
})->name('logout');

Route::get('/games/export', [GameController::class, 'exportCsv'])->name('games.exportCsv');
