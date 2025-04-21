<?php

use App\Livewire\HomePage;
use App\Livewire\ContactPage;
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
use App\Livewire\Auth\RegistrationSuccess;
use App\Livewire\Profile\Profile;
use App\Livewire\Profile\UpdatePassword;
use App\Livewire\TrainersList;
use App\Livewire\TrainerDetails;
use App\Livewire\CreateReservation;
use App\Livewire\UserReservations;
use App\Livewire\TrainerReservations;
use App\Livewire\NutritionCalculator;
use App\Livewire\MealPlanner;
use App\Livewire\CommentEdit;

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
use App\Livewire\Admin\PostTranslations;
use App\Livewire\Admin\CategoryTranslations;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\VerifyEmailHandler;

// =============================
// Public Routes - Available to all users
// =============================

// Root route redirects to home
Route::get('/', function () {
    return redirect()->route('home');
});

// Language switching route (POST method to prevent GET livewire/update error)
Route::post('/switch-language', function (\Illuminate\Http\Request $request) {
    // Validate the locale
    $request->validate([
        'locale' => 'required|in:en,pl'
    ]);
    
    // Store the locale in session
    session()->put('locale', $request->locale);
    
    // Get previous URL to redirect back
    $previousUrl = url()->previous();
    $parsedUrl = parse_url($previousUrl);
    
    // Start building the new URL
    $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
    if (isset($parsedUrl['port'])) {
        $newUrl .= ':' . $parsedUrl['port'];
    }
    $newUrl .= $parsedUrl['path'] ?? '';
    
    // Parse the query parameters
    $queryParams = [];
    if (isset($parsedUrl['query'])) {
        parse_str($parsedUrl['query'], $queryParams);
    }
    
    // Update or add the locale parameter
    $queryParams['locale'] = $request->locale;
    
    // Build the new query string
    $newQueryString = http_build_query($queryParams);
    if (!empty($newQueryString)) {
        $newUrl .= '?' . $newQueryString;
    }
    
    // Add fragment if it exists
    if (isset($parsedUrl['fragment'])) {
        $newUrl .= '#' . $parsedUrl['fragment'];
    }
    
    // Redirect to the new URL
    return redirect()->to($newUrl);
})->name('switch-language');

// Main public pages
Route::get('/home', HomePage::class)->name('home');
Route::get('/posts', PostsPage::class)->name('posts.list');
Route::get('/post/{postId}', PostDetails::class)->name('post.show');
Route::get('/trainers', TrainersList::class)->name('trainers.list');
Route::get('/trainers/{trainerId}', TrainerDetails::class)->name('trainer.show');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/terms', TermsPage::class)->name('terms');
Route::get('/search', SearchResultsPage::class)->name('search');
Route::get('/registration-success/{userType?}', RegistrationSuccess::class)->name('registration.success');

// Nutrition features
Route::get('/nutrition-calculator', NutritionCalculator::class)->name('nutrition-calculator');
Route::get('/meal-planner', MealPlanner::class)->name('meal-planner');

// -----------------------------
// Public Authentication Routes
// -----------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', VerifyEmailHandler::class)
    ->middleware(['signed'])
    ->name('verification.verify');
Route::get('/password/confirm', ConfirmPassword::class)->name('password.confirm');

// Profile routes
Route::get('/profile', Profile::class)->name('profile');
Route::get('/profile/password', UpdatePassword::class)->name('profile.password');

// Logout route
Route::post('/logout', function () {
    // Wyloguj zarówno z guardu 'web' jak i 'trainer'
    if (Auth::guard('trainer')->check()) {
        Auth::guard('trainer')->logout();
    }
    Auth::logout();
    
    return redirect('/home');
})->name('logout');

// =============================
// Protected Routes - Only authenticated & verified users can access
// =============================
Route::middleware('auth', 'verified')->group(function () {
    // Comments functionality
    Route::get('comments/{commentId}/livewire-edit', CommentEdit::class)->name('comments.livewire-edit');
    
    // Reservation system for users
    Route::get('/reservation/create/{trainerId}', CreateReservation::class)->name('reservation.create');
    Route::get('/user/reservations', UserReservations::class)->name('user.reservations');
});

// =============================
// Trainer Routes - Only accessible by authenticated trainers
// =============================
Route::middleware('auth:trainer')->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/reservations', TrainerReservations::class)->name('reservations');
    Route::get('/profile', \App\Livewire\Profile\TrainerProfile::class)->name('profile');
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
    Route::get('/posts/{id}/translations', PostTranslations::class)->name('posts.translations');
    
    // Categories management
    Route::get('/categories', CategoriesIndex::class)->name('categories.index');
    Route::get('/categories/create', CategoriesCreate::class)->name('categories.create');
    Route::get('/categories/{id}/edit', CategoriesEdit::class)->name('categories.edit');
    Route::get('/categories/{id}/translations', CategoryTranslations::class)->name('categories.translations');
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