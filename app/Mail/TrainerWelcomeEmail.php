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

class TrainerWelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public User $trainer;

    public function __construct(User $trainer)
    {
        $this->trainer = $trainer;
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Witaj w FitSphere jako Trener! 🏋️‍♂️💪',
            from: new Address(config('mail.from.address', '8eecba001@smtp-brevo.com'), config('mail.from.name', 'FitSphere')),
            replyTo: [
                config('mail.from.address'),
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trainer-welcome',
            with: [
                'trainer' => $this->trainer,
                'appUrl' => config('app.url'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
} 