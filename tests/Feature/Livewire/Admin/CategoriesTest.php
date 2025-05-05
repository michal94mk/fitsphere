<?php

namespace Tests\Feature\Livewire\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Livewire\Livewire;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * Create an admin user for testing.
     *
     * @return User
     */
    private function createAdminUser(): User
    {
        return User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Test the categories index Livewire component.
     */
    public function test_categories_index_component_can_render()
    {
        $admin = $this->createAdminUser();
        
        // Create some categories
        Category::factory()->count(3)->create();
        
        Livewire::actingAs($admin)
            ->test('admin.categories-index')
            ->assertViewIs('livewire.admin.categories-index');
    }

    /**
     * Test category search functionality.
     */
    public function test_categories_search()
    {
        $admin = $this->createAdminUser();
        
        // Create specific categories for testing
        $category1 = Category::factory()->create([
            'name' => 'Fitness Tips',
        ]);
        
        $category2 = Category::factory()->create([
            'name' => 'Nutrition Advice',
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.categories-index')
            ->set('search', 'Fitness')
            ->assertSee('Fitness Tips')
            ->assertDontSee('Nutrition Advice');
    }

    /**
     * Test category creation functionality.
     */
    public function test_create_category()
    {
        $admin = $this->createAdminUser();
        
        $name = $this->faker->word . ' Category';
        
        Livewire::actingAs($admin)
            ->test('admin.categories-create')
            ->set('name', $name)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.categories.index'));

        // Check that category was created in database
        $this->assertDatabaseHas('categories', [
            'name' => $name,
        ]);
    }

    /**
     * Test category validation.
     */
    public function test_create_category_validation()
    {
        $admin = $this->createAdminUser();
        
        Livewire::actingAs($admin)
            ->test('admin.categories-create')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }

    /**
     * Test basic category operations.
     */
    public function test_basic_category_operations()
    {
        // Create a category
        $name = $this->faker->word . ' Category';
        
        $category = Category::create([
            'name' => $name
        ]);
        
        // Verify it exists
        $this->assertDatabaseHas('categories', [
            'name' => $name
        ]);
        
        // Update category
        $newName = 'Updated Category';
        $category->update(['name' => $newName]);
        
        // Verify update
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $newName
        ]);
        
        // Delete category
        $category->delete();
        
        // Verify deletion
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id
        ]);
    }

    /**
     * Test category edit functionality.
     */
    public function test_edit_category()
    {
        $admin = $this->createAdminUser();
        
        // Create a category to edit
        $category = Category::factory()->create([
            'name' => 'Original Name',
        ]);
        
        $newName = 'Updated Name';
        
        Livewire::actingAs($admin)
            ->test('admin.categories-edit', ['id' => $category->id])
            ->assertSet('name', 'Original Name')
            ->set('name', $newName)
            ->call('save')
            ->assertHasNoErrors()
            ->assertRedirect(route('admin.categories.index'));
        
        // Check that category was updated in database
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $newName,
        ]);
    }

    /**
     * Test category deletion.
     */
    public function test_delete_category()
    {
        $admin = $this->createAdminUser();
        
        // Create categories
        $category = Category::factory()->create([
            'name' => 'Category to Delete',
        ]);
        
        Livewire::actingAs($admin)
            ->test('admin.categories-index')
            ->call('confirmCategoryDeletion', $category->id)
            ->assertSet('confirmingCategoryDeletion', true)
            ->assertSet('categoryIdBeingDeleted', $category->id)
            ->call('deleteCategory');
        
        // Verify category was deleted
        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }
} 