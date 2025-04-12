<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

class ResetPassword extends Component
{
    public $token;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required|min:6|confirmed',
    ];

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email'                 => $this->email,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token'                 => $this->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', 'Hasło zostało zresetowane!');
            return $this->redirect(route('login'), navigate: true);
        } else {
            $this->addError('email', 'Błąd przy resetowaniu hasła.');
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
