<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

/**
 * Handles the user account deletion process.
 * 
 * This component manages the secure deletion of user accounts,
 * including confirmation steps, password verification, cleanup
 * of associated resources, and session management.
 */
class DeleteUserAccount extends Component
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
     * Process account deletion after password confirmation.
     * 
     * Validates the password, cleans up associated resources like profile images,
     * logs the user out, invalidates the session, and permanently deletes the account.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function deleteAccount()
    {
        $this->validate();

        $user = Auth::user();

        if (!$user) {
            session()->flash('error', 'Nie jesteś zalogowany.');
            return;
        }

        // Password verification
        if (!password_verify($this->confirmPassword, $user->password)) {
            $this->addError('confirmPassword', 'Podane hasło jest nieprawidłowe.');
            return;
        }

        // Remove profile image from storage if it exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        // Log the user out
        Auth::logout();
        
        // Destroy the session
        session()->invalidate();
        session()->regenerateToken();

        // Delete the account
        $user->delete();

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
        return view('livewire.profile.delete-user-account');
    }
} 