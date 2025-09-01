<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Layout;
use Illuminate\Auth\Events\Registered;

/**
 * Handles the user registration process for both regular users and trainers.
 */
class Register extends Component
{
    // Registration form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $account_type = 'regular';
    public $specialization = '';
    
    // Real-time validation
    protected $validateAttributes = [
        'name' => 'name',
        'email' => 'email', 
        'password' => 'password',
        'specialization' => 'specialization'
    ];
    
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
     * Real-time validation on field update
     */
    public function updated($propertyName)
    {
        // Sanitize input
        $this->sanitizeInput($propertyName);
        
        // Validate only the updated field
        $this->validateOnly($propertyName);
    }
    
    /**
     * Sanitize user input to prevent XSS and clean data
     */
    private function sanitizeInput($propertyName)
    {
        switch($propertyName) {
            case 'name':
                $this->name = trim(strip_tags($this->name));
                break;
            case 'email':
                $this->email = trim(strtolower(strip_tags($this->email)));
                break;
            case 'specialization':
                $this->specialization = trim(strip_tags($this->specialization));
                break;
        }
    }

    /**
     * Improved validation rules with proper limits and security
     */
    protected function rules()
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[\pL\s\-\'\.À-ſ]+$/u', // Supports international characters
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:100',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'unique:users,email', // Unified email uniqueness check
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/', // Requires special character
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8',
                'max:128',
            ],
            'account_type' => [
                'required',
                'string',
                'in:regular,trainer',
            ],
        ];

        // Add specialization validation for trainers
        if ($this->account_type === 'trainer') {
            $rules['specialization'] = [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\pL\s\-\'\.\,\(\)\/\&]+$/u', // Professional specialization format
            ];
        }

        return $rules;
    }

    /**
     * Custom validation attributes for better error messages
     */
    protected function validationAttributes()
    {
        return [
            'name' => __('validation.attributes.full_name'),
            'email' => __('validation.attributes.email_address'),
            'password' => __('validation.attributes.password'),
            'password_confirmation' => __('validation.attributes.password_confirmation'),
            'account_type' => __('validation.attributes.account_type'),
            'specialization' => __('validation.attributes.specialization'),
        ];
    }

    /**
     * Enhanced error messages with detailed explanations
     */
    protected function messages()
    {
        return [
            // Name validation messages
            'name.required' => __('validation.user.name.required'),
            'name.min' => __('validation.user.name.min'),
            'name.max' => __('validation.user.name.max'),
            'name.regex' => __('validation.user.name.regex'),
            
            // Email validation messages
            'email.required' => __('validation.user.email.required'),
            'email.email' => __('validation.user.email.format'),
            'email.unique' => __('validation.user.email.unique'),
            'email.max' => __('validation.user.email.max'),
            'email.regex' => __('validation.user.email.format'),
            'email.not_in' => __('validation.user.email.exists_other_type'),
            
            // Password validation messages
            'password.required' => __('validation.user.password.required'),
            'password.min' => __('validation.user.password.min'),
            'password.max' => __('validation.user.password.max'),
            'password.confirmed' => __('validation.user.password.confirmed'),
            'password.regex' => __('validation.user.password.complex'),
            
            // Password confirmation
            'password_confirmation.required' => __('validation.user.password_confirmation.required'),
            'password_confirmation.min' => __('validation.user.password_confirmation.min'),
            'password_confirmation.max' => __('validation.user.password_confirmation.max'),
            
            // Account type
            'account_type.required' => __('validation.user.account_type.required'),
            'account_type.in' => __('validation.user.account_type.invalid'),
            
            // Specialization (trainers only)
            'specialization.required' => __('validation.user.specialization.required'),
            'specialization.min' => __('validation.user.specialization.min'),
            'specialization.max' => __('validation.user.specialization.max'),
            'specialization.regex' => __('validation.user.specialization.format'),
        ];
    }

    public function register()
    {
        $this->validate();

        if ($this->account_type === 'regular') {
            $user = $this->createRegularUser();
            // Wyzwól event Registered dla automatycznego wysłania email weryfikacyjnego
            event(new Registered($user));
            $this->sendWelcomeEmail($user);
            $this->setUserRegistrationSuccess();
            
            session()->flash('success', __('common.register_user_success'));
            return Redirect::to('/registration-success/user');
        } else {
            $user = $this->createTrainer();
            // Wyzwól event Registered dla automatycznego wysłania email weryfikacyjnego
            event(new Registered($user));
            $this->sendWelcomeEmailToTrainer($user);
            $this->setTrainerRegistrationSuccess();
            
            session()->flash('success', __('common.register_trainer_success'));
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
            'role'     => 'user',
        ]);
    }
    
    /**
     * Creates a trainer account that requires admin approval
     */
    private function createTrainer()
    {
        return User::create([
            'name'           => $this->name,
            'email'          => $this->email,
            'password'       => Hash::make($this->password),
            'role'           => 'trainer',
            'specialization' => $this->specialization,
            'is_approved'    => false, 
        ]);
    }
    
    // Metoda sendVerificationEmail została usunięta - email weryfikacyjny
    // jest wysyłany automatycznie przez AppServiceProvider
    
    /**
     * Wysyła email powitalny dla użytkownika przez Brevo
     */
    private function sendWelcomeEmail(User $user)
    {
        try {
            $emailService = app(EmailService::class);
            $emailService->sendWelcomeEmail($user);
        } catch (\Exception $e) {
            // Log błąd ale nie przerywaj procesu rejestracji
            Log::error('Failed to send welcome email during registration', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Wysyła email powitalny dla trenera przez Brevo
     */
    private function sendWelcomeEmailToTrainer(User $user)
    {
        try {
            $emailService = app(EmailService::class);
            $emailService->sendTrainerWelcomeEmail($user);
        } catch (\Exception $e) {
            // Log błąd ale nie przerywaj procesu rejestracji
            Log::error('Failed to send welcome email to trainer during registration', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
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
