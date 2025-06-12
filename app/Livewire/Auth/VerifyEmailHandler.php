<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Auth\Events\Verified;
use App\Models\User;

class VerifyEmailHandler extends Component
{
    public $id;
    public $hash;
    public $message;
    public $error = false;

    public function mount($id, $hash)
    {
        $this->id = $id;
        $this->hash = $hash;
        
        try {
            // Find the user by ID
            $user = User::find($id);
            
            // If user not found, return error
            if (!$user) {
                throw new \Exception('User not found with the provided ID.');
            }
            
            // Check if the hash matches
            if (!hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
                throw new \Exception('Invalid verification hash for the provided user.');
            }
            
            // Check if email is already verified
            if ($user->hasVerifiedEmail()) {
                $this->message = 'Your email address has already been verified!';
                return redirect('/login')->with('verified', $this->message);
            }

            // Mark email as verified and save changes
            $user->markEmailAsVerified();
            
            // Trigger Verified event
            event(new Verified($user));
            
            // Success message and redirect
            if ($user->isTrainer()) {
                $this->message = __('common.trainer_email_verified_success');
            } else {
                $this->message = __('common.email_verified_success');
            }
            
            // Redirect to home with success message
            return redirect('/home')->with('success', $this->message);
            
        } catch (\Exception $e) {
            // In case of error, show message and redirect to login
            $this->error = true;
            $this->message = 'An error occurred during verification: ' . $e->getMessage();
            return redirect('/login')->with('error', $this->message);
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email-handler');
    }
}
