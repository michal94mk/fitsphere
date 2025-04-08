<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name'                  => 'required|string|min:3|max:255',
        'email'                 => 'required|email|unique:users,email',
        'password'              => 'required|min:6|confirmed',
    ];

// app/Livewire/Auth/Register.php

public function register()
{
    $this->validate();

    $user = User::create([
        'name'     => $this->name,
        'email'    => $this->email,
        'password' => Hash::make($this->password),
    ]);

    Auth::login($user);

    $user->sendEmailVerificationNotification();

    session()->flash('status', 'Udało się zarejestrować! Proszę potwierdzić swój adres e-mail.');

    return redirect()->route('profile');
}


    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.blog');
    }
}
