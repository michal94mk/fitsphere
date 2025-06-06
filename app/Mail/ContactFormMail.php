<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email z formularza kontaktowego
 */
class ContactFormMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $senderName;
    public string $senderEmail;
    public string $messageContent;

    public function __construct(string $name, string $email, string $message)
    {
        $this->senderName = $name;
        $this->senderEmail = $email;
        $this->messageContent = $message;
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nowa wiadomość z formularza kontaktowego - FitSphere',
            from: new Address(config('mail.from.address', '8eecba001@smtp-brevo.com'), config('mail.from.name', 'FitSphere')),
            replyTo: [
                $this->senderEmail,
            ]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'contactData' => [
                    'name' => $this->senderName,
                    'email' => $this->senderEmail,
                    'message' => $this->messageContent,
                ]
            ]
        );
    }
} 