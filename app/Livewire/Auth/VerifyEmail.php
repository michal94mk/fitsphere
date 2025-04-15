<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmail extends Component
{
    public $resent = false;
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
            // Jeśli nie ma zalogowanego użytkownika, przekieruj na stronę logowania
            return redirect()->route('login');
        }
    }

    public function resendVerificationLink()
    {
        if (!$this->user) {
            session()->flash('error', 'Użytkownik nie jest zalogowany.');
            return;
        }
        
        if (!($this->user instanceof MustVerifyEmail)) {
            session()->flash('error', 'Ten typ konta nie wymaga weryfikacji email.');
            return;
        }

        if ($this->user->hasVerifiedEmail()) {
            session()->flash('status', 'Twój adres email został już zweryfikowany.');
            return;
        }

        $this->user->sendEmailVerificationNotification();
        $this->resent = true;
        session()->flash('status', 'Link weryfikacyjny został wysłany ponownie na adres: ' . $this->user->email);
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
