<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Trasy dostępne dla wszystkich
Route::resource('posts', PostController::class)->only(['index', 'show']); // Brak middleware dla tych dwóch metod
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Trasy dostępne tylko dla zalogowanych
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});


// Trasy dostępne tylko dla administratorów
Route::middleware([AdminMiddleware::class, 'auth'])->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});


require __DIR__.'/auth.php';
