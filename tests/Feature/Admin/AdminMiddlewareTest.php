<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminMiddlewareTest extends TestCase
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
     * Create a regular user for testing.
     *
     * @return User
     */
    private function createRegularUser(): User
    {
        return User::factory()->create([
            'role' => 'user',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Test that admin routes can be accessed by admin users.
     */
    public function test_admin_routes_accessible_by_admin()
    {
        $admin = $this->createAdminUser();
        $this->actingAs($admin);
        
        $routes = [
            'admin.dashboard',
            'admin.posts.index',
            'admin.categories.index',
            'admin.users.index',
            'admin.trainers.index',
            'admin.comments.index',
        ];
        
        foreach ($routes as $route) {
            $this->get(route($route))->assertStatus(200);
        }
    }

    /**
     * Test that admin routes cannot be accessed by regular users.
     */
    public function test_admin_routes_inaccessible_by_regular_users()
    {
        $user = $this->createRegularUser();
        $this->actingAs($user);
        
        $routes = [
            'admin.dashboard',
            'admin.posts.index',
            'admin.categories.index',
            'admin.users.index',
            'admin.trainers.index',
            'admin.comments.index',
        ];
        
        foreach ($routes as $route) {
            $this->get(route($route))->assertRedirect(route('home'));
        }
    }

    /**
     * Test that admin routes cannot be accessed by guests.
     */
    public function test_admin_routes_inaccessible_by_guests()
    {
        $routes = [
            'admin.dashboard',
            'admin.posts.index',
            'admin.categories.index',
            'admin.users.index',
            'admin.trainers.index',
            'admin.comments.index',
        ];
        
        foreach ($routes as $route) {
            $response = $this->get(route($route));
            $this->assertTrue($response->getStatusCode() == 302, "Route $route should redirect guests");
        }
    }

    /**
     * Test that admin middleware is correctly checking user roles.
     */
    public function test_admin_middleware_checks_user_role()
    {
        // Create a user and set role to admin
        $user = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
        
        // Test access as admin
        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertStatus(200);
        
        // Change role to user
        $user->update(['role' => 'user']);
        
        // Test access is now denied
        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('home'));
    }
} 