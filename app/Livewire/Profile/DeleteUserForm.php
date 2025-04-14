<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;


class DeleteUserForm extends Component
{
    public $confirmingUserDeletion = false;
    public $password = '';
    public $user = null;
    public $isTrainer = false;

    protected $rules = [
        'password' => 'required',
    ];

    public function mount()
    {
        // Sprawdzamy, który guard jest aktywny
        if (Auth::check()) {
            $this->user = Auth::user();
            $this->isTrainer = false;
        } elseif (Auth::guard('trainer')->check()) {
            $this->user = Auth::guard('trainer')->user();
            $this->isTrainer = true;
        } else {
            // Jeśli nie ma zalogowanego użytkownika
            return redirect()->route('login');
        }
    }

    public function confirmUserDeletion()
    {
        $this->confirmingUserDeletion = true;
    }

    public function deleteUser()
    {
        $this->validate();

        // Verify password before proceeding with deletion
        if (!Hash::check($this->password, $this->user->password)) {
            $this->addError('password', 'Podane hasło jest nieprawidłowe.');
            return;
        }

        // Wyloguj z odpowiedniego guarda
        if ($this->isTrainer) {
            Auth::guard('trainer')->logout();
        } else {
            Auth::logout();
        }
        
        // Usuń użytkownika
        $this->user->delete();

        session()->flash('status', 'Konto zostało usunięte.');
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.profile.delete-user-form');
    }
}
