<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class UserProfile extends Component
{
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.profile.user-profile');
    }
} 