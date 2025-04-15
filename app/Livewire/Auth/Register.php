<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
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

            // Zamiast wywoływać event, bezpośrednio wywołujemy metodę wysyłającą email
            // event(new Registered($user));
            if (method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
            }
            
            // Nie logujemy użytkownika automatycznie, ponieważ najpierw musi zweryfikować email
            // Auth::login($user);
            
            // Zapisz informację, że rejestracja zakończyła się pomyślnie
            session()->flash('registration_success', 'Udało się zarejestrować! Proszę potwierdzić swój adres e-mail.');
            session()->flash('user_type', 'user');
            session()->flash('email', $this->email);
            
            // Użycie bezpośredniego URLa zamiast nazwanej trasy
            return Redirect::to('/registration-success/user');
        } else {
            // Register as trainer
            $trainer = Trainer::create([
                'name'           => $this->name,
                'email'          => $this->email,
                'password'       => Hash::make($this->password),
                'specialization' => $this->specialization,
                'is_approved'    => false,
            ]);

            // Nie logujemy trenera automatycznie, ponieważ najpierw musi zweryfikować email
            // Auth::guard('trainer')->login($trainer);
            
            // Zamiast wywoływać event, bezpośrednio wywołujemy metodę wysyłającą email
            // event(new Registered($trainer));
            if (method_exists($trainer, 'sendEmailVerificationNotification')) {
                $trainer->sendEmailVerificationNotification();
            }

            session()->flash('registration_success', 'Udało się zarejestrować jako trener! Proszę potwierdzić swój adres e-mail. Konto będzie wymagało zatwierdzenia przez administratora.');
            session()->flash('user_type', 'trainer');
            session()->flash('email', $this->email);
            
            // Użycie bezpośredniego URLa zamiast nazwanej trasy
            return Redirect::to('/registration-success/trainer');
        }
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
