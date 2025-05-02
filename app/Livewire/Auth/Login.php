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
        'email'    => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        // Try to login as a regular user
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return $this->redirect(route('home'), navigate: true);
        }

        // If login as a user failed, check if it's a trainer
        if (Auth::guard('trainer')->attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
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
