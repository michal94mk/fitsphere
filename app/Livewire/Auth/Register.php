<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\Layout;

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
     * Account type (regular or trainer)
     * @var string
     */
    public $account_type = 'regular';

    /**
     * Trainer specialization (only for trainer accounts)
     * @var string
     */
    public $specialization;

    /**
     * Validation rules
     * @var array
     */
    protected function rules()
    {
        $rules = [
            'name'                  => 'required|string|min:3|max:255',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|confirmed',
            'account_type'          => 'required|in:regular,trainer',
        ];

        if ($this->account_type === 'regular') {
            $rules['email'] .= '|unique:users,email';
        } else {
            $rules['email'] .= '|unique:trainers,email';
            $rules['specialization'] = 'required|string|max:255';
        }

        return $rules;
    }

    /**
     * Process user registration.
     * Creates a new user account and sends verification email.
     */
    public function register()
    {
        $this->validate();

        if ($this->account_type === 'regular') {
            // Register as regular user
            $user = User::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
            ]);

            Auth::login($user);
            $user->sendEmailVerificationNotification();

            // Use a distinct session flag to avoid conflicts with other form notifications
            session()->flash('registration_success', 'Udało się zarejestrować! Proszę potwierdzić swój adres e-mail.');
        } else {
            // Register as trainer
            $trainer = Trainer::create([
                'name'           => $this->name,
                'email'          => $this->email,
                'password'       => Hash::make($this->password),
                'specialization' => $this->specialization,
                'is_approved'    => false,
            ]);

            // Użyj guard 'trainer' do logowania trenera
            Auth::guard('trainer')->login($trainer);
            event(new Registered($trainer));

            session()->flash('registration_success', 'Udało się zarejestrować jako trener! Proszę potwierdzić swój adres e-mail. Konto będzie wymagało zatwierdzenia przez administratora.');
        }

        return $this->redirect(route('registration.success'), navigate: true);
    }

    /**
     * Render the component.
     */
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.register');
    }
}
