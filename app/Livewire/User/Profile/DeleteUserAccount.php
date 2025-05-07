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
        if (empty($this->password)) {
            $this->errorMessage = __('profile.password_required');
            return;
        }
        
        $this->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

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
        if (!$this->passwordValidated) {
            $this->errorMessage = __('profile.verify_first');
            return;
        }

        $user = Auth::user();

        // Clean up user files
        if ($user->profile_image && $user->profile_image !== 'users/default-avatar.png') {
            Storage::disk('public')->delete($user->profile_image);
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