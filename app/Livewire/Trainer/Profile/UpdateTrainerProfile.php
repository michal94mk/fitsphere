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
    public $phone;
    public $specialization;
    public $description;
    public $bio;
    public $specialties;
    public $experience;
    public $twitter_link;
    public $instagram_link;
    public $facebook_link;
    public $newImage;
    public $image;

    public function mount()
    {
        $user = Auth::user();
        if (!$user || !in_array('trainer', explode(',', $user->role))) {
            return redirect()->route('login');
        }

        $this->user = $user;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->specialization = $this->user->specialization;
        $this->description = $this->user->description;
        $this->bio = $this->user->bio;
        $this->specialties = $this->user->specialties;
        $this->experience = $this->user->experience;
        $this->twitter_link = $this->user->twitter_link;
        $this->instagram_link = $this->user->instagram_link;
        $this->facebook_link = $this->user->facebook_link;
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
            session()->flash('info', __('profile.no_changes'));
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

                            $imagePath = $this->newImage->store('images/trainers', 'public');
            $trainer->image = $imagePath;
            $this->image = $imagePath;
        }

        // Reset email verification if email changed
        if ($emailChanged) {
            $trainer->email_verified_at = null;
        }

        $trainer->save();

        session()->flash('success', __('profile.profile_updated'));
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
        
        session()->flash('verification_sent', __('profile.verification_sent', ['email' => $this->user->email]));
    }

    #[Layout('layouts.trainer')]
    public function render()
    {
        return view('livewire.trainer.profile.update-trainer-profile');
    }
}
