<?php

namespace App\Services;

use App\Mail\EmailVerification;
use App\Mail\PasswordResetEmail;
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
            Mail::to($user->email)->send(new \App\Mail\PasswordChangeNotification($user));
            
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
            // Wysyła testowy email do administratora
            $adminEmail = config('mail.from.address');
            
            Mail::raw(
                'Test email z FitSphere przez Brevo SMTP! 📧' . PHP_EOL . 
                'Konfiguracja działa poprawnie.' . PHP_EOL .
                'Data: ' . now()->format('Y-m-d H:i:s'),
                function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                            ->subject('✅ Test Brevo SMTP - FitSphere');
                }
            );
            
            Log::info('Brevo test email sent successfully', [
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