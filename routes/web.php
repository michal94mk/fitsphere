<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// -----------------------------------------
// LIVEWIRE COMPONENT IMPORTS
// -----------------------------------------

// Public pages
use App\Livewire\HomePage;
use App\Livewire\ContactPage;
use App\Livewire\TermsPage;
use App\Livewire\SearchResultsPage;

// Blog related
use App\Livewire\PostsPage;
use App\Livewire\PostDetails;
use App\Livewire\CommentEdit;

// Auth components
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerifyEmail;
use App\Livewire\Auth\VerifyEmailHandler;
use App\Livewire\Auth\ConfirmPassword;
use App\Livewire\Auth\RegistrationSuccess;

// User profile components
use App\Livewire\User\Profile\UserProfile;
use App\Livewire\User\Profile\UpdatePassword as UserUpdatePassword;


// Trainers & Reservations
use App\Livewire\TrainersList;
use App\Livewire\TrainerDetails;
use App\Livewire\CreateReservation;
use App\Livewire\UserReservations;
use App\Livewire\TrainerReservations;

// Nutrition features
use App\Livewire\NutritionCalculator;
use App\Livewire\MealPlanner;

// Admin components
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PostsIndex;
use App\Livewire\Admin\PostsCreate;
use App\Livewire\Admin\PostsEdit;
use App\Livewire\Admin\PostsShow;
use App\Livewire\Admin\PostTranslations;
use App\Livewire\Admin\CategoriesIndex;
use App\Livewire\Admin\CategoriesCreate;
use App\Livewire\Admin\CategoriesEdit;
use App\Livewire\Admin\CategoryTranslations;
use App\Livewire\Admin\CommentsIndex;
use App\Livewire\Admin\UsersIndex;
use App\Livewire\Admin\UsersCreate;
use App\Livewire\Admin\UsersEdit;
use App\Livewire\Admin\UsersShow;
use App\Livewire\Admin\TrainersIndex;
use App\Livewire\Admin\TrainersCreate;
use App\Livewire\Admin\TrainersEdit;
use App\Livewire\Admin\TrainersShow;
use App\Livewire\Admin\UserTranslations;

// -----------------------------------------
// PUBLIC ROUTES
// -----------------------------------------

// Root redirect
Route::get('/', function () {
    return redirect()->route('home');
});

// Language switcher
Route::post('/switch-language', function (\Illuminate\Http\Request $request) {
    $newUrl = \App\Services\LanguageService::switchLanguage($request);
    return redirect()->to($newUrl);
})->name('switch-language');

// Main public pages
Route::get('/home', HomePage::class)->name('home');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/terms', TermsPage::class)->name('terms');
Route::get('/search', SearchResultsPage::class)->name('search');
Route::get('/registration-success/{userType?}', RegistrationSuccess::class)->name('registration.success');

// Blog
Route::prefix('blog')->group(function () {
    Route::get('/', PostsPage::class)->name('posts.list');
    Route::get('/post/{postId}', PostDetails::class)->name('post.show');
});

// Trainers public pages
Route::prefix('trainers')->group(function () {
    Route::get('/', TrainersList::class)->name('trainers.list');
    Route::get('/{trainerId}', TrainerDetails::class)->name('trainer.show');
});

// Nutrition features
Route::get('/nutrition-calculator', NutritionCalculator::class)->name('nutrition.calculator');
Route::get('/meal-planner', MealPlanner::class)->name('meal-planner');

Route::get('/meal-planner-debug', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    $spoonacularKey = config('services.spoonacular.key');
    
    $diagnostics = [
        'authenticated' => $user ? true : false,
        'user_id' => $user ? $user->id : null,
        'spoonacular_key_set' => !empty($spoonacularKey) && $spoonacularKey !== 'your_spoonacular_api_key_here',
        'spoonacular_key_preview' => $spoonacularKey ? substr($spoonacularKey, 0, 10) . '...' : 'NOT SET',
        'has_nutritional_profile' => $user && $user->nutritionalProfile ? true : false,
        'target_calories' => $user && $user->nutritionalProfile ? $user->nutritionalProfile->target_calories : null,
    ];
    
    return response()->json($diagnostics);
})->name('meal-planner.debug');

// -----------------------------------------
// AUTHENTICATION ROUTES
// -----------------------------------------

