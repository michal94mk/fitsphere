<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_comment_can_be_created()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'This is a test comment'
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'content' => 'This is a test comment'
        ]);
    }

    public function test_post_can_have_multiple_comments()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create();

        $comment1 = Comment::factory()->create([
            'user_id' => $user1->id,
            'post_id' => $post->id,
            'content' => 'First comment'
        ]);

        $comment2 = Comment::factory()->create([
            'user_id' => $user2->id,
            'post_id' => $post->id,
            'content' => 'Second comment'
        ]);

        $this->assertCount(2, $post->comments);
        $this->assertTrue($post->comments->contains($comment1));
        $this->assertTrue($post->comments->contains($comment2));
    }

    public function test_user_can_have_multiple_comments()
    {
        $user = User::factory()->create();
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->create();

        $comment1 = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post1->id,
            'content' => 'Comment on first post'
        ]);

        $comment2 = Comment::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post2->id,
            'content' => 'Comment on second post'
        ]);

        $this->assertCount(2, $user->comments);
        $this->assertTrue($user->comments->contains($comment1));
        $this->assertTrue($user->comments->contains($comment2));
    }

    public function test_comment_can_be_updated()
    {
        $comment = Comment::factory()->create([
            'content' => 'Original content'
        ]);

        $comment->update(['content' => 'Updated content']);

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Updated content'
        ]);
    }

    public function test_comment_can_be_deleted()
    {
        $comment = Comment::factory()->create();
        $commentId = $comment->id;

        $comment->delete();

        $this->assertDatabaseMissing('comments', [
            'id' => $commentId
        ]);
    }

    public function test_comment_author_relationship_works()
    {
        $user = User::factory()->create(['name' => 'John Doe']);
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        $this->assertEquals('John Doe', $comment->author()->name);
        $this->assertEquals($user->id, $comment->author()->id);
    }

    public function test_comment_ownership_verification_with_authenticated_user()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user->id]);

        Auth::login($user);
        $this->assertTrue($comment->belongsToAuthUser());

        Auth::logout();
        $this->assertFalse($comment->belongsToAuthUser());
    }

    public function test_comment_ownership_verification_with_different_user()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $comment = Comment::factory()->create(['user_id' => $user1->id]);

        Auth::login($user2);
        $this->assertFalse($comment->belongsToAuthUser());
    }
} 