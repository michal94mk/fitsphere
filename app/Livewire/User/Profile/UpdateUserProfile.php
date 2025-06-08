<?php

namespace App\Livewire\User\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use App\Models\User;

/**
 * Manages user profile information updates
 */
class UpdateUserProfile extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $newImage;
    public $image;

    /**
     * Initialize the component with the authenticated user's data.
     * 
     * Loads the current user's profile information and redirects
     * unauthenticated users to the login page.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mount()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->user = Auth::user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->image = $this->user->image ?? null;
    }

    /**
     * Update the user's profile information.
     * 
     * Validates input data, checks for changes, and updates the profile
     * including profile image if provided. If the email is changed,
     * it will require re-verification.
     * 
     * @return void
     */
    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'newImage' => ['nullable', 'image', 'max:1024'], // 1MB max
        ]);
        
        // Check if anything changed
        $emailChanged = $this->user->email !== $this->email;
        $profileChanged = 
            $this->user->name !== $this->name ||
            $this->newImage;
            
        if (!$emailChanged && !$profileChanged) {
            session()->flash('info_button', __('profile.no_changes'));
            return;
        }
        
        $user = $this->user;
        
        $user->name = $this->name;
        $user->email = $this->email;
        
        // Handle profile image
        if ($this->newImage) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            
            $imagePath = $this->newImage->store('images/users', 'public');
            $user->image = $imagePath;
            $this->image = $imagePath;
        }
        
        // Reset verification if email changed
        if ($emailChanged) {
            $user->email_verified_at = null;
        }
        
        $user->save();
        
        // Refresh user data
        $this->user = $user->fresh();

        session()->flash('status', __('profile.profile_updated'));
    }

    /**
     * Resend the email verification link to the user.
     * 
     * Checks if verification is necessary and sends a verification
     * email if required, with appropriate feedback messages.
     * 
     * @return void
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

    /**
     * Render the profile update form.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Reload latest user data
        if (Auth::check()) {
            $this->user = User::find(Auth::id());
        }
        return view('livewire.user.profile.update-user-profile');
    }
} 