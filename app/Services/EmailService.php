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
 * Serwis do zarządzania wysyłaniem emaili przez Brevo SMTP
 * 
 * Obsługuje:
 * - Emaile powitalne po rejestracji
 * - Emaile weryfikacyjne 
 * - Emaile resetowania hasła
 * - Powiadomienia o zmianie hasła
 * - Powiadomienia o zatwierdzeniu trenera
 * - Potwierdzenia subskrypcji
 * - Emaile z formularza kontaktowego
 * 
 * Wszystkie emaile są kolejkowane i wysyłane asynchronicznie
 */
class EmailService
{
    /**
     * Wysyła email powitalny po rejestracji
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
     * Wysyła email weryfikacyjny
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
     * Wysyła email resetowania hasła
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
     * Wysyła email powiadomienia o zmianie hasła
     */
    public function sendPasswordChangeNotification(User $user): bool
    {
        try {
            // Możesz stworzyć osobną klasę mailową dla tego
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
     * Wysyła email powitalny dla trenera po rejestracji
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
     * Wysyła email powiadomienia o zatwierdzeniu trenera
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
     * Wysyła email potwierdzenia subskrypcji
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
     * Wysyła email potwierdzenia subskrypcji newslettera (bez tworzenia User)
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
     * Wysyła email z formularza kontaktowego
     */
    public function sendContactFormEmail(string $name, string $email, string $message, ?string $recipientEmail = null): bool
    {
        try {
            $recipient = $recipientEmail ?? 'michalkolodziejczyk307@gmail.com';
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
     * Sprawdza status kolejki emaili
     */
    public function getEmailQueueStatus(): array
    {
        // Możesz dodać logikę sprawdzania statusu kolejki
        return [
            'pending_jobs' => 0, // Można pobrać z bazy danych
            'failed_jobs' => 0,
            'last_processed' => now()
        ];
    }

    /**
     * Testuje konfigurację mailową z Brevo
     */
    public function testEmailConfiguration(): bool
    {
        try {
            // Wysyła testowy email na prawdziwą skrzynkę
            $testEmail = 'michalkolodziejczyk307@gmail.com'; // Twoja prawdziwa skrzynka
            
            Mail::raw(
                'Test email z FitSphere przez Brevo SMTP! 📧' . PHP_EOL . 
                'Konfiguracja działa poprawnie.' . PHP_EOL .
                'Data: ' . now()->format('Y-m-d H:i:s'),
                function ($message) use ($testEmail) {
                    $message->to($testEmail)
                            ->subject('✅ Test Brevo SMTP - FitSphere');
                }
            );
            
            Log::info('Brevo test email sent successfully', [
                'recipient' => $testEmail,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host')
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Brevo email configuration test failed', [
                'error' => $e->getMessage(),
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host')
            ]);
            
            return false;
        }
    }
} 