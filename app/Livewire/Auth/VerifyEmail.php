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
        // Check which guard is active
        if (Auth::check()) {
            $this->user = Auth::user();
            $this->isTrainer = false;
        } elseif (Auth::guard('trainer')->check()) {
            $this->user = Auth::guard('trainer')->user();
            $this->isTrainer = true;
        } else {
            // If no user is logged in, redirect to login page
            return redirect()->route('login');
        }
    }

    public function resendVerificationLink()
    {
        if (!$this->user) {
            session()->flash('error', 'User is not logged in.');
            return;
        }
        
        if (!($this->user instanceof MustVerifyEmail)) {
            session()->flash('error', 'This account type does not require email verification.');
            return;
        }

        if ($this->user->hasVerifiedEmail()) {
            session()->flash('status', 'Your email address has already been verified.');
            return;
        }

        $this->user->sendEmailVerificationNotification();
        $this->resent = true;
        session()->flash('status', 'Verification link has been resent to: ' . $this->user->email);
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
