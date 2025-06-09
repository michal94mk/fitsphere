<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class SocialLogin extends Component
{
    /**
     * Redirect to social provider
     */
    public function redirectToGoogle()
    {
        return redirect('/auth/google');
    }

    public function render()
    {
        return view('livewire.auth.social-login');
    }
}
