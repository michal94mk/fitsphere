<?php

namespace App\Livewire\User\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use App\Models\User;

/**
 * Handles user profile information updates.
 * 
 * This component allows users to update their basic profile information,
 * including name, email, and profile image. It also handles email verification
 * management and provides feedback through flash messages.
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
        // Check if a regular user is logged in
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
        // Validation
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'newImage' => ['nullable', 'image', 'max:1024'], // 1MB max size
        ]);
        
        // Check if anything changed
        $emailChanged = $this->user->email !== $this->email;
        $profileChanged = 
            $this->user->name !== $this->name ||
            $this->newImage;
            
        // If nothing changed, show a message and return
        if (!$emailChanged && !$profileChanged) {
            session()->flash('info', 'Nie wprowadzono żadnych zmian w profilu.');
            return;
        }
        
        // Update user
        $user = $this->user;
        
        $user->name = $this->name;
        $user->email = $this->email;
        
        // Upload image if provided
        if ($this->newImage) {
            // Delete old image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            
            // Store the new image
            $imagePath = $this->newImage->store('users', 'public');
            $user->image = $imagePath;
            $this->image = $imagePath;
        }
        
        if ($emailChanged) {
            $user->email_verified_at = null;
        }
        
        $user->save();
        
        // Refresh the user instance to ensure image property is updated
        $this->user = $user->fresh();

        session()->flash('status', 'Profil został zaktualizowany!');
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
            session()->flash('error', 'Użytkownik nie jest zalogowany.');
            return;
        }

        if ($this->user->hasVerifiedEmail()) {
            session()->flash('info', 'Twój adres email został już zweryfikowany.');
            return;
        }

        // Send verification email
        $this->user->sendEmailVerificationNotification();
        
        session()->flash('status', 'Link weryfikacyjny został wysłany ponownie na adres: ' . $this->user->email);
    }

    /**
     * Render the profile update form.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Reload the user from the database to ensure we have the latest data
        if (Auth::check()) {
            $this->user = User::find(Auth::id());
        }
        return view('livewire.user.profile.update-user-profile');
    }
} 