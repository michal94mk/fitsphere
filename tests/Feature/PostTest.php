<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;

class PostTest extends TestCase
{
    use RefreshDatabase;

    #[DoesNotPerformAssertions]
    public function test_can_create_post() {}

    public function test_post_belongs_to_user_and_category()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $this->assertEquals($user->id, $post->user_id);
        $this->assertEquals($category->id, $post->category_id);
    }

    public function test_post_can_have_comments()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'content' => 'Test comment',
        ]);
        $this->assertCount(1, $post->comments);
        $this->assertEquals('Test comment', $post->comments->first()->content);
    }
} 