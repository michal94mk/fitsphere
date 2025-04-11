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
use App\Livewire\PostsPage;
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
use App\Livewire\TrainerProfilePage;
use App\Livewire\TrainersList;
use App\Livewire\TrainerDetails;

use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PostsIndex;
use App\Livewire\Admin\PostsCreate;
use App\Livewire\Admin\PostsEdit;
use App\Livewire\Admin\CategoriesIndex;
use App\Livewire\Admin\CategoriesCreate;
use App\Livewire\Admin\CategoriesEdit;
use App\Livewire\Admin\CategoriesShow;
use App\Livewire\Admin\CommentsIndex;
use App\Livewire\Admin\UsersIndex;
use App\Livewire\Admin\UsersCreate;
use App\Livewire\Admin\UsersEdit;
use App\Livewire\Admin\UsersShow;
use App\Livewire\Admin\UsersDashboard;
use App\Livewire\Admin\TrainersIndex;
use App\Livewire\Admin\TrainersCreate;
use App\Livewire\Admin\TrainersEdit;
use App\Livewire\Admin\TrainersShow;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Post;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

// =============================
// Public Routes - Available to all users
// =============================

// Root route redirects to home
Route::get('/', function () {
    return redirect()->route('home');
});

// Main public pages
Route::get('/home', HomePage::class)->name('home');
Route::get('/posts', PostsPage::class)->name('posts.list');
Route::get('/post/{postId}', PostDetails::class)->name('post.show');
Route::get('/about', TrainersList::class)->name('trainers.list');
Route::get('/trainer/{trainerId}', TrainerDetails::class)->name('trainer.show');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/terms', TermsPage::class)->name('terms');
Route::get('/search', SearchResultsPage::class)->name('search');

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
Route::get('/profile', Profile::class)->name('profile');
Route::get('/profile/password', UpdatePassword::class)->name('profile.password');

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
    // Livewire Admin Dashboard
    Route::get('/', AdminDashboard::class)->name('dashboard');
    
    // Posts management
    Route::get('/posts', PostsIndex::class)->name('posts.index');
    Route::get('/posts/create', PostsCreate::class)->name('posts.create');
    Route::get('/posts/{id}/edit', PostsEdit::class)->name('posts.edit');
    
    // Categories management
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');
    Route::get('/categories/create', CategoriesCreate::class)->name('categories.create');
    Route::get('/categories/{id}/edit', CategoriesEdit::class)->name('categories.edit');
    Route::get('/categories/{id}', CategoriesShow::class)->name('categories.show');
    
    // Comments management
    Route::get('/comments', CommentsIndex::class)->name('comments.index');
    
    // Users management
    Route::get('/users/dashboard', UsersDashboard::class)->name('users.dashboard');
    Route::get('/users', UsersIndex::class)->name('users.index');
    Route::get('/users/create', UsersCreate::class)->name('users.create');
    Route::get('/users/{id}/edit', UsersEdit::class)->name('users.edit');
    Route::get('/users/{id}', UsersShow::class)->name('users.show');
    
    // Trainers management
    Route::get('/trainers', TrainersIndex::class)->name('trainers.index');
    Route::get('/trainers/create', TrainersCreate::class)->name('trainers.create');
    Route::get('/trainers/{id}/edit', TrainersEdit::class)->name('trainers.edit');
    Route::get('/trainers/{id}', TrainersShow::class)->name('trainers.show');
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