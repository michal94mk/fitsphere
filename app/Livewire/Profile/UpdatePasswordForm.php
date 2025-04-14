<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;
    public $user = null;
    public $isTrainer = false;

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

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify the current password before allowing changes
        if (!Hash::check($this->current_password, $this->user->password)) {
            $this->addError('current_password', 'Obecne hasło jest nieprawidłowe.');
            return;
        }
        
        // Sprawdź czy nowe hasło jest takie samo jak aktualne
        if (Hash::check($this->password, $this->user->password)) {
            $this->addError('password', 'Nowe hasło musi być inne niż aktualne.');
            session()->flash('info', 'Nowe hasło jest takie samo jak aktualne. Hasło nie zostało zmienione.');
            return;
        }

        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('status', 'Hasło zostało zmienione.');
    }

    public function render()
    {
        return view('livewire.profile.update-password-form');
    }
}
