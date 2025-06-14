<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_correct_attributes()
    {
        $category = Category::factory()->create([
            'name' => 'Test Category'
        ]);

        $this->assertEquals('Test Category', $category->name);
        $this->assertInstanceOf(Category::class, $category);
    }

    public function test_category_can_have_posts()
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $this->assertCount(1, $category->posts);
        $this->assertEquals($post->id, $category->posts->first()->id);
    }

    public function test_category_can_have_translations()
    {
        $category = Category::factory()->create();
        $translation = CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Kategoria testowa'
        ]);

        $this->assertCount(1, $category->translations);
        $this->assertEquals('Kategoria testowa', $category->translations->first()->name);
    }

    public function test_category_can_check_if_has_translation()
    {
        $category = Category::factory()->create();
        CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Kategoria testowa'
        ]);

        $this->assertTrue($category->hasTranslation('pl'));
        $this->assertFalse($category->hasTranslation('de'));
    }

    public function test_category_returns_translated_name_when_available()
    {
        $category = Category::factory()->create(['name' => 'Original Name']);
        CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Przetłumaczona nazwa'
        ]);

        app()->setLocale('pl');
        $this->assertEquals('Przetłumaczona nazwa', $category->getTranslatedName());
    }

    public function test_category_returns_original_name_when_translation_not_available()
    {
        $category = Category::factory()->create(['name' => 'Original Name']);

        app()->setLocale('de');
        $this->assertEquals('Original Name', $category->getTranslatedName());
    }
} 