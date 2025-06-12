<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;

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

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            
            // Check user's role to determine redirect
            $user = Auth::user();
            $roles = explode(',', $user->role);
            
            if (in_array('trainer', $roles)) {
                return redirect()->intended(route('home'));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        $this->addError('email', __('auth.failed'));
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
