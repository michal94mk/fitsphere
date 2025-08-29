<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\EmailService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_user_can_register()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => 'Password123!@',
            'password_confirmation' => 'Password123!@',
            'account_type' => 'regular'
        ];

        $component = Livewire::test(\App\Livewire\Auth\Register::class)
            ->set($userData)
            ->call('register');
            
        if ($component->errors()->isNotEmpty()) {
            $this->fail('Component has errors: ' . json_encode($component->errors()));
        }
            
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@test.com'
        ]);
    }

    public function test_user_cannot_register_with_invalid_data()
    {
        $userData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '123',
            'account_type' => 'regular'
        ];

        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set($userData)
            ->call('register')
            ->assertHasErrors(['name', 'email', 'password']);

        $this->assertDatabaseMissing('users', [
            'email' => 'invalid-email'
        ]);
    }

    public function test_user_cannot_register_with_existing_email()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'existing@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'account_type' => 'regular'
        ];

        Livewire::test(\App\Livewire\Auth\Register::class)
            ->set($userData)
            ->call('register')
            ->assertHasErrors(['email']);
    }

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('password123')
        ]);

        $component = Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', 'test@test.com')
            ->set('password', 'password123')
            ->call('login');
            
        if ($component->errors()->isNotEmpty()) {
            $this->fail('Component has errors: ' . json_encode($component->errors()));
        }

        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        Livewire::test(\App\Livewire\Auth\Login::class)
            ->set('email', 'test@example.com')
            ->set('password', 'wrongpassword')
            ->call('login')
            ->assertHasErrors(['email']);

        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/home');
        $this->assertGuest();
    }

    public function test_user_can_request_password_reset()
    {
        $user = User::factory()->create(['email' => 'reset@example.com']);

        Livewire::test(\App\Livewire\Auth\ForgotPassword::class)
            ->set('email', 'reset@example.com')
            ->call('sendResetLink')
            ->assertHasNoErrors();
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
            'password' => Hash::make('oldpassword')
        ]);

        $token = app('auth.password.broker')->createToken($user);

        Livewire::test(\App\Livewire\Auth\ResetPassword::class, ['token' => $token])
            ->set('email', 'reset@example.com')
            ->set('password', 'newpassword123')
            ->set('password_confirmation', 'newpassword123')
            ->call('resetPassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        Livewire::test(\App\Livewire\Auth\ResetPassword::class, ['token' => 'invalid-token'])
            ->set('email', 'reset@example.com')
            ->set('password', 'newpassword123')
            ->set('password_confirmation', 'newpassword123')
            ->call('resetPassword')
            ->assertHasErrors();
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword')
        ]);
        $this->actingAs($user);

        Livewire::test(\App\Livewire\User\Profile\UpdatePassword::class)
            ->set('current_password', 'oldpassword')
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('updatePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_user_cannot_change_password_with_wrong_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword')
        ]);
        $this->actingAs($user);

        $component = Livewire::test(\App\Livewire\User\Profile\UpdatePassword::class)
            ->set('current_password', 'wrongpassword')
            ->set('new_password', 'newpassword123')
            ->set('new_password_confirmation', 'newpassword123')
            ->call('updatePassword');
            
        // Sprawdzamy czy hasło nie zostało zmienione (powinno pozostać stare)
        $this->assertTrue(Hash::check('oldpassword', $user->fresh()->password));
    }

    public function test_user_can_request_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null
        ]);
        $this->actingAs($user);

        Livewire::test(\App\Livewire\Auth\VerifyEmail::class)
            ->call('resendVerificationLink')
            ->assertHasNoErrors();
    }

    public function test_trainer_registration_creates_trainer_role()
    {
        $trainerData = [
            'name' => 'Trainer John',
            'email' => 'trainer@test.com',
            'password' => 'Password123!@',
            'password_confirmation' => 'Password123!@',
            'account_type' => 'trainer',
            'specialization' => 'Strength Training'
        ];

        $component = Livewire::test(\App\Livewire\Auth\Register::class)
            ->set($trainerData)
            ->call('register');
            
        if ($component->errors()->isNotEmpty()) {
            $this->fail('Component has errors: ' . json_encode($component->errors()));
        }

        $this->assertDatabaseHas('users', [
            'name' => 'Trainer John',
            'email' => 'trainer@test.com',
            'role' => 'trainer',
            'specialization' => 'Strength Training'
        ]);
    }

    public function test_admin_can_approve_trainer()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => false
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\Dashboard::class)
            ->call('approveTrainer', $trainer->id)
            ->assertHasNoErrors();

        $this->assertTrue($trainer->fresh()->is_approved);
    }

    public function test_non_admin_cannot_approve_trainer()
    {
        $user = User::factory()->create(['role' => 'user']);
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => false
        ]);

        $this->actingAs($user);

        $component = Livewire::test(\App\Livewire\Admin\Dashboard::class)
            ->call('approveTrainer', $trainer->id);
            
        // Sprawdzamy czy trener nie został zatwierdzony (powinien pozostać niezatwierdzony)
        $this->assertFalse($trainer->fresh()->is_approved);
    }

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ];

        Livewire::test(\App\Livewire\User\Profile\UpdateUserProfile::class)
            ->set($updateData)
            ->call('updateProfile')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
    }

    public function test_user_cannot_update_profile_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $updateData = [
            'name' => '',
            'email' => 'invalid-email'
        ];

        Livewire::test(\App\Livewire\User\Profile\UpdateUserProfile::class)
            ->set($updateData)
            ->call('updateProfile')
            ->assertHasErrors(['name', 'email']);
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $this->actingAs($user);

        $component = Livewire::test(\App\Livewire\User\Profile\DeleteUserAccount::class)
            ->set('password', 'password')
            ->call('validatePasswordAndOpenModal')
            ->call('deleteAccount');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_account_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);
        $this->actingAs($user);

        $component = Livewire::test(\App\Livewire\User\Profile\DeleteUserAccount::class)
            ->set('password', 'wrongpassword')
            ->call('validatePasswordAndOpenModal');
            
        // Sprawdzamy czy konto nie zostało usunięte
        $this->assertDatabaseHas('users', ['id' => $user->id]);

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }
}
