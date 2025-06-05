<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email do resetowania hasła użytkownika
 */
class PasswordResetEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public string $resetToken;
    public string $resetUrl;

    public function __construct(User $user, string $resetToken)
    {
        $this->user = $user;
        $this->resetToken = $resetToken;
        $this->resetUrl = config('app.url') . '/password/reset/' . $resetToken . '?email=' . urlencode($user->email);
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset hasła - FitSphere',
            replyTo: [
                config('mail.from.address'),
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
            with: [
                'user' => $this->user,
                'resetUrl' => $this->resetUrl,
                'resetToken' => $this->resetToken,
                'appUrl' => config('app.url'),
                'validUntil' => now()->addMinutes(60)->format('d.m.Y H:i'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
} 