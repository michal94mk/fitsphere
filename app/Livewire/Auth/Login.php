<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class Login extends Component
{
    public $email;
    public $password;

    protected $rules = [
        'email'    => 'required|email:rfc,dns',
        'password' => 'required',
    ];

    protected function messages()
    {
        return [
            'email.required' => __('validation.user.email.required'),
            'email.email' => __('validation.user.email.email'),
            'password.required' => __('validation.user.password.required'),
        ];
    }

    public function login()
    {
        $this->validate();

        // Try to login as a regular user
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            
            // Add success message
            session()->flash('success', __('common.login_success'));
            
            return $this->redirect(route('home'), navigate: true);
        }

        // If login as a user failed, check if it's a trainer
        if (Auth::guard('trainer')->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            
            // Add success message for trainer
            session()->flash('success', __('common.login_success'));
            
            return $this->redirect(route('home'), navigate: true);
        }

        $this->addError('email', __('auth.failed'));
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
