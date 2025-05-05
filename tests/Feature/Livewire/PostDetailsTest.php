<?php

namespace Tests\Feature\Livewire;

use App\Livewire\PostDetails;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\PostView;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PostDetailsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post',
            'content' => 'Test content',
            'status' => 'published',
        ]);

        Livewire::test(PostDetails::class, ['postId' => $post->id])
            ->assertStatus(200);
    }

    #[Test]
    public function displays_post_title_and_content()
    {
        $post = Post::factory()->create([
            'title' => 'Test Post Title',
            'content' => 'Test post content',
            'status' => 'published',
        ]);

        Livewire::test(PostDetails::class, ['postId' => $post->id])
            ->assertSee('Test Post Title')
            ->assertSee('Test post content');
    }

    #[Test]
    public function increments_view_count_when_viewed()
    {
        $post = Post::factory()->create([
            'view_count' => 0,
            'status' => 'published',
        ]);

        $this->assertEquals(0, $post->view_count);

        Livewire::test(PostDetails::class, ['postId' => $post->id]);

        $this->assertEquals(1, $post->refresh()->view_count);
    }

    #[Test]
    public function creates_post_view_record_when_viewed()
    {
        $post = Post::factory()->create([
            'status' => 'published',
        ]);

        $this->assertEquals(0, PostView::count());

        Livewire::test(PostDetails::class, ['postId' => $post->id]);

        $this->assertEquals(1, PostView::count());
        $this->assertDatabaseHas('post_views', [
            'post_id' => $post->id,
        ]);
    }

    #[Test]
    public function guest_cannot_add_comment()
    {
        $post = Post::factory()->create([
            'status' => 'published',
        ]);

        // Testing session flashes in Livewire requires a different approach
        Livewire::test(PostDetails::class, ['postId' => $post->id])
            ->set('newComment', 'This is a test comment')
            ->call('addComment');
            
        // Instead of checking session directly, verify no comment was created
        $this->assertEquals(0, Comment::count());
    }

    #[Test]
    public function authenticated_user_can_add_comment()
    {
        $post = Post::factory()->create([
            'status' => 'published',
        ]);

        $user = User::factory()->create();

        // Test the actual outcome instead of the session flash
        Livewire::actingAs($user)
            ->test(PostDetails::class, ['postId' => $post->id])
            ->set('newComment', 'This is a test comment')
            ->call('addComment');

        // Verify comment was created in database
        $this->assertEquals(1, Comment::count());
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'This is a test comment',
        ]);
    }

    #[Test]
    public function validates_comment_content()
    {
        $post = Post::factory()->create([
            'status' => 'published',
        ]);

        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(PostDetails::class, ['postId' => $post->id])
            ->set('newComment', 'a')
            ->call('addComment')
            ->assertHasErrors(['newComment' => 'min']);

        $this->assertEquals(0, Comment::count());
    }

    #[Test]
    public function shows_comments_for_post()
    {
        $post = Post::factory()->create([
            'status' => 'published',
        ]);

        $user = User::factory()->create([
            'name' => 'John Doe',
        ]);

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'This is a comment',
        ]);

        Livewire::test(PostDetails::class, ['postId' => $post->id])
            ->assertSee('This is a comment')
            ->assertSee('John Doe');
    }
} 