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

        // Próba logowania jako zwykły użytkownik
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            return $this->redirect(route('home'), navigate: true);
        }

        // Jeśli nie udało się zalogować jako użytkownik, sprawdź czy to trener
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
