<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_can_be_created()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $comment = Comment::create([
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->assertDatabaseHas('comments', [
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function test_comment_has_relationships()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        
        $comment = Comment::create([
            'content' => 'This is a test comment',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
        
        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertInstanceOf(Post::class, $comment->post);
        $this->assertEquals($user->id, $comment->user->id);
        $this->assertEquals($post->id, $comment->post->id);
    }
} 