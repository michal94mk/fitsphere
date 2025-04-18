<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * Admin Mini User Profile Component
 * 
 * This component displays the current admin user profile in the sidebar
 * and provides logout functionality.
 */
class UserProfileMini extends Component
{
    /**
     * Initialize the component and verify authentication
     * 
     * Redirects to login page if the user is not authenticated.
     * 
     * @return void|\Illuminate\Http\RedirectResponse
     */
    public function mount()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }
    }
    
    /**
     * Handle user logout and session cleanup
     * 
     * This method logs out the current user, invalidates their session,
     * regenerates the CSRF token, and redirects to the login page.
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        return redirect()->route('login');
    }
    
    /**
     * Render the mini profile component with current user data
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.user-profile-mini', [
            'user' => Auth::user(),
        ]);
    }
} 