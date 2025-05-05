<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    // Simplified test case - just checking if the profile page loads
    // The actual update functionality should be tested in Livewire component tests
    public function test_profile_page_contains_user_information(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertSee('Test User');
        $response->assertSee('test@example.com');
    }
}
