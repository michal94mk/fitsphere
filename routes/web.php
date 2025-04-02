<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Livewire\HomePage;
use App\Livewire\AboutPage;
use App\Livewire\ContactPage;
use App\Livewire\PostView;
use App\Livewire\PostDetails;
use App\Livewire\PostsList;
use App\Livewire\TermsPage;
use App\Models\Post;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;


// Public routes - Available to all users
Route::get('/home', HomePage::class)->name('home');
Route::get('/posts', PostsList::class)->name('posts');
Route::get('/post/{postId}', PostDetails::class)->name('post.show');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/terms', TermsPage::class)->name('terms');

// Protected routes - Only authenticated & verified users can access
Route::middleware('auth', 'verified')->group(function () {
    // Dashboard view
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Comments functionality
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    
    // Post management (excluding index and show, handled by public routes)
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});

// Admin routes - Only accessible by users with 'admin' middleware
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    // Admin dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // User management
    Route::get('users/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/admins', [UserController::class, 'admins'])->name('users.admins');
    Route::get('users/trainers', [UserController::class, 'trainers'])->name('users.trainers');
    Route::resource('users', UserController::class);
    
    // Content management
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('comments', CommentController::class);
});

// Authentication routes
require __DIR__.'/auth.php';
