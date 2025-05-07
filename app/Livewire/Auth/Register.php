<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;

/**
 * Handles the user registration process for both regular users and trainers.
 */
class Register extends Component
{
    // Registration form fields
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $account_type = 'regular';
    public $specialization;
    
    /**
     * Sets form to trainer mode if specified in URL parameters
     */
    public function mount($account_type = null)
    {
        if ($account_type === 'trainer') {
            $this->account_type = 'trainer';
        }
    }

    /**
     * Validation rules change based on account type selected
     */
    protected function rules()
    {
        $rules = [
            'name'                  => 'required|string|min:3|max:50|regex:/^[\pL\s\-\']+$/u',
            'email'                 => 'required|email:rfc,dns',
            'password'              => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
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
     * Custom error messages for validation
     */
    protected function messages()
    {
        return [
            'name.required' => __('validation.user.name.required'),
            'name.regex' => __('validation.user.name.regex'),
            'email.required' => __('validation.user.email.required'),
            'email.email' => __('validation.user.email.email'),
            'email.unique' => __('validation.user.email.unique'),
            'email.dns' => __('validation.user.email.dns'),
            'password.required' => __('validation.user.password.required'),
            'password.min' => __('validation.user.password.min', ['min' => 8]),
            'password.confirmed' => __('validation.user.password.confirmed'),
            'password.regex' => __('validation.user.password.regex'),
            'specialization.required' => __('validation.user.specialization.required'),
        ];
    }

    public function register()
    {
        $this->validate();

        if ($this->account_type === 'regular') {
            $user = $this->createRegularUser();
            $this->sendVerificationEmail($user);
            $this->setUserRegistrationSuccess();
            
            return Redirect::to('/registration-success/user');
        } else {
            $trainer = $this->createTrainer();
            $this->sendVerificationEmail($trainer);
            $this->setTrainerRegistrationSuccess();
            
            return Redirect::to('/registration-success/trainer');
        }
    }
    
    /**
     * Creates a regular user account with hashed password
     */
    private function createRegularUser()
    {
        return User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
        ]);
    }
    
    /**
     * Creates a trainer account that requires admin approval
     */
    private function createTrainer()
    {
        return Trainer::create([
            'name'           => $this->name,
            'email'          => $this->email,
            'password'       => Hash::make($this->password),
            'specialization' => $this->specialization,
            'is_approved'    => false, 
        ]);
    }
    
    /**
     * Sends verification email if the model supports it
     */
    private function sendVerificationEmail($user)
    {
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }
    }
    
    /**
     * Sets success messages for regular user registration
     */
    private function setUserRegistrationSuccess()
    {
        session()->flash('registration_success', __('profile.user_registration_success'));
        session()->flash('user_type', 'user');
        session()->flash('email', $this->email);
    }
    
    /**
     * Sets success messages for trainer registration
     */
    private function setTrainerRegistrationSuccess()
    {
        session()->flash('registration_success', __('profile.trainer_registration_success'));
        session()->flash('user_type', 'trainer');
        session()->flash('email', $this->email);
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.register');
    }
}
