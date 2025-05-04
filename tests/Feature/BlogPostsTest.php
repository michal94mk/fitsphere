<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_posts_page_can_be_viewed()
    {
        // Create some posts
        $posts = Post::factory()->count(5)->create([
            'status' => 'published'
        ]);

        // Visit the posts page
        $response = $this->get(route('posts.list'));

        // Assert response is successful
        $response->assertStatus(200);
        
        // Assert page contains some basic HTML elements
        $response->assertSee('Posts', false);
    }

    public function test_post_details_page_can_be_viewed()
    {
        // Create a post
        $post = Post::factory()->create([
            'title' => 'Test Post Title',
            'content' => 'Test post content',
            'status' => 'published'
        ]);

        // Visit the post details page
        $response = $this->get(route('post.show', $post->id));

        // Assert response is successful
        $response->assertStatus(200);
    }
} 