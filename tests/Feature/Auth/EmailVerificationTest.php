<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test that the email verification route exists and is accessible.
     */
    public function test_email_verification_route_exists(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
    }

    /**
     * Test that an unverified user sees the verification notice.
     */
    public function test_unverified_user_sees_verification_notice(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        // Sprawdzamy, czy strona zawiera informacje o weryfikacji adresu e-mail
        $response->assertSee('adres e-mail', false);
    }
}
