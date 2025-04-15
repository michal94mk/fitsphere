<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Livewire\HomePage;
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
use App\Livewire\CreateReservation;
use App\Livewire\UserReservations;
use App\Livewire\TrainerReservations;

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
use Illuminate\Support\Facades\Auth;

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
Route::get('/trainers', TrainersList::class)->name('trainers.list');
Route::get('/trainers/{trainerId}', TrainerDetails::class)->name('trainer.show');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/terms', TermsPage::class)->name('terms');
Route::get('/search', SearchResultsPage::class)->name('search');
Route::get('/registration-success/{userType?}', \App\Livewire\Auth\RegistrationSuccess::class)->name('registration.success');

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
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('comments/{comment}/edit', [CommentController::class, 'edit'])->name('comments.edit');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    
    // Post management (excluding index and show, handled by public routes)
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    
    // Reservation system for users
    Route::get('/reservation/create/{trainerId}', CreateReservation::class)->name('reservation.create');
    Route::get('/user/reservations', UserReservations::class)->name('user.reservations');
});

// =============================
// Trainer Routes - Only accessible by authenticated trainers
// =============================
Route::middleware('auth:trainers')->prefix('trainer')->name('trainer.')->group(function () {
    Route::get('/reservations', TrainerReservations::class)->name('reservations');
    // Route::get('/profile', TrainerProfilePage::class)->name('profile');
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
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    try {
        // Próbujemy znaleźć zarówno użytkownika jak i trenera
        $userModel = null;
        $trainerModel = null;
        $isTrainer = false;
        $validatedUser = null;
        
        try {
            // Próba znalezienia użytkownika
            $userModel = User::find($id);
        } catch (\Exception $e) {
            // Ignorujemy błędy
        }
        
        try {
            // Próba znalezienia trenera
            $trainerModel = \App\Models\Trainer::find($id);
        } catch (\Exception $e) {
            // Ignorujemy błędy
        }
        
        // Jeśli nie znaleziono ani użytkownika ani trenera, zwróć błąd
        if (!$userModel && !$trainerModel) {
            throw new \Exception('Nie znaleziono użytkownika o podanym ID.');
        }
        
        // Sprawdź, który model pasuje do hasha (może być tylko jeden prawidłowy)
        if ($userModel && hash_equals(sha1($userModel->getEmailForVerification()), (string) $hash)) {
            $validatedUser = $userModel;
            $isTrainer = false;
        } elseif ($trainerModel && hash_equals(sha1($trainerModel->getEmailForVerification()), (string) $hash)) {
            $validatedUser = $trainerModel;
            $isTrainer = true;
        } else {
            throw new \Exception('Nieprawidłowy hash weryfikacyjny dla podanego użytkownika.');
        }
        
        // Sprawdź, czy email jest już zweryfikowany
        if ($validatedUser->hasVerifiedEmail()) {
            $message = 'Twój adres email został już wcześniej zweryfikowany!';
            return redirect('/login')->with('verified', $message);
        }

        // Oznacz email jako zweryfikowany i zapisz zmiany
        $validatedUser->markEmailAsVerified();
        
        // Uruchom zdarzenie Verified
        event(new Verified($validatedUser));
        
        // Komunikat o sukcesie i przekierowanie
        $message = 'Twój adres email został pomyślnie zweryfikowany!';
        
        if ($isTrainer) {
            $message .= ' Administrator wkrótce sprawdzi Twoje zgłoszenie.';
        }
        
        // Przekierowanie do logowania
        return redirect('/login')->with('verified', $message);
        
    } catch (\Exception $e) {
        // W przypadku błędu, pokazujemy komunikat i przekierowujemy do logowania
        return redirect('/login')->with('error', 'Wystąpił błąd podczas weryfikacji: ' . $e->getMessage());
    }
})->middleware(['signed'])->name('verification.verify');