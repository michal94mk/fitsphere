<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the reset password page can be rendered
     */
    public function test_reset_password_link_screen_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
    }

    /**
     * Test that the reset password page contains expected elements
     */
    public function test_reset_password_page_has_expected_elements(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertSee('email', false);
        $response->assertSee('password', false);
    }
}
