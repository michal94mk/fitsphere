<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeleteAccount extends Component
{
    public $password;

    public function deleteAccount()
    {
        $this->validate(['password' => 'required']);

        if (!Hash::check($this->password, Auth::user()->password)) {
            session()->flash('error', 'Nieprawidłowe hasło.');
            return;
        }

        Auth::user()->delete();
        Auth::logout();

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.profile.delete-account');
    }
}
