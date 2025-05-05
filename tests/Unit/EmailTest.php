<?php

namespace Tests\Unit;

use App\Mail\ContactFormMail;
use App\Mail\SubscriptionConfirmation;
use App\Mail\TrainerApproved;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_mail_can_be_created()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.'
        ];

        $mail = new ContactFormMail($data);

        $this->assertEquals($data, $mail->contactData);
        $this->assertEquals('New Contact Form Submission - Test User', $mail->envelope()->subject);
        $this->assertEquals('emails.contact', $mail->content()->view);
    }

    public function test_subscription_confirmation_mail_can_be_created()
    {
        $mail = new SubscriptionConfirmation();

        $this->assertEquals('Subscription Confirmation', $mail->envelope()->subject);
        $this->assertEquals('emails.subscription-confirmation', $mail->content()->view);
    }

    public function test_trainer_approved_mail_can_be_created()
    {
        $trainer = Trainer::factory()->create();

        $mail = new TrainerApproved($trainer);

        $this->assertEquals($trainer->id, $mail->trainer->id);
        $this->assertEquals('Your trainer account has been approved', $mail->envelope()->subject);
        $this->assertEquals('emails.trainer-approved', $mail->content()->view);
    }
} 