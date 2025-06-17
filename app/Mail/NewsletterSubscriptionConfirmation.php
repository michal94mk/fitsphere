<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterSubscriptionConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $subscriberEmail;
    public string $subscriberName;

    public function __construct(string $email, string $name = 'Subscriber')
    {
        $this->subscriberEmail = $email;
        $this->subscriberName = $name;
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Potwierdzenie subskrypcji newslettera - FitSphere',
            from: new Address(config('mail.from.address', '8eecba001@smtp-brevo.com'), config('mail.from.name', 'FitSphere')),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter-subscription',
        );
    }

    public function attachments(): array
    {
        return [];
    }
} 