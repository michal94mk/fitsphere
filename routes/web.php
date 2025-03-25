<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscribeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Trasy dostępne dla wszystkich
Route::resource('posts', PostController::class)->only(['index', 'show']); // Brak middleware dla tych dwóch metod
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'handleForm'])->name('contact.submit');
Route::post('/subscribe', [SubscribeController::class, 'store'])->name('subscribe.store');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');


// Trasy dostępne tylko dla zalogowanych
Route::middleware('auth', 'verified')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

// Trasy dostępne tylko dla administratorów
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('users/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/admins', [UserController::class, 'admins'])->name('users.admins');
    Route::get('users/trainers', [UserController::class, 'trainers'])->name('users.trainers');
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('comments', CommentController::class);
    Route::resource('users', UserController::class);
});


require __DIR__.'/auth.php';
