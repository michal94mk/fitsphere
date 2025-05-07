<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserProfileMini extends Component
{
    public function mount()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }
    
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
    
    public function render()
    {
        return view('livewire.admin.user-profile-mini', [
            'user' => Auth::user(),
        ]);
    }
} 