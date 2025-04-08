<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePassword extends Component
{
    public $current_password, $new_password, $new_password_confirmation;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ];

    public function updatePassword()
    {
        $this->validate();

        $user = Auth::user();
        if (!Hash::check($this->current_password, $user->password)) {
            session()->flash('error', 'Aktualne hasło jest nieprawidłowe.');
            return;
        }

        $user->update(['password' => Hash::make($this->new_password)]);
        session()->flash('message', 'Hasło zostało zmienione.');
    }

    public function render()
    {
        return view('livewire.profile.update-password');
    }
}
