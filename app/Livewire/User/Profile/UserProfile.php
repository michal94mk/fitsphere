<?php

namespace App\Livewire\User\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

/**
 * Handles the main user profile page display.
 * 
 * This component serves as the container for various profile-related components
 * such as profile information management, password updates, and account deletion.
 * It ensures that only authenticated users can access the profile page.
 */
class UserProfile extends Component
{
    /**
     * Initialize the component and check authentication.
     * 
     * Redirects unauthenticated users to the login page.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }

    /**
     * Render the profile view with blog layout.
     * 
     * @return \Illuminate\View\View
     */
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.user.profile.user-profile');
    }
} 