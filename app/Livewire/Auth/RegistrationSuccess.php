<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Auth\Events\Registered;

class RegistrationSuccess extends Component
{
    public $userType;
    public $email;
    public $resent = false;
    
    public function mount($userType = null)
    {
        $this->userType = $userType ?? session('user_type', 'user');
        $this->email = session('email', '');
    }
    
    public function resendVerificationEmail()
    {
        if (empty($this->email)) {
            session()->flash('error', 'Email address is not available. Try logging in or registering again.');
            return;
        }
        
        // Find user or trainer based on email address
        if ($this->userType == 'trainer') {
            $user = Trainer::where('email', $this->email)->first();
        } else {
            $user = User::where('email', $this->email)->first();
        }
        
        if (!$user) {
            session()->flash('error', 'No account found with this email address.');
            return;
        }
        
        if ($user->hasVerifiedEmail()) {
            session()->flash('info', 'This email address has already been verified. You can log in.');
            return;
        }
        
        // Send verification email directly without triggering event
        // event(new Registered($user));
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }
        
        $this->resent = true;
        session()->flash('success', 'Verification link has been resent to: ' . $this->email);
    }
    
    #[Layout('layouts.blog', ['title' => 'Registration Completed Successfully'])]
    public function render()
    {
        return view('livewire.auth.registration-success');
    }
}
