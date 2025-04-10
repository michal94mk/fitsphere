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

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        // Only update if data has changed
        if ($user->name !== $data['name'] || $user->email !== $data['email']) {
            $user->update($data);
            session()->flash('status', 'Profil został zaktualizowany.');
        } else {
            session()->flash('info', 'Nie wprowadzono żadnych zmian w profilu.');
        }
    }

    public function render()
    {
        return view('livewire.profile.update-profile-information-form');
    }
}
