<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\PostView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_view_can_be_created()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $postView = PostView::create([
            'post_id' => $post->id,
            'ip_address' => '127.0.0.1',
            'user_id' => $user->id,
            'user_agent' => 'PHPUnit Test',
        ]);

        $this->assertDatabaseHas('post_views', [
            'post_id' => $post->id,
            'ip_address' => '127.0.0.1',
            'user_id' => $user->id,
            'user_agent' => 'PHPUnit Test',
        ]);
    }

    public function test_post_view_can_be_created_without_user()
    {
        $post = Post::factory()->create();

        $postView = PostView::create([
            'post_id' => $post->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit Test',
        ]);

        $this->assertDatabaseHas('post_views', [
            'post_id' => $post->id,
            'ip_address' => '127.0.0.1',
            'user_id' => null,
            'user_agent' => 'PHPUnit Test',
        ]);
    }

    public function test_post_view_belongs_to_post()
    {
        $post = Post::factory()->create();
        
        $postView = PostView::create([
            'post_id' => $post->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit Test',
        ]);
        
        $this->assertInstanceOf(Post::class, $postView->post);
        $this->assertEquals($post->id, $postView->post->id);
    }
} 