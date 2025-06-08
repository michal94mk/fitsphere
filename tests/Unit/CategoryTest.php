<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created()
    {
        $category = Category::create([
            'name' => 'Test Category',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
        ]);
    }

    public function test_category_has_relationships()
    {
        $category = Category::factory()->create();
        
        // Create posts in this category
        Post::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);
        
        // Create translations for this category
        CategoryTranslation::create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Testowa Kategoria',
        ]);
        
        $this->assertCount(3, $category->posts);
        $this->assertCount(1, $category->translations);
    }

    public function test_category_translations()
    {
        $category = Category::factory()->create([
            'name' => 'English Category',
        ]);
        
        // Create a translation
        CategoryTranslation::create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Polska Kategoria',
        ]);
        
        // Test translation exists
        $this->assertTrue($category->hasTranslation('pl'));
        
        // Set app locale to Polish
        app()->setLocale('pl');
        
        // Test translated content
        $this->assertEquals('Polska Kategoria', $category->getTranslatedName());
        
        // Test default locale fallback
        app()->setLocale('en');
        $this->assertEquals('English Category', $category->getTranslatedName());
        
        // Test non-existent locale
        $this->assertFalse($category->hasTranslation('fr'));
    }
} 