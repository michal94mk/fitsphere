<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class WelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $user;
    public string $verificationUrl;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->generateVerificationUrl();
        $this->onQueue('emails');
    }

    private function generateVerificationUrl(): void
    {
        $this->verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60), // Link valid for 60 minutes
            [
                'id' => $this->user->id,
                'hash' => sha1($this->user->email),
            ]
        );
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to FitSphere! Please verify your email 🏋️‍♂️',
            from: new Address(config('mail.from.address', '8eecba001@smtp-brevo.com'), config('mail.from.name', 'FitSphere')),
            replyTo: [
                config('mail.from.address'),
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'user' => $this->user,
                'appUrl' => config('app.url'),
                'verificationUrl' => $this->verificationUrl,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
} 