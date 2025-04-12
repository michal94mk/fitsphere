<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;

class ForgotPassword extends Component
{
    public $email;
    
    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status == Password::RESET_LINK_SENT) {
            session()->flash('message', 'Link resetujący hasło został wysłany!');
            $this->reset('email');
        } else {
            $this->addError('email', 'Nie znaleziono użytkownika o podanym adresie email.');
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
