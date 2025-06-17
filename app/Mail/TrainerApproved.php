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

class TrainerApproved extends Mailable implements ShouldQueue
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
            subject: 'Zostałeś zatwierdzony jako trener - FitSphere',
            from: new Address(config('mail.from.address', '8eecba001@smtp-brevo.com'), config('mail.from.name', 'FitSphere')),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trainer-approved',
            with: [
                'trainer' => $this->trainer,
                'dashboardUrl' => config('app.url') . '/trainer/dashboard',
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
} 