<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Livewire\Attributes\Layout;


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

        $trainer = Auth::user();

        if (!$trainer || !in_array('trainer', explode(',', $trainer->role))) {
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

        $trainer = Auth::user();

        if (!$trainer || !in_array('trainer', explode(',', $trainer->role))) {
            $this->errorMessage = 'You are not logged in as a trainer.';
            return;
        }

        $trainerId = $trainer->id;

        if ($trainer->image && $trainer->image !== 'images/trainers/default-avatar.png') {
            Storage::disk('public')->delete($trainer->image);
        }

        try {
            // Delete associated data first
            $trainer->reservations()->delete();
            $trainer->trainerReservations()->delete();
            $trainer->posts()->delete();
            $trainer->comments()->delete();
            $trainer->nutritionalProfile()->delete();
            $trainer->mealPlans()->delete();
            
            // Delete the trainer
            $trainer->delete();

            Auth::logout();
            
            return redirect()->route('home')->with('success', 'Your account has been deleted.');
        } catch (\Exception $e) {
            Log::error("Error while deleting trainer: " . $e->getMessage());
            $this->errorMessage = 'An error occurred while deleting your account: ' . $e->getMessage();
            return;
        }
    }

    #[Layout('layouts.trainer')]
    public function render()
    {
        return view('livewire.trainer.profile.delete-trainer-account');
    }
} 