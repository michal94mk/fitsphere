<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_has_correct_attributes()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'Test comment content'
        ]);

        $this->assertEquals('Test comment content', $comment->content);
        $this->assertEquals($user->id, $comment->user_id);
        $this->assertEquals($post->id, $comment->post_id);
    }

    public function test_comment_belongs_to_user()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }

    public function test_comment_belongs_to_post()
    {
        $post = Post::factory()->create();
        $comment = Comment::factory()->create(['post_id' => $post->id]);

        $this->assertInstanceOf(Post::class, $comment->post);
        $this->assertEquals($post->id, $comment->post->id);
    }

    public function test_comment_author_returns_user()
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertEquals('John Doe', $comment->author()->name);
        $this->assertEquals($user->id, $comment->author()->id);
    }

    public function test_comment_belongs_to_auth_user_when_authenticated()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        Auth::login($user);
        $this->assertTrue($comment->belongsToAuthUser());
    }

    public function test_comment_does_not_belong_to_auth_user_when_different_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user1->id]);

        Auth::login($user2);
        $this->assertFalse($comment->belongsToAuthUser());
    }

    public function test_comment_does_not_belong_to_auth_user_when_not_authenticated()
    {
        $comment = Comment::factory()->create();

        $this->assertFalse($comment->belongsToAuthUser());
    }
} 