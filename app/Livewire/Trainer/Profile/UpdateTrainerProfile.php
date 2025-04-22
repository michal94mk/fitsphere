<?php

namespace App\Livewire\Trainer\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class UpdateTrainerProfile extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $specialization;
    public $description;
    public $bio;
    public $newImage;
    public $image;

    public function mount()
    {
        // Sprawdzamy, czy zalogowany jest trener
        if (!Auth::guard('trainer')->check()) {
            return redirect()->route('login');
        }
        
        $this->user = Auth::guard('trainer')->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->specialization = $this->user->specialization;
        $this->description = $this->user->description;
        $this->bio = $this->user->bio;
        $this->image = $this->user->image;
    }

    public function updateProfile()
    {
        // Validation for trainer
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('trainers')->ignore($this->user->id),
            ],
            'specialization' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'newImage' => ['nullable', 'image', 'max:1024'], // 1MB max size
        ]);
        
        // Check if anything changed
        $emailChanged = $this->user->email !== $this->email;
        $profileChanged = 
            $this->user->name !== $this->name ||
            $this->user->specialization !== $this->specialization ||
            $this->user->description !== $this->description ||
            $this->user->bio !== $this->bio ||
            $this->newImage;
            
        // If nothing changed, show a message and return
        if (!$emailChanged && !$profileChanged) {
            session()->flash('info_button', __('profile.no_changes'));
            return;
        }
        
        // Aktualizacja trenera
        $trainer = $this->user;
        
        $trainer->name = $this->name;
        $trainer->email = $this->email;
        $trainer->specialization = $this->specialization;
        $trainer->description = $this->description;
        $trainer->bio = $this->bio;
        
        // Upload image if provided
        if ($this->newImage) {
            // Delete old image if exists
            if ($trainer->image && file_exists(storage_path('app/public/' . $trainer->image))) {
                unlink(storage_path('app/public/' . $trainer->image));
            }
            
            // Store the new image
            $imagePath = $this->newImage->store('trainers', 'public');
            $trainer->image = $imagePath;
            $this->image = $imagePath;
        }
        
        if ($emailChanged) {
            $trainer->email_verified_at = null;
        }
        
        $trainer->save();

        session()->flash('status', __('profile.profile_updated'));
    }

    /**
     * Ponownie wysyła email z linkiem weryfikacyjnym.
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

        // Wysyłanie emaila weryfikacyjnego
        $this->user->sendEmailVerificationNotification();
        
        session()->flash('status', __('profile.verification_sent', ['email' => $this->user->email]));
    }

    public function render()
    {
        return view('livewire.trainer.profile.update-trainer-profile');
    }
} 