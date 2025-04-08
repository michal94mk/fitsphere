<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VerifyEmail extends Component
{
    public $resent = false;

    public function resendVerificationLink()
    {
        $user = Auth::user();
        if ($user && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $this->resent = true;
            session()->flash('status', 'Link weryfikacyjny został wysłany ponownie!');
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email')->layout('layouts.blog');
    }
}
