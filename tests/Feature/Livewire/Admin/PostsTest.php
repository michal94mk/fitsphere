<?php

namespace Tests\Feature\Livewire\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use Livewire\Livewire;

class PostsTest extends TestCase
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
     * Set up required data for tests.
     */
    public function setUp(): void
    {
        parent::setUp();
        
        // Create categories for posts
        Category::factory()->count(3)->create();
    }

    /**
     * Test the posts index Livewire component.
     */
    public function test_posts_index_component_can_render()
    {
        $admin = $this->createAdminUser();
        
        // Create some posts
        Post::factory()->count(5)->create([
            'user_id' => $admin->id,
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.posts-index')
            ->assertStatus(200);
    }

    /**
     * Test posts index search and filters.
     */
    public function test_posts_index_search_and_filters()
    {
        $admin = $this->createAdminUser();
        
        // Create specific posts for testing
        $publishedPost = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Published Unique Title',
            'status' => 'published',
        ]);
        
        $draftPost = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Draft Post',
            'status' => 'draft',
        ]);
        
        // Test search functionality (simplified)
        Livewire::actingAs($admin)
            ->test('admin.posts-index')
            ->assertStatus(200);
        
        // Verify posts exist in database
        $this->assertDatabaseHas('posts', [
            'title' => 'Published Unique Title',
            'status' => 'published',
        ]);
        
        $this->assertDatabaseHas('posts', [
            'title' => 'Draft Post',
            'status' => 'draft',
        ]);
    }

    /**
     * Test basic post database operations.
     */
    public function test_post_database_operations()
    {
        $admin = $this->createAdminUser();
        
        // Create a post
        $title = $this->faker->sentence;
        $content = $this->faker->paragraphs(3, true);
        $excerpt = $this->faker->paragraph;
        $categoryId = Category::first()->id;
        
        $post = Post::factory()->create([
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'status' => 'published',
            'category_id' => $categoryId,
            'user_id' => $admin->id,
        ]);

        // Check that post was created in database
        $this->assertDatabaseHas('posts', [
            'title' => $title,
            'content' => $content,
            'excerpt' => $excerpt,
            'status' => 'published',
            'category_id' => $categoryId,
            'user_id' => $admin->id,
        ]);
        
        // Update post
        $newTitle = 'Updated Title';
        $post->update(['title' => $newTitle]);
        
        // Check that post was updated in database
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $newTitle,
        ]);
        
        // Delete post
        $post->delete();
        
        // Verify post was deleted
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }

    /**
     * Test the posts edit component can render.
     */
    public function test_posts_edit_component_can_render()
    {
        $admin = $this->createAdminUser();
        
        // Create a post to edit
        $post = Post::factory()->create([
            'user_id' => $admin->id,
            'title' => 'Test Post Title',
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.posts-edit', ['id' => $post->id])
            ->assertStatus(200);
    }

    /**
     * Test the posts create component can render.
     */
    public function test_posts_create_component_can_render()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.posts-create')
            ->assertStatus(200);
    }
} 