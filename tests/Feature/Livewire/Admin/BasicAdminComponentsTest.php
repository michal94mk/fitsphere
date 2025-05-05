<?php

namespace Tests\Feature\Livewire\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Livewire\Livewire;

class BasicAdminComponentsTest extends TestCase
{
    use RefreshDatabase;

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
     * Test that the dashboard component can be rendered.
     */
    public function test_dashboard_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.dashboard')
            ->assertStatus(200);
    }

    /**
     * Test that the posts-index component can be rendered.
     */
    public function test_posts_index_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.posts-index')
            ->assertStatus(200);
    }

    /**
     * Test that the posts-create component can be rendered.
     */
    public function test_posts_create_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.posts-create')
            ->assertStatus(200);
    }

    /**
     * Test that the categories-index component can be rendered.
     */
    public function test_categories_index_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.categories-index')
            ->assertStatus(200);
    }

    /**
     * Test that the categories-create component can be rendered.
     */
    public function test_categories_create_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.categories-create')
            ->assertStatus(200);
    }

    /**
     * Test that the users-index component can be rendered.
     */
    public function test_users_index_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.users-index')
            ->assertStatus(200);
    }

    /**
     * Test that the users-create component can be rendered.
     */
    public function test_users_create_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.users-create')
            ->assertStatus(200);
    }

    /**
     * Test that the trainers-index component can be rendered.
     */
    public function test_trainers_index_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.trainers-index')
            ->assertStatus(200);
    }

    /**
     * Test that the trainers-create component can be rendered.
     */
    public function test_trainers_create_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.trainers-create')
            ->assertStatus(200);
    }

    /**
     * Test that the comments-index component can be rendered.
     */
    public function test_comments_index_component_exists()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.comments-index')
            ->assertStatus(200);
    }
} 