<?php

namespace Tests\Unit;

use App\Mail\ContactFormMail;
use App\Mail\SubscriptionConfirmation;
use App\Mail\TrainerApproved;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_mail_can_be_created()
    {
        $name = 'Test User';
        $email = 'test@example.com';
        $message = 'This is a test message.';

        $mail = new ContactFormMail($name, $email, $message);

        $this->assertEquals($name, $mail->senderName);
        $this->assertEquals($email, $mail->senderEmail);
        $this->assertEquals($message, $mail->messageContent);
        $this->assertEquals('Nowa wiadomość z formularza kontaktowego - FitSphere', $mail->envelope()->subject);
        $this->assertEquals('emails.contact', $mail->content()->view);
    }

    public function test_subscription_confirmation_mail_can_be_created()
    {
        $user = User::factory()->create();
        $subscriptionType = 'newsletter';

        $mail = new SubscriptionConfirmation($user, $subscriptionType);

        $this->assertEquals($user->id, $mail->user->id);
        $this->assertEquals($subscriptionType, $mail->subscriptionType);
        $this->assertEquals('Potwierdzenie subskrypcji - FitSphere', $mail->envelope()->subject);
        $this->assertEquals('emails.subscription-confirmation', $mail->content()->view);
    }

    public function test_trainer_approved_mail_can_be_created()
    {
        $trainer = Trainer::factory()->create();

        $mail = new TrainerApproved($trainer);

        $this->assertEquals($trainer->id, $mail->trainer->id);
        $this->assertEquals('Zostałeś zatwierdzony jako trener - FitSphere', $mail->envelope()->subject);
        $this->assertEquals('emails.trainer-approved', $mail->content()->view);
    }
} 