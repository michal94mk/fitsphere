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
use App\Livewire\SearchResultsPage;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Profile\Profile;
use App\Livewire\Profile\UpdatePassword;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Post;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// =============================
// Public Routes - Available to all users
// =============================

// Main public pages
Route::get('/home', HomePage::class)->name('home');
Route::get('/posts', PostsList::class)->name('posts');
Route::get('/post/{postId}', PostDetails::class)->name('post.show');
Route::get('/about', AboutPage::class)->name('about');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/terms', TermsPage::class)->name('terms');

// -----------------------------
// Public Authentication Routes
// -----------------------------
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', VerifyEmail::class)->name('verification.verify');
Route::get('/password/confirm', ConfirmPassword::class)->name('password.confirm');

// Profile routes
Route::get('/profile', \App\Livewire\Profile\Profile::class)->name('profile');
Route::get('/profile/password', \App\Livewire\Profile\UpdatePassword::class)->name('profile.password');

// Logout route
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/home');
})->name('logout');

// =============================
// Protected Routes - Only authenticated & verified users can access
// =============================
Route::middleware('auth', 'verified')->group(function () {
    // Comments functionality
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    
    // Post management (excluding index and show, handled by public routes)
    Route::resource('posts', PostController::class)->except(['index', 'show']);
});

// =============================
// Admin Routes - Only accessible by users with 'admin' middleware
// =============================
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


// Email verification route
Route::get('email/verify/{id}/{hash}', function ($id, $hash) {
    $user = User::findOrFail($id);

    // Ensure that the hash matches the user's email verification hash
    if (! hash_equals((string) $hash, (string) sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    // If email is already verified, redirect to profile
    if ($user->hasVerifiedEmail()) {
        return redirect()->route('profile')->with('verified', 'Your email address is already verified!');
    }

    // Mark the email as verified and trigger the Verified event
    $user->markEmailAsVerified();
    event(new Verified($user));

    return redirect()->route('profile')->with('verified', 'Your email address has been successfully verified!');
})->middleware(['signed', 'throttle:6,1'])->name('verification.verify');


Route::get('/search', SearchResultsPage::class)->name('search');