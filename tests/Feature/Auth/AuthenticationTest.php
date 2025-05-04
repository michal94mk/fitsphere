<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->call('login');

        $this->assertAuthenticated();
    }

    public function test_users_cannot_authenticate_with_invalid_password()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        Livewire::test(Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrong-password')
            ->call('login');

        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        
        $this->actingAs($user);
        $this->assertAuthenticated();
        
        $this->post(route('logout'));
        
        $this->assertGuest();
    }

    public function test_registration_page_can_be_rendered()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $userCountBefore = User::count();

        Livewire::test(Register::class)
            ->set('name', 'Test User')
            ->set('email', 'test@example.com')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->set('account_type', 'regular')
            ->call('register');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
        
        $this->assertEquals($userCountBefore + 1, User::count());
    }
}
