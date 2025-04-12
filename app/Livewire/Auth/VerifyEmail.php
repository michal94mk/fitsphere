<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifyEmail extends Component
{
    public $resent = false;

    public function resendVerificationLink()
    {
        /** @var \Illuminate\Contracts\Auth\MustVerifyEmail|null $user */
        $user = Auth::user();
        
        if ($user && $user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            $this->resent = true;
            session()->flash('status', 'Link weryfikacyjny został wysłany ponownie!');
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.auth.verify-email');
    }
}
