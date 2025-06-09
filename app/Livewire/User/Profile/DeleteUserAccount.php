<?php

namespace App\Livewire\User\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

/**
 * Handles user account deletion
 */
class DeleteUserAccount extends Component
{
    public $showDeleteModal = false;
    public $password = '';
    public $errorMessage = '';
    public $passwordValidated = false;

    public function validatePasswordAndOpenModal()
    {
        $user = Auth::user();
        
        // For social login users (no password required)
        if ($user->provider) {
            $this->passwordValidated = true;
            $this->errorMessage = '';
            $this->showDeleteModal = true;
            return;
        }
        
        // For regular users (password required)
        if (empty($this->password)) {
            $this->errorMessage = __('profile.password_required');
            return;
        }
        
        $this->validate([
            'password' => 'required',
        ]);

        if (!Hash::check($this->password, $user->password)) {
            $this->errorMessage = __('profile.password_incorrect');
            return;
        }

        $this->passwordValidated = true;
        $this->errorMessage = '';
        $this->showDeleteModal = true;
    }

    public function openModal()
    {
        $this->reset(['errorMessage']);
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
    }

    /**
     * Delete user account after verification
     */
    public function deleteAccount()
    {
        $user = Auth::user();
        
        // For regular users, require password validation
        // For social login users, skip password validation
        if (!$user->provider && !$this->passwordValidated) {
            $this->errorMessage = __('profile.verify_first');
            return;
        }

        // Clean up user files
        if ($user->image && $user->image !== 'images/users/default-avatar.png') {
            Storage::disk('public')->delete($user->image);
        }

        $userId = $user->id;

        User::find($userId)->delete();

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('home')->with('success', __('profile.account_deleted'));
    }

    public function render()
    {
        return view('livewire.user.profile.delete-user-account');
    }
} 