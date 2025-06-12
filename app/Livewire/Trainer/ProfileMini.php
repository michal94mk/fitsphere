<?php

namespace App\Livewire\Trainer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Computed;

class ProfileMini extends Component
{
    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array('trainer', explode(',', $user->role))) {
            return redirect()->route('login');
        }
    }
    
    public function getUserProperty()
    {
        return Auth::user();
    }
    
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('home');
    }
    
    public function render()
    {
        return view('livewire.trainer.profile-mini');
    }
}