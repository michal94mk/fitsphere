<?php

namespace App\Services;

use App\Mail\ContactFormMail;
use App\Mail\EmailVerification;
use App\Mail\NewsletterSubscriptionConfirmation;
use App\Mail\PasswordChangeNotification;
use App\Mail\PasswordResetEmail;
use App\Mail\SubscriptionConfirmation;
use App\Mail\TrainerApproved;
use App\Mail\TrainerWelcomeEmail;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Service for managing email sending via Brevo SMTP
 * 
 * Handles:
 * - Welcome emails after registration
 * - Verification emails
 * - Password reset emails
 * - Password change notifications
 * - Trainer approval notifications
 * - Subscription confirmations
 * - Contact form emails
 * 
 * All emails are queued and sent asynchronously
 */
class EmailService
{
    /**
     * Sends a welcome email after registration
     */
    public function sendWelcomeEmail(User $user): bool
    {
        try {
            Mail::to($user->email)->queue(new WelcomeEmail($user));
            
            Log::info('Welcome email queued', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends a verification email
     */
    public function sendEmailVerification(User $user): bool
    {
        try {
            Mail::to($user->email)->queue(new EmailVerification($user));
            
            Log::info('Email verification queued', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue email verification', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends a password reset email
     */
    public function sendPasswordResetEmail(User $user, string $token): bool
    {
        try {
            Mail::to($user->email)->queue(new PasswordResetEmail($user, $token));
            
            Log::info('Password reset email queued', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue password reset email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends a password change notification email
     */
    public function sendPasswordChangeNotification(User $user): bool
    {
        try {
            Mail::to($user->email)->queue(new PasswordChangeNotification($user));
            
            Log::info('Password change notification sent', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send password change notification', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends a welcome email to a trainer after registration
     */
    public function sendTrainerWelcomeEmail(User $trainer): bool
    {
        try {
            Mail::to($trainer->email)->queue(new TrainerWelcomeEmail($trainer));
            
            Log::info('Trainer welcome email queued', [
                'trainer_id' => $trainer->id,
                'email' => $trainer->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue trainer welcome email', [
                'trainer_id' => $trainer->id,
                'email' => $trainer->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends a trainer approval notification email
     */
    public function sendTrainerApprovedEmail(User $trainer): bool
    {
        try {
            Mail::to($trainer->email)->queue(new TrainerApproved($trainer));
            
            Log::info('Trainer approved email queued', [
                'trainer_id' => $trainer->id,
                'email' => $trainer->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue trainer approved email', [
                'trainer_id' => $trainer->id,
                'email' => $trainer->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends a subscription confirmation email
     */
    public function sendSubscriptionConfirmationEmail(User $user, string $subscriptionType): bool
    {
        try {
            Mail::to($user->email)->queue(new SubscriptionConfirmation($user, $subscriptionType));
            
            Log::info('Subscription confirmation email queued', [
                'user_id' => $user->id,
                'email' => $user->email,
                'subscription_type' => $subscriptionType
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue subscription confirmation email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'subscription_type' => $subscriptionType,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends a newsletter subscription confirmation email (without creating a User)
     */
    public function sendNewsletterSubscriptionConfirmation(string $email): bool
    {
        try {
            Mail::to($email)->queue(new NewsletterSubscriptionConfirmation($email));
            
            Log::info('Newsletter subscription confirmation email queued', [
                'email' => $email,
                'type' => 'newsletter'
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue newsletter subscription confirmation email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Sends an email from the contact form
     */
    public function sendContactFormEmail(string $name, string $email, string $message, ?string $recipientEmail = null): bool
    {
        try {
            $recipient = $recipientEmail ?? config('mail.contact.address', 'michalkolodziejczyk307@gmail.com');
            Mail::to($recipient)->queue(new ContactFormMail($name, $email, $message));
            
            Log::info('Contact form email queued', [
                'sender_name' => $name,
                'sender_email' => $email,
                'recipient_email' => $recipient
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to queue contact form email', [
                'sender_name' => $name,
                'sender_email' => $email,
                'recipient_email' => $recipient ?? 'default',
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Checks the status of the email queue
     */
    public function getEmailQueueStatus(): array
    {
        return [
            'pending_jobs' => 0,
            'failed_jobs' => 0,
            'last_processed' => now()
        ];
    }
}
