<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryTranslation;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_can_be_created()
    {
        $category = Category::create([
            'name' => 'Test Category'
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);

        $this->assertEquals('Test Category', $category->name);
    }

    public function test_category_can_have_multiple_posts()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();
        
        $post1 = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);
        $post2 = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);

        $this->assertCount(2, $category->posts);
        $this->assertTrue($category->posts->contains($post1));
        $this->assertTrue($category->posts->contains($post2));
    }

    public function test_category_can_have_translations_in_multiple_languages()
    {
        $category = Category::factory()->create(['name' => 'English Name']);
        
        CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Polska nazwa'
        ]);
        
        CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'locale' => 'de',
            'name' => 'Deutsche Name'
        ]);

        $this->assertCount(2, $category->translations);
        $this->assertTrue($category->hasTranslation('pl'));
        $this->assertTrue($category->hasTranslation('de'));
        $this->assertFalse($category->hasTranslation('fr'));
    }

    public function test_category_returns_correct_translated_name_based_on_locale()
    {
        $category = Category::factory()->create(['name' => 'English Name']);
        
        CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Polska nazwa'
        ]);

        // Test Polish translation
        app()->setLocale('pl');
        $this->assertEquals('Polska nazwa', $category->getTranslatedName());

        // Test fallback to original name
        app()->setLocale('fr');
        $this->assertEquals('English Name', $category->getTranslatedName());
    }

    public function test_category_can_be_updated()
    {
        $category = Category::factory()->create(['name' => 'Original Name']);

        $category->update(['name' => 'Updated Name']);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name'
        ]);
    }

    public function test_category_can_be_deleted()
    {
        $category = Category::factory()->create();
        $categoryId = $category->id;

        $category->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $categoryId
        ]);
    }

    public function test_deleting_category_removes_associated_translations()
    {
        $category = Category::factory()->create();
        $translation = CategoryTranslation::factory()->create([
            'category_id' => $category->id,
            'locale' => 'pl',
            'name' => 'Polska nazwa'
        ]);

        $category->delete();

        $this->assertDatabaseMissing('category_translations', [
            'id' => $translation->id
        ]);
    }
} 