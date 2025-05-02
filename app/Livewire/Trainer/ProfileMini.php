<?php

namespace App\Livewire\Trainer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class ProfileMini extends Component
{
    public function mount()
    {
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('login');
        }
    }
    
    #[Computed]
    public function trainer()
    {
        return Auth::guard('trainer')->user();
    }
    
    public function logout()
    {
        Auth::guard('trainer')->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
    
    public function render()
    {
        return view('livewire.trainer.profile-mini');
    }
}