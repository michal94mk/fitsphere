<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use App\Models\Trainer;
use App\Models\User;

class Profile extends Component
{
    public $user;
    public $isTrainer = false;
    public $name;
    public $email;

    public function mount()
    {
        // Sprawdź, czy użytkownik jest zalogowany jako zwykły użytkownik
        if (Auth::check()) {
            $this->user = Auth::user();
            $this->isTrainer = false;
            $this->name = $this->user->name;
            $this->email = $this->user->email;
        }
        // Sprawdź, czy użytkownik jest zalogowany jako trener
        elseif (Auth::guard('trainer')->check()) {
            $this->user = Auth::guard('trainer')->user();
            $this->isTrainer = true;
            $this->name = $this->user->name;
            $this->email = $this->user->email;
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
                Rule::unique('users')->ignore($this->isTrainer ? null : $this->user->id),
                $this->isTrainer ? Rule::unique('trainers')->ignore($this->user->id) : null,
            ],
        ]);

        if ($this->isTrainer) {
            // Aktualizacja trenera
            $trainer = $this->user;
            $emailChanged = $trainer->email !== $this->email;
            
            $trainer->name = $this->name;
            $trainer->email = $this->email;
            
            if ($emailChanged) {
                $trainer->email_verified_at = null;
            }
            
            $trainer->save();
        } else {
            // Aktualizacja zwykłego użytkownika
            $user = $this->user;
            $emailChanged = $user->email !== $this->email;
            
            $user->name = $this->name;
            $user->email = $this->email;
            
            if ($emailChanged) {
                $user->email_verified_at = null;
            }
            
            $user->save();
        }

        session()->flash('status', 'Profil został zaktualizowany!');
    }

    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.profile', [
            'isTrainer' => $this->isTrainer,
        ]);
    }
}
