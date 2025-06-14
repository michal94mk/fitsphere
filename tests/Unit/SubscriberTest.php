<?php

namespace Tests\Unit;

use App\Models\Subscriber;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscriber_has_correct_attributes()
    {
        $subscriber = Subscriber::factory()->create([
            'email' => 'test@example.com'
        ]);

        $this->assertEquals('test@example.com', $subscriber->email);
        $this->assertInstanceOf(Subscriber::class, $subscriber);
    }

    public function test_subscriber_can_be_created_with_valid_email()
    {
        $subscriber = Subscriber::create([
            'email' => 'subscriber@example.com'
        ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => 'subscriber@example.com'
        ]);
    }

    public function test_subscriber_email_is_fillable()
    {
        $subscriber = new Subscriber();
        $fillable = $subscriber->getFillable();

        $this->assertContains('email', $fillable);
    }
} 