<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

/**
 * Handles the trainer account deletion process.
 * 
 * This component manages the secure deletion of trainer accounts,
 * including confirmation steps, password verification, cleanup
 * of associated resources, and session management.
 */
class DeleteTrainerAccount extends Component
{
    public $confirmPassword = '';
    public $confirmDelete = false;

    protected $rules = [
        'confirmPassword' => 'required',
    ];

    /**
     * Set the deletion confirmation state to true.
     * 
     * @return void
     */
    public function confirmDelete()
    {
        $this->confirmDelete = true;
    }

    /**
     * Cancel the deletion process and reset form state.
     * 
     * @return void
     */
    public function cancelDelete()
    {
        $this->confirmDelete = false;
        $this->confirmPassword = '';
        $this->resetValidation();
    }

    /**
     * Process trainer account deletion after password confirmation.
     * 
     * Validates the password, cleans up associated resources like profile images,
     * logs the trainer out, invalidates the session, and permanently deletes the account.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function deleteAccount()
    {
        $this->validate();

        $trainer = Auth::guard('trainer')->user();

        if (!$trainer) {
            session()->flash('error', 'Nie jesteś zalogowany jako trener.');
            return;
        }

        // Password verification
        if (!password_verify($this->confirmPassword, $trainer->password)) {
            $this->addError('confirmPassword', 'Podane hasło jest nieprawidłowe.');
            return;
        }

        // Remove profile image from storage if it exists
        if ($trainer->image && Storage::disk('public')->exists($trainer->image)) {
            Storage::disk('public')->delete($trainer->image);
        }

        // Log the trainer out
        Auth::guard('trainer')->logout();
        
        // Destroy the session
        session()->invalidate();
        session()->regenerateToken();

        // Delete the account
        $trainer->delete();

        // Redirect to home page with success message
        return redirect()->route('home')->with('success', 'Twoje konto zostało pomyślnie usunięte.');
    }

    /**
     * Render the account deletion form with blog layout.
     * 
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.profile.delete-trainer-account');
    }
} 