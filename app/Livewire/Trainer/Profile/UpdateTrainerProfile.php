<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

/**
 * Manages trainer profile information updates
 */
class UpdateTrainerProfile extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $specialization;
    public $bio;
    public $newImage;
    public $image;

    public function mount()
    {
        // Redirect if not logged in
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('login');
        }

        $this->user = Auth::guard('trainer')->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->specialization = $this->user->specialization;
        $this->bio = $this->user->bio;
        $this->image = $this->user->image;
    }

    /**
     * Updates the trainer profile information
     */
    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('trainers')->ignore($this->user->id),
            ],
            'specialization' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'newImage' => ['nullable', 'image', 'max:1024'], // 1MB max
        ]);

        // Check if anything changed
        $emailChanged = $this->user->email !== $this->email;
        $profileChanged = 
            $this->user->name !== $this->name ||
            $this->user->specialization !== $this->specialization ||
            $this->user->bio !== $this->bio ||
            $this->newImage;

        if (!$emailChanged && !$profileChanged) {
            session()->flash('info_button', __('profile.no_changes'));
            return;
        }

        $trainer = $this->user;

        $trainer->name = $this->name;
        $trainer->email = $this->email;
        $trainer->specialization = $this->specialization;
        $trainer->bio = $this->bio;

        // Handle profile image update
        if ($this->newImage) {
            if ($trainer->image && file_exists(storage_path('app/public/' . $trainer->image))) {
                unlink(storage_path('app/public/' . $trainer->image));
            }

            $imagePath = $this->newImage->store('trainers', 'public');
            $trainer->image = $imagePath;
            $this->image = $imagePath;
        }

        // Reset email verification if email changed
        if ($emailChanged) {
            $trainer->email_verified_at = null;
        }

        $trainer->save();

        session()->flash('status', __('profile.profile_updated'));
    }

    /**
     * Sends a new verification email to the trainer
     */
    public function resendVerificationEmail()
    {
        if (!$this->user) {
            session()->flash('error', __('profile.user_not_logged_in'));
            return;
        }

        if ($this->user->hasVerifiedEmail()) {
            session()->flash('info', __('profile.email_already_verified'));
            return;
        }

        $this->user->sendEmailVerificationNotification();

        session()->flash('status', __('profile.verification_sent', ['email' => $this->user->email]));
    }

    #[Layout('layouts.trainer')]
    public function render()
    {
        return view('livewire.trainer.profile.update-trainer-profile');
    }
}
