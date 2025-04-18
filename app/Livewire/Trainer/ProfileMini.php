<?php

namespace App\Livewire\Trainer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;

class ProfileMini extends Component
{
    /**
     * Inicjalizacja komponentu
     * Sprawdzenie, czy zalogowany jest trener
     */
    public function mount()
    {
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('login');
        }
    }
    
    /**
     * Przykład użycia atrybutu #[Computed]
     * Cachowanie rezultatu metody, dopóki zależności nie ulegną zmianie
     */
    #[Computed]
    public function trainer()
    {
        return Auth::guard('trainer')->user();
    }
    
    /**
     * Obsługa wylogowania trenera
     */
    public function logout()
    {
        Auth::guard('trainer')->logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
    
    /**
     * Renderowanie widoku
     */
    public function render()
    {
        return view('livewire.trainer.profile-mini');
    }
} 