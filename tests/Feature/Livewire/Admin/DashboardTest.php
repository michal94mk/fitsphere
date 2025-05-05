<?php

namespace Tests\Feature\Livewire\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Trainer;
use App\Models\Comment;
use App\Mail\TrainerApproved;
use App\Services\EmailService;
use Livewire\Livewire;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Illuminate\Support\Facades\App;

class DashboardTest extends TestCase
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
     * Test the dashboard component can render.
     */
    public function test_dashboard_component_can_render()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.dashboard')
            ->assertViewIs('livewire.admin.dashboard');
    }

    /**
     * Test dashboard shows statistics based on the database.
     */
    public function test_dashboard_shows_correct_statistics()
    {
        $admin = $this->createAdminUser();
        
        // Create data for statistics
        $usersCount = 3;
        User::factory()->count($usersCount)->create(); // Plus admin = 4 users total
        
        $publishedPostsCount = 5;
        Post::factory()->count($publishedPostsCount)->create([
            'user_id' => $admin->id,
            'status' => 'published'
        ]);
        
        $draftPostsCount = 2;
        Post::factory()->count($draftPostsCount)->create([
            'user_id' => $admin->id,
            'status' => 'draft'
        ]);
        
        $approvedTrainersCount = 3;
        Trainer::factory()->count($approvedTrainersCount)->create(['is_approved' => true]);
        
        $pendingTrainersCount = 2;
        Trainer::factory()->count($pendingTrainersCount)->create(['is_approved' => false]);
        
        // Add some comments
        $commentsCount = 4;
        $post = Post::first();
        Comment::factory()->count($commentsCount)->create([
            'post_id' => $post->id,
            'user_id' => $admin->id
        ]);
        
        $component = Livewire::actingAs($admin)
            ->test('admin.dashboard');
            
        // Verify key statistics are present
        $stats = $component->get('stats');
        
        // Custom assertions that are more flexible
        $this->assertEquals($usersCount + 1, $stats['users'], "Users count mismatch");
        $this->assertEquals($approvedTrainersCount + $pendingTrainersCount, $stats['trainers'], "Trainers count mismatch");
        $this->assertEquals($publishedPostsCount + $draftPostsCount, $stats['posts'], "Posts count mismatch");
        $this->assertEquals($commentsCount, $stats['comments'], "Comments count mismatch");
        $this->assertEquals($pendingTrainersCount, $stats['pendingTrainers'], "Pending trainers count mismatch");
    }

    /**
     * Test dashboard shows recent users.
     */
    public function test_dashboard_shows_recent_users()
    {
        $admin = $this->createAdminUser();
        
        $user1 = User::factory()->create(['name' => 'Test User One']);
        $user2 = User::factory()->create(['name' => 'Test User Two']);
        
        Livewire::actingAs($admin)
            ->test('admin.dashboard')
            ->assertSee('Test User One')
            ->assertSee('Test User Two');
    }

    /**
     * Test dashboard shows pending trainers.
     */
    public function test_dashboard_shows_pending_trainers()
    {
        $admin = $this->createAdminUser();
        
        $trainer1 = Trainer::factory()->create([
            'name' => 'Pending Trainer 1',
            'is_approved' => false
        ]);
        
        $trainer2 = Trainer::factory()->create([
            'name' => 'Approved Trainer',
            'is_approved' => true
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.dashboard')
            ->assertSee('Pending Trainer 1');
    }

    /**
     * Test dashboard shows popular posts.
     */
    public function test_dashboard_shows_popular_posts()
    {
        $admin = $this->createAdminUser();
        
        $post1 = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Popular Post'
        ]);
        
        $post2 = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Less Popular Post'
        ]);
        
        // Add comments to make post1 more popular
        Comment::factory()->count(5)->create([
            'post_id' => $post1->id,
            'user_id' => $admin->id
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.dashboard')
            ->assertSee('Popular Post');
    }
    
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 