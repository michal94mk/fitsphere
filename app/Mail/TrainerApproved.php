<?php

namespace App\Mail;

use App\Models\Trainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainerApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The trainer instance.
     *
     * @var \App\Models\Trainer
     */
    public $trainer;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\Trainer  $trainer
     * @return void
     */
    public function __construct(Trainer $trainer)
    {
        $this->trainer = $trainer;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Twoje konto trenera zostało zatwierdzone',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.trainer-approved',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
} 