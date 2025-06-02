<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/game', [GameController::class, 'index']);
Route::post('/check', [GameController::class, 'check']);
Route::get('/games', [GameController::class, 'indexAllGames'])->name('games.allIndex');

Route::get('/students', [\App\Http\Controllers\StudentController::class, 'index'])->name('students.index');
Route::get('/students/create', [\App\Http\Controllers\StudentController::class, 'create'])->name('students.create');
Route::post('/students/store', [\App\Http\Controllers\StudentController::class, 'store'])->name('students.store');
Route::get('/students/{student}', [\App\Http\Controllers\StudentController::class, 'show'])->name('students.show');
Route::delete('/students/{student}', [\App\Http\Controllers\StudentController::class, 'destroy'])->name('students.destroy');
Route::get('/students/{student}/edit', [\App\Http\Controllers\StudentController::class, 'edit'])->name('students.edit');
Route::put('/students/{student}', [\App\Http\Controllers\StudentController::class, 'update'])->name('students.update');

Route::get('/student/login', function () {  return view('student.login'); })->name('student.login');


require __DIR__.'/auth.php';
