<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ConfirmPassword extends Component
{
    public $password;

    protected $rules = [
        'password' => 'required',
    ];

    public function confirm()
    {
        $this->validate();

        if (Hash::check($this->password, Auth::user()->password)) {
            session(['auth.password_confirmed_at' => time()]);
            return redirect()->intended(url()->previous());
        }

        $this->addError('password', 'Hasło jest nieprawidłowe.');
    }

    public function render()
    {
        return view('livewire.auth.confirm-password')->layout('layouts.blog');
    }
}
