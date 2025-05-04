<?php

namespace Tests\Unit;

use App\Models\Subscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscriber_can_be_created()
    {
        $subscriber = Subscriber::create([
            'email' => 'test@example.com',
        ]);

        $this->assertDatabaseHas('subscribers', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_subscriber_email_must_be_unique()
    {
        // Create first subscriber
        Subscriber::create([
            'email' => 'test@example.com',
        ]);
        
        // Try to create another subscriber with the same email
        // This should throw an exception due to unique constraint
        $this->expectException(\Illuminate\Database\QueryException::class);
        
        Subscriber::create([
            'email' => 'test@example.com',
        ]);
    }
} 