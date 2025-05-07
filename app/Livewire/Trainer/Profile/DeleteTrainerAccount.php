<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Trainer;

class DeleteTrainerAccount extends Component
{
    public $showDeleteModal = false;
    public $password = '';
    public $errorMessage = '';
    public $passwordValidated = false;

    public function validatePasswordAndOpenModal()
    {
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
            return;
        }

        if (!password_verify($this->password, $trainer->password)) {
            $this->errorMessage = 'The provided password is incorrect.';
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

    public function deleteAccount()
    {
        if (!$this->passwordValidated) {
            $this->errorMessage = 'You must verify your password first.';
            return;
        }

        $trainer = Auth::guard('trainer')->user();

        if (!$trainer) {
            $this->errorMessage = 'You are not logged in as a trainer.';
            return;
        }

        $trainerId = $trainer->id;

        if ($trainer->profile_image && $trainer->profile_image !== 'trainers/default-avatar.png') {
            Storage::disk('public')->delete($trainer->profile_image);
        }

        try {
            Trainer::find($trainerId)->delete();
            
            Auth::guard('trainer')->logout();
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect()->route('home')->with('success', 'Your account has been deleted.');
        } catch (\Exception $e) {
            Log::error("Error while deleting trainer: " . $e->getMessage());
            $this->errorMessage = 'An error occurred while deleting your account: ' . $e->getMessage();
            return;
        }
    }

    public function render()
    {
        return view('livewire.trainer.profile.delete-trainer-account');
    }
} 