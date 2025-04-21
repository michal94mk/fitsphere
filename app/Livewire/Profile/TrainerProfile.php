<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class TrainerProfile extends Component
{
    public function mount()
    {
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('login');
        }
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.profile.trainer-profile');
    }
} 