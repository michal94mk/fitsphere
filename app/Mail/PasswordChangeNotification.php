<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email powiadomienia o zmianie hasła
 */
class PasswordChangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hasło zostało zmienione - FitSphere',
            replyTo: [
                config('mail.from.address'),
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-changed',
            with: [
                'user' => $this->user,
                'appUrl' => config('app.url'),
                'changeTime' => now()->format('d.m.Y H:i:s'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
} 