<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

class TrainerProfile extends Component
{
    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array('trainer', explode(',', $user->role))) {
            return redirect()->route('login');
        }
    }

    #[Layout('layouts.trainer')]
    public function render()
    {
        return view('livewire.trainer.profile.trainer-profile');
    }
} 