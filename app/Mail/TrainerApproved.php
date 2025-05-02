<?php

namespace App\Mail;

use App\Models\Trainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Email sent when a trainer account is approved
 */
class TrainerApproved extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The trainer instance
     */
    public $trainer;

    public function __construct(Trainer $trainer)
    {
        $this->trainer = $trainer;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Your trainer account has been approved',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.trainer-approved',
        );
    }

    public function attachments()
    {
        return [];
    }
}