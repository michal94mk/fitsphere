<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Trainer;

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
            // Try to find both user and trainer
            $userModel = null;
            $trainerModel = null;
            $isTrainer = false;
            $validatedUser = null;
            
            try {
                // Try to find a user
                $userModel = User::find($id);
            } catch (\Exception $e) {
                // Ignore errors
            }
            
            try {
                // Try to find a trainer
                $trainerModel = Trainer::find($id);
            } catch (\Exception $e) {
                // Ignore errors
            }
            
            // If neither user nor trainer found, return error
            if (!$userModel && !$trainerModel) {
                throw new \Exception('User not found with the provided ID.');
            }
            
            // Check which model matches the hash (only one can be valid)
            if ($userModel && hash_equals(sha1($userModel->getEmailForVerification()), (string) $hash)) {
                $validatedUser = $userModel;
                $isTrainer = false;
            } elseif ($trainerModel && hash_equals(sha1($trainerModel->getEmailForVerification()), (string) $hash)) {
                $validatedUser = $trainerModel;
                $isTrainer = true;
            } else {
                throw new \Exception('Invalid verification hash for the provided user.');
            }
            
            // Check if email is already verified
            if ($validatedUser->hasVerifiedEmail()) {
                $this->message = 'Your email address has already been verified!';
                return redirect('/login')->with('verified', $this->message);
            }

            // Mark email as verified and save changes
            $validatedUser->markEmailAsVerified();
            
            // Trigger Verified event
            event(new Verified($validatedUser));
            
            // Success message and redirect
            $this->message = 'Your email address has been successfully verified!';
            
            if ($isTrainer) {
                $this->message .= ' An administrator will review your application soon.';
            }
            
            // Redirect to login
            return redirect('/login')->with('verified', $this->message);
            
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
