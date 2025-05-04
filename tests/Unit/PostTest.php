<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\PostTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_can_be_created()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $post = Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Post',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'Test Post',
            'user_id' => $user->id,
        ]);
        
        $this->assertEquals('test-post', $post->slug);
    }

    public function test_post_has_relationships()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
        
        $this->assertInstanceOf(User::class, $post->user);
        $this->assertInstanceOf(Category::class, $post->category);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $post->comments);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $post->translations);
    }

    public function test_post_translations()
    {
        $post = Post::factory()->create([
            'title' => 'English Title',
            'content' => 'English Content',
        ]);
        
        // Create a translation
        PostTranslation::create([
            'post_id' => $post->id,
            'locale' => 'pl',
            'title' => 'Polski Tytuł',
            'content' => 'Polska Treść',
            'excerpt' => 'Polski Wstęp',
        ]);
        
        // Test translation exists
        $this->assertTrue($post->hasTranslation('pl'));
        
        // Set app locale to Polish
        app()->setLocale('pl');
        
        // Test translated content
        $this->assertEquals('Polski Tytuł', $post->getTranslatedTitle());
        $this->assertEquals('Polska Treść', $post->getTranslatedContent());
        $this->assertEquals('Polski Wstęp', $post->getTranslatedExcerpt());
    }
} 