// Guest only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    
    // Google OAuth
    Route::get('/auth/google', [App\Http\Controllers\Auth\SocialController::class, 'redirect']);
    Route::get('/auth/google/callback', [App\Http\Controllers\Auth\SocialController::class, 'callback']);
});

// Email verification
Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', VerifyEmailHandler::class)
    ->middleware(['signed'])
    ->name('verification.verify');
Route::get('/password/confirm', ConfirmPassword::class)->name('password.confirm');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    
    // Add logout success message
    session()->flash('success', __('common.logout_success'));
    
    return redirect('/home');
})->name('logout');

// -----------------------------------------
// USER ROUTES (AUTHENTICATED)
// -----------------------------------------

// Profile
Route::prefix('profile')->middleware(['auth'])->group(function () {
    Route::get('/', UserProfile::class)->name('profile');
    Route::get('/password', UserUpdatePassword::class)->name('profile.password');
});

// Comments - accessible by both users and trainers  
Route::get('comments/{commentId}/livewire-edit', CommentEdit::class)->name('comments.livewire-edit');

// Explicit route for user reservations (debug)
Route::get('/user/reservations', UserReservations::class)->name('user.reservations')->middleware('auth');

// User reservations - accessible by authenticated users  
Route::middleware(['auth'])->group(function () {
    Route::prefix('user/reservations')->group(function () {
        Route::get('/my', UserReservations::class)->name('user.reservations.alt');
        Route::get('/create/{trainerId}', CreateReservation::class)->name('reservation.create');
    });
});

// -----------------------------------------
// TRAINER ROUTES (Now using unified auth with role checks)
// -----------------------------------------

Route::middleware(['auth'])->group(function () {
    // Trainer-specific routes (role will be checked in components)
    Route::get('/trainer/reservations', TrainerReservations::class)->name('trainer.reservations');
    Route::get('/trainer/profile', \App\Livewire\Trainer\Profile\TrainerProfile::class)->name('trainer.profile');
    
    // Trainer reservations
    Route::prefix('trainer/reservations')->group(function () {
        Route::get('/create/{trainerId}', CreateReservation::class)->name('reservation.create.trainer');
        Route::get('/my', UserReservations::class)->name('trainer.my.reservations');
    });
});

// -----------------------------------------
// ADMIN ROUTES
// -----------------------------------------

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', AdminDashboard::class)->name('dashboard');
    
    // Posts management
    Route::prefix('posts')->group(function () {
        Route::get('/', PostsIndex::class)->name('posts.index');
        Route::get('/create', PostsCreate::class)->name('posts.create');
        Route::get('/{id}/edit', PostsEdit::class)->name('posts.edit');
        Route::get('/{id}', PostsShow::class)->name('posts.show');
        Route::get('/{id}/translations', PostTranslations::class)->name('posts.translations');
    });
    
    // Categories management
    Route::prefix('categories')->group(function () {
        Route::get('/', CategoriesIndex::class)->name('categories.index');
        Route::get('/create', CategoriesCreate::class)->name('categories.create');
        Route::get('/{id}/edit', CategoriesEdit::class)->name('categories.edit');
        Route::get('/{id}/translations', CategoryTranslations::class)->name('categories.translations');
    });
    
    // Comments management
    Route::get('/comments', CommentsIndex::class)->name('comments.index');
    
    // Users management
    Route::prefix('users')->group(function () {
        Route::get('/', UsersIndex::class)->name('users.index');
        Route::get('/create', UsersCreate::class)->name('users.create');
        Route::get('/{id}/edit', UsersEdit::class)->name('users.edit');
        Route::get('/{id}', UsersShow::class)->name('users.show');
        Route::get('/{id}/translations', UserTranslations::class)->name('users.translations');
    });
    
    // Trainers management
    Route::prefix('trainers')->group(function () {
        Route::get('/', TrainersIndex::class)->name('trainers.index');
        Route::get('/create', TrainersCreate::class)->name('trainers.create');
        Route::get('/{id}/edit', TrainersEdit::class)->name('trainers.edit');
        Route::get('/{id}', TrainersShow::class)->name('trainers.show');
        Route::get('/{id}/translations', UserTranslations::class)->name('trainers.translations');
    });
});