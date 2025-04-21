<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use App\Models\User;

class Profile extends Component
{
    public $user;
    public $name;
    public $email;

    public function mount()
    {
        // Sprawdź, czy użytkownik jest zalogowany jako zwykły użytkownik
        if (Auth::check()) {
            $this->user = Auth::user();
            $this->name = $this->user->name;
            $this->email = $this->user->email;
        }
        // Jeśli zalogowany jest trener, przekieruj do dedykowanego profilu trenera
        elseif (Auth::guard('trainer')->check()) {
            return redirect()->route('trainer.profile');
        }
        // Jeśli nie jest zalogowany, przekieruj do strony logowania
        else {
            return redirect()->route('login');
        }
    }

    public function update()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
        ]);
        
        // Aktualizacja użytkownika
        $user = $this->user;
        $emailChanged = $user->email !== $this->email;
        
        $user->name = $this->name;
        $user->email = $this->email;
        
        if ($emailChanged) {
            $user->email_verified_at = null;
        }
        
        $user->save();
        
        session()->flash('status', 'Profil został zaktualizowany!');
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.profile');
    }
}
