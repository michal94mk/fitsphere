<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;


class DeleteUserForm extends Component
{

    public $confirmingUserDeletion = false;
    public $password = '';

    protected $rules = [
        'password' => 'required',
    ];

    public function confirmUserDeletion()
    {
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        $this->validate();

        $user = Auth::user();

        // Verify password before proceeding with deletion
        if (!Hash::check($this->password, $user->password)) {
            $this->addError('password', 'Podane hasło jest nieprawidłowe.');
            return;
        }

        Auth::logout();
        $user->delete();

        session()->flash('status', 'Konto zostało usunięte.');
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.profile.delete-user-form');
    }
}
