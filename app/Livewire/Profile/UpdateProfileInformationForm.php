<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * Profile information update component.
 * Handles the user profile editing functionality including name and email updates.
 */
class UpdateProfileInformationForm extends Component
{
    public $name;
    public $email;
    public $isTrainer = false;
    public $user = null;

    public function mount()
    {
        // Sprawdzamy, który guard jest aktywny
        if (Auth::check()) {
            $this->user = Auth::user();
            $this->isTrainer = false;
        } elseif (Auth::guard('trainer')->check()) {
            $this->user = Auth::guard('trainer')->user();
            $this->isTrainer = true;
        } else {
            // Jeśli nie ma zalogowanego użytkownika
            return redirect()->route('login');
        }
        
        // Ustawiamy pola formularza
        $this->name = $this->user->name;
        $this->email = $this->user->email;
    }

    public function updateProfile()
    {
        // Przygotowujemy reguły walidacji
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'email', 
                'max:255',
            ],
        ];

        // Dostosowanie reguł unique w zależności od typu użytkownika
        if ($this->isTrainer) {
            $rules['email'][] = Rule::unique('trainers')->ignore($this->user->id);
        } else {
            $rules['email'][] = Rule::unique('users')->ignore($this->user->id);
        }

        // Walidacja
        $data = $this->validate($rules);

        // Sprawdzamy czy dane zostały zmienione
        $nameChanged = $this->user->name !== $data['name'];
        $emailChanged = $this->user->email !== $data['email'];

        // Jeśli nic się nie zmieniło, wyświetl komunikat i zakończ
        if (!$nameChanged && !$emailChanged) {
            session()->flash('info', 'Nie wprowadzono żadnych zmian w danych profilu.');
            return;
        }

        // Aktualizacja danych
        $this->user->name = $data['name'];
        $this->user->email = $data['email'];
        
        // Jeśli email się zmienił, oznacz go jako niezweryfikowany
        if ($emailChanged) {
            $this->user->email_verified_at = null;
        }
        
        // Zapisz zmiany
        $this->user->save();

        session()->flash('status', 'Dane profilu zostały zaktualizowane.');
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information-form');
    }
}
