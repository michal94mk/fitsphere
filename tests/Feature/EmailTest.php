<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use App\Mail\NewsletterSubscriptionConfirmation;
use App\Mail\TrainerApproved;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Livewire\Livewire;

class EmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_email()
    {
        Mail::fake();

        /** @var User&Authenticatable $user */
        $user = User::factory()->create();

        // Test Livewire component directly
        Livewire::actingAs($user)
            ->test(\App\Livewire\ContactPage::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('message', 'This is a test message.')
            ->call('send');

        Mail::assertQueued(ContactFormMail::class, function ($mail) {
            return $mail->senderName === 'Test User';
        });
    }

    public function test_newsletter_subscription_sends_confirmation_email()
    {
        Mail::fake();

        // Test Livewire component directly
        Livewire::test(\App\Livewire\Footer::class)
            ->set('email', 'subscriber@example.com')
            ->call('subscribe');

        $this->assertDatabaseHas('subscribers', [
            'email' => 'subscriber@example.com',
        ]);

        Mail::assertQueued(NewsletterSubscriptionConfirmation::class, function ($mail) {
            return $mail->subscriberEmail === 'subscriber@example.com';
        });
    }

    public function test_trainer_approval_sends_email()
    {
        Mail::fake();

        /** @var User&Authenticatable $admin */
        $admin = User::factory()->create(['role' => 'admin']);
        
        $trainer = Trainer::factory()->create(['is_approved' => false]);

        // Test Livewire component directly
        Livewire::actingAs($admin)
            ->test(\App\Livewire\Admin\TrainersShow::class, ['id' => $trainer->id])
            ->call('approveTrainer');

        $this->assertDatabaseHas('trainers', [
            'id' => $trainer->id,
            'is_approved' => true,
        ]);

        Mail::assertQueued(TrainerApproved::class, function ($mail) use ($trainer) {
            return $mail->trainer->id === $trainer->id;
        });
    }

    public function test_user_registration_sends_verification_email()
    {
        // Use Notification::fake() instead of Mail::fake()
        Notification::fake();
        
        // Test user registration
        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set('name', 'New User')
            ->set('email', 'newuser@example.com')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register');

        // Check if user was created
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
        
        // Get user from database
        $user = User::where('email', 'newuser@example.com')->first();
        
        // Check if VerifyEmail notification was sent to the user
        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );
    }
} 