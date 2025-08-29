<?php

namespace Tests\Unit;

use App\Services\EmailService;
use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Mail\EmailVerification;
use App\Mail\PasswordResetEmail;
use App\Mail\TrainerApproved;
use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected EmailService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EmailService();
        Mail::fake();
    }

    public function test_send_welcome_email_queues_email()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        $result = $this->service->sendWelcomeEmail($user);

        $this->assertTrue($result);
        Mail::assertQueued(WelcomeEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_send_welcome_email_handles_failure()
    {
        Mail::shouldReceive('to->queue')
            ->once()
            ->andThrow(new \Exception('Mail server error'));

        $user = User::factory()->create();

        $result = $this->service->sendWelcomeEmail($user);

        $this->assertFalse($result);
    }

    public function test_send_email_verification_queues_email()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $result = $this->service->sendEmailVerification($user);

        $this->assertTrue($result);
        Mail::assertQueued(EmailVerification::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_send_password_reset_email_queues_email()
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com'
        ]);

        $token = 'test-reset-token';

        $result = $this->service->sendPasswordResetEmail($user, $token);

        $this->assertTrue($result);
        Mail::assertQueued(PasswordResetEmail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_send_password_change_notification_queues_email()
    {
        $user = User::factory()->create([
            'email' => 'change@example.com'
        ]);

        $result = $this->service->sendPasswordChangeNotification($user);

        $this->assertTrue($result);
        Mail::assertQueued(\App\Mail\PasswordChangeNotification::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_send_trainer_welcome_email_queues_email()
    {
        $trainer = User::factory()->create([
            'name' => 'Trainer John',
            'email' => 'trainer@example.com',
            'role' => 'trainer'
        ]);

        $result = $this->service->sendTrainerWelcomeEmail($trainer);

        $this->assertTrue($result);
        Mail::assertQueued(\App\Mail\TrainerWelcomeEmail::class, function ($mail) use ($trainer) {
            return $mail->hasTo($trainer->email);
        });
    }

    public function test_send_trainer_approved_email_queues_email()
    {
        $trainer = User::factory()->create([
            'name' => 'Approved Trainer',
            'email' => 'approved@example.com',
            'role' => 'trainer',
            'is_approved' => true
        ]);

        $result = $this->service->sendTrainerApprovedEmail($trainer);

        $this->assertTrue($result);
        Mail::assertQueued(TrainerApproved::class, function ($mail) use ($trainer) {
            return $mail->hasTo($trainer->email);
        });
    }

    public function test_send_subscription_confirmation_queues_email()
    {
        $user = User::factory()->create([
            'email' => 'subscribe@example.com'
        ]);

        $subscriptionType = 'premium';

        $result = $this->service->sendSubscriptionConfirmationEmail($user, $subscriptionType);

        $this->assertTrue($result);
        Mail::assertQueued(\App\Mail\SubscriptionConfirmation::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_send_newsletter_subscription_confirmation_queues_email()
    {
        $email = 'newsletter@example.com';

        $result = $this->service->sendNewsletterSubscriptionConfirmation($email);

        $this->assertTrue($result);
        Mail::assertQueued(\App\Mail\NewsletterSubscriptionConfirmation::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    public function test_send_contact_form_email_queues_email()
    {
        $name = 'Contact User';
        $email = 'contact@example.com';
        $message = 'Test contact message';

        $result = $this->service->sendContactFormEmail($name, $email, $message);

        $this->assertTrue($result);
        Mail::assertQueued(ContactFormMail::class, function ($mail) use ($email) {
            return $mail->hasTo(config('mail.contact.address', 'michalkolodziejczyk307@gmail.com'));
        });
    }

    public function test_send_contact_form_email_with_custom_recipient()
    {
        $name = 'Contact User';
        $email = 'contact@example.com';
        $message = 'Test contact message';
        $recipient = 'custom@example.com';

        $result = $this->service->sendContactFormEmail($name, $email, $message, $recipient);

        $this->assertTrue($result);
        Mail::assertQueued(ContactFormMail::class, function ($mail) use ($recipient) {
            return $mail->hasTo($recipient);
        });
    }

    public function test_get_email_queue_status_returns_array()
    {
        $status = $this->service->getEmailQueueStatus();

        $this->assertIsArray($status);
        $this->assertArrayHasKey('pending_jobs', $status);
        $this->assertArrayHasKey('failed_jobs', $status);
        $this->assertArrayHasKey('last_processed', $status);
    }

    public function test_email_service_logs_successful_operations()
    {
        Log::shouldReceive('info')
            ->once()
            ->with('Welcome email queued', \Mockery::any());

        $user = User::factory()->create();
        $this->service->sendWelcomeEmail($user);
    }

    public function test_email_service_logs_failed_operations()
    {
        Mail::shouldReceive('to->queue')
            ->once()
            ->andThrow(new \Exception('Mail server error'));

        Log::shouldReceive('error')
            ->once()
            ->with('Failed to queue welcome email', \Mockery::any());

        $user = User::factory()->create();
        $this->service->sendWelcomeEmail($user);
    }
}
