<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * User registration component.
 * Handles the registration process for new users.
 */
class Register extends Component
{
    /**
     * User's name/login
     * @var string
     */
    public $name;
    
    /**
     * User's email
     * @var string
     */
    public $email;
    
    /**
     * User's password
     * @var string
     */
    public $password;
    
    /**
     * Password confirmation
     * @var string
     */
    public $password_confirmation;

    /**
     * Validation rules
     * @var array
     */
    protected $rules = [
        'name'                  => 'required|string|min:3|max:255',
        'email'                 => 'required|email|unique:users,email',
        'password'              => 'required|min:6|confirmed',
    ];

    /**
     * Process user registration.
     * Creates a new user account and sends verification email.
     */
    public function register()
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        // Use a distinct session flag to avoid conflicts with other form notifications
        session()->flash('registration_success', 'Udało się zarejestrować! Proszę potwierdzić swój adres e-mail.');

        return $this->redirect(route('profile'), navigate: true);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.blog');
    }
}
