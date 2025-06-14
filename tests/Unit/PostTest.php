<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_has_correct_attributes()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Post',
            'slug' => 'test-post',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'status' => 'published',
        ]);

        $this->assertEquals('Test Post', $post->title);
        $this->assertEquals('test-post', $post->slug);
        $this->assertEquals('Test excerpt', $post->excerpt);
        $this->assertEquals('Test content', $post->content);
        $this->assertEquals('published', $post->status);
    }
} 