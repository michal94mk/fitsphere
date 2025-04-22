<?php

namespace App\Livewire\User\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;

/**
 * Handles password updates for users.
 * 
 * This component provides a secure way for users to update
 * their passwords with appropriate validation and feedback.
 */
class UpdatePassword extends Component
{
    public $current_password, $new_password, $new_password_confirmation;
    public $user = null;

    protected $rules = [
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ];

    /**
     * Initialize the component with the authenticated user data.
     * 
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $this->user = Auth::user();
    }

    /**
     * Customize validation messages for password fields.
     *
     * @return array
     */
    protected function messages()
    {
        return [
            'current_password.required' => __('validation.password.current_required'),
            'new_password.required' => __('validation.password.new_required'),
            'new_password.min' => __('validation.password.min', ['min' => 8]),
            'new_password.confirmed' => __('validation.password.confirmed'),
        ];
    }

    /**
     * Process the password update after validation.
     * 
     * Verifies the current password, updates to the new password if valid,
     * and provides appropriate feedback messages.
     * 
     * @return void
     */
    public function updatePassword()
    {
        $this->validate();

        if (!$this->user) {
            session()->flash('error', __('profile.user_not_logged_in'));
            return;
        }

        if (!Hash::check($this->current_password, $this->user->password)) {
            session()->flash('error', __('profile.current_password_incorrect'));
            return;
        }

        $this->user->update(['password' => Hash::make($this->new_password)]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        session()->flash('status', __('profile.password_updated'));
    }

    /**
     * Render the password update form.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.user.profile.update-password');
    }
} 