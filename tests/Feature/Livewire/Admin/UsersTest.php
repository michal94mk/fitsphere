<?php

namespace Tests\Feature\Livewire\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Create an admin user for testing.
     *
     * @return User
     */
    private function createAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Test the users index Livewire component.
     */
    public function test_users_index_component_can_render()
    {
        $admin = $this->createAdminUser();
        
        // Create additional users
        User::factory()->count(5)->create();
        
        Livewire::actingAs($admin)
            ->test('admin.users-index')
            ->assertViewIs('livewire.admin.users-index');
    }

    /**
     * Test users search functionality.
     */
    public function test_users_search()
    {
        $admin = $this->createAdminUser();
        
        // Create specific users for testing
        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        
        $user2 = User::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.users-index')
            ->set('search', 'John')
            ->assertSee('John Doe')
            ->assertDontSee('Jane Smith');
    }

    /**
     * Test users role filter.
     */
    public function test_users_role_filter()
    {
        $admin = $this->createAdminUser();
        
        // Create users with different roles
        $adminUser = User::factory()->create([
            'name' => 'Another Admin',
            'role' => 'admin',
        ]);
        
        $regularUser = User::factory()->create([
            'name' => 'Regular User',
            'role' => 'user',
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.users-index')
            ->set('role', 'admin')
            ->assertSee('Another Admin')
            ->assertDontSee('Regular User');
    }

    /**
     * Test user validation during creation.
     */
    public function test_create_user_validation()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.users-create')
            ->set('name', '')
            ->set('email', 'not-an-email')
            ->set('password', 'short')
            ->set('password_confirmation', 'different')
            ->call('store')
            ->assertHasErrors([
                'name' => 'required',
                'email' => 'email',
                'password' => 'min',
                'password' => 'confirmed',
            ]);
    }

    /**
     * Test duplicate email validation.
     */
    public function test_duplicate_email_validation()
    {
        $admin = $this->createAdminUser();
        $existingUser = User::factory()->create();
        
        Livewire::actingAs($admin)
            ->test('admin.users-create')
            ->set('name', 'New User')
            ->set('email', $existingUser->email)
            ->set('password', 'Password123!')
            ->set('password_confirmation', 'Password123!')
            ->set('role', 'user')
            ->call('store')
            ->assertHasErrors(['email' => 'unique']);
    }

    /**
     * Test basic user database operations.
     */
    public function test_basic_user_operations()
    {
        $admin = $this->createAdminUser();
        
        // Create a basic user
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail;
        
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        
        // Verify user exists
        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
        
        // Update the user
        $newName = 'Updated Name';
        $user->update(['name' => $newName]);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newName,
        ]);
        
        // Delete the user
        $user->delete();
        
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /**
     * Test viewing the user edit page.
     */
    public function test_user_edit_page_can_render()
    {
        $admin = $this->createAdminUser();
        $user = User::factory()->create();
        
        Livewire::actingAs($admin)
            ->test('admin.users-edit', ['id' => $user->id])
            ->assertStatus(200);
    }

    /**
     * Test user edit functionality.
     */
    public function test_edit_user()
    {
        $admin = $this->createAdminUser();
        
        // Create a user to edit
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'role' => 'user',
        ]);
        
        $newName = 'Updated Name';
        $newEmail = 'updated@example.com';
        $newRole = 'admin';
        
        Livewire::actingAs($admin)
            ->test('admin.users-edit', ['id' => $user->id])
            ->assertSet('name', 'Original Name')
            ->set('name', $newName)
            ->set('email', $newEmail)
            ->set('role', $newRole)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.users.index'));
        
        // Check that user was updated in database
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newName,
            'email' => $newEmail,
            'role' => $newRole,
        ]);
    }

    /**
     * Test updating a user's password.
     */
    public function test_update_user_password()
    {
        $admin = $this->createAdminUser();
        
        $user = User::factory()->create();
        $newPassword = 'NewPassword123!';
        
        Livewire::actingAs($admin)
            ->test('admin.users-edit', ['id' => $user->id])
            ->set('changePassword', true)
            ->set('password', $newPassword)
            ->set('password_confirmation', $newPassword)
            ->call('save')
            ->assertHasNoErrors();
        
        // Get the updated user from the database
        $updatedUser = User::find($user->id);
        
        // Verify password was updated
        $this->assertTrue(Hash::check($newPassword, $updatedUser->password));
    }

    /**
     * Test viewing user information in edit form.
     */
    public function test_view_user_details()
    {
        $admin = $this->createAdminUser();
        
        $userName = 'Test User Name';
        $userEmail = 'testuser@example.com';
        
        $user = User::factory()->create([
            'name' => $userName,
            'email' => $userEmail,
            'role' => 'user',
        ]);
        
        // Test that we can access the user edit page
        $response = $this->actingAs($admin)->get(route('admin.users.edit', $user->id));
        $response->assertStatus(200);
        $response->assertSee($userName);
        $response->assertSee($userEmail);
    }

    /**
     * Test user deletion functionality.
     */
    public function test_delete_user()
    {
        $admin = $this->createAdminUser();
        
        // Create a user to delete
        $user = User::factory()->create();
        
        Livewire::actingAs($admin)
            ->test('admin.users-index')
            ->call('confirmUserDeletion', $user->id)
            ->assertSet('confirmingUserDeletion', true)
            ->assertSet('userIdBeingDeleted', $user->id)
            ->call('deleteUser');
        
        // Verify user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
} 