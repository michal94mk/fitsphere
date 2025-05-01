<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Trainer;

/**
 * Handles the trainer account deletion process.
 * 
 * This component manages the secure deletion of trainer accounts,
 * including password verification, cleanup of associated resources,
 * and session management.
 */
class DeleteTrainerAccount extends Component
{
    public $showDeleteModal = false;
    public $password = '';
    public $errorMessage = '';
    public $debugInfo = '';
    public $passwordValidated = false;

    public function mount()
    {
        Log::info('DeleteTrainerAccount mounted');
    }

    public function validatePasswordAndOpenModal()
    {
        Log::info('validatePasswordAndOpenModal called');
        
        if (empty($this->password)) {
            $this->errorMessage = 'Password is required.';
            return;
        }
        
        $this->validate([
            'password' => 'required',
        ]);

        $trainer = Auth::guard('trainer')->user();

        if (!$trainer) {
            $this->errorMessage = 'You are not logged in as a trainer.';
            $this->debugInfo = 'No trainer';
            return;
        }

        // Password verification
        if (!password_verify($this->password, $trainer->password)) {
            $this->errorMessage = 'The provided password is incorrect.';
            $this->debugInfo = 'Incorrect password';
            return;
        }

        // Password has been verified, we can open the modal
        $this->passwordValidated = true;
        $this->errorMessage = '';
        $this->showDeleteModal = true;
        $this->debugInfo = 'Password verified, modal opened';
    }

    public function openModal()
    {
        Log::info('openModal called');
        $this->reset(['errorMessage']);
        $this->showDeleteModal = true;
        $this->debugInfo = 'Modal opened';
    }

    public function closeModal()
    {
        Log::info('closeModal called');
        $this->showDeleteModal = false;
        $this->debugInfo = 'Modal closed';
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
        Log::info('deleteAccount called');
        
        // Make sure the password has been previously verified
        if (!$this->passwordValidated) {
            $this->errorMessage = 'You must verify your password first.';
            $this->debugInfo = 'Attempt to delete account without password verification';
            return;
        }

        $trainer = Auth::guard('trainer')->user();

        if (!$trainer) {
            $this->errorMessage = 'You are not logged in as a trainer.';
            return;
        }

        // Remember trainer ID before logout
        $trainerId = $trainer->id;

        // Delete profile photo if it exists (similar to User)
        if ($trainer->profile_image && $trainer->profile_image !== 'trainers/default-avatar.png') {
            Storage::disk('public')->delete($trainer->profile_image);
        }

        // Delete account
        try {
            Trainer::find($trainerId)->delete();
            Log::info("Trainer {$trainerId} deleted");
            
            // Log out the trainer
            Auth::guard('trainer')->logout();
            
            // Destroy session
            session()->invalidate();
            session()->regenerateToken();
            
            // Redirect to home page with a message
            return redirect()->route('home')->with('status', 'Your account has been deleted.');
        } catch (\Exception $e) {
            Log::error("Error while deleting trainer: " . $e->getMessage());
            $this->errorMessage = 'An error occurred while deleting your account: ' . $e->getMessage();
            return;
        }
    }

    /**
     * Render the account deletion form with blog layout.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.trainer.profile.delete-trainer-account');
    }
} 