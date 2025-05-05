<?php

namespace Tests\Unit;

use App\Exceptions\EmailSendingException;
use App\Mail\ContactFormMail;
use App\Services\EmailService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class EmailServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $emailService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->emailService = new EmailService();
    }

    public function test_send_email_successfully()
    {
        Mail::fake();

        $recipient = 'test@example.com';
        $contactData = [
            'name' => 'Test User',
            'email' => $recipient,
            'subject' => 'Test Subject',
            'message' => 'This is a test message.'
        ];
        $mailable = new ContactFormMail($contactData);

        $result = $this->emailService->send($recipient, $mailable);

        Mail::assertSent(ContactFormMail::class, function ($mail) use ($recipient) {
            return $mail->hasTo($recipient);
        });

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Email sent successfully.', $result['message']);
    }

    public function test_send_email_with_custom_success_message()
    {
        Mail::fake();

        $recipient = 'test@example.com';
        $contactData = [
            'name' => 'Test User',
            'email' => $recipient,
            'subject' => 'Test Subject',
            'message' => 'This is a test message.'
        ];
        $mailable = new ContactFormMail($contactData);
        $customMessage = 'Custom success message';

        $result = $this->emailService->send($recipient, $mailable, $customMessage);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals($customMessage, $result['message']);
    }

    public function test_send_email_failure_without_exception()
    {
        Mail::shouldReceive('to')
            ->once()
            ->andThrow(new Exception('Test exception'));

        $recipient = 'test@example.com';
        $mailable = Mockery::mock(Mailable::class);

        $result = $this->emailService->send($recipient, $mailable);

        $this->assertEquals('error', $result['status']);
        $this->assertArrayHasKey('exception', $result);
        $this->assertInstanceOf(EmailSendingException::class, $result['exception']);
    }

    public function test_send_email_failure_with_exception()
    {
        Mail::shouldReceive('to')
            ->once()
            ->andThrow(new Exception('Test exception'));

        $recipient = 'test@example.com';
        $mailable = Mockery::mock(Mailable::class);

        $this->expectException(EmailSendingException::class);
        
        $this->emailService->send($recipient, $mailable, 'Success message', true);
    }
} 