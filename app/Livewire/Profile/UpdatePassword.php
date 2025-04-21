<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;

/**
 * Handles password updates for both users and trainers.
 * 
 * This component provides a secure way for users and trainers to update
 * their passwords with appropriate validation and feedback. It works with
 * both regular user and trainer authentication guards.
 */
class UpdatePassword extends Component
{
    public $current_password, $new_password, $new_password_confirmation;
    public $user = null;
    public $isTrainer = false;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ];

    /**
     * Initialize the component with the authenticated user or trainer data.
     * 
     * Determines which authentication guard to use (regular user or trainer)
     * and redirects unauthenticated visitors to the login page.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mount()
    {
        // Check which guard is active
        if (Auth::check()) {
            $this->user = Auth::user();
            $this->isTrainer = false;
        } elseif (Auth::guard('trainer')->check()) {
            $this->user = Auth::guard('trainer')->user();
            $this->isTrainer = true;
        } else {
            // If no user is logged in
            return redirect()->route('login');
        }
    }

    /**
     * Process the password update after validation.
     * 
     * Verifies the current password, updates to the new password if valid,
     * and provides appropriate feedback messages.
     * 
     * @return void
     */
    public function updatePassword()
    {
        $this->validate();

        if (!$this->user) {
            session()->flash('error', 'Użytkownik nie jest zalogowany.');
            return;
        }

        if (!Hash::check($this->current_password, $this->user->password)) {
            session()->flash('error', 'Aktualne hasło jest nieprawidłowe.');
            return;
        }

        $this->user->update(['password' => Hash::make($this->new_password)]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        session()->flash('status', 'Hasło zostało zmienione.');
    }

    /**
     * Render the password update form with blog layout.
     * 
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.profile.update-password');
    }
}
