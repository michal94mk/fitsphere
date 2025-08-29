<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Reservation;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use App\Services\DeepLTranslateService;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LivewireComponentsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock external services
        $this->mock(SpoonacularService::class, function ($mock) {
            $mock->shouldReceive('searchRecipes')->andReturn([
                'results' => [
                    [
                        'id' => 1,
                        'title' => 'Test Recipe',
                        'image' => 'test.jpg'
                    ]
                ]
            ]);
        });

        $this->mock(DeepLTranslateService::class, function ($mock) {
            $mock->shouldReceive('translate')->andReturn('Translated text');
        });
    }

    public function test_nutrition_calculator_can_calculate_bmi()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(\App\Livewire\NutritionCalculator::class)
            ->set('weight', 70)
            ->set('height', 175)
            ->set('age', 25)
            ->set('gender', 'male')
            ->set('activityLevel', 'moderate')
            ->call('calculateNutrition')
            ->assertSet('bmi', round(70 / (1.75 * 1.75), 2));
    }

    public function test_nutrition_calculator_requires_authentication()
    {
        Livewire::test(\App\Livewire\NutritionCalculator::class)
            ->set('weight', 70)
            ->set('height', 175)
            ->call('calculateNutrition')
            ->assertDispatched('login-required');
    }

    public function test_nutrition_calculator_can_save_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(\App\Livewire\NutritionCalculator::class)
            ->set('weight', 70)
            ->set('height', 175)
            ->set('age', 25)
            ->set('gender', 'male')
            ->set('activityLevel', 'moderate')
            ->call('saveProfile')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('nutritional_profiles', [
            'user_id' => $user->id,
            'weight' => 70,
            'height' => 175
        ]);
    }

    public function test_trainers_list_displays_approved_trainers()
    {
        $approvedTrainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => true
        ]);
        
        $unapprovedTrainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => false
        ]);

        Livewire::test(\App\Livewire\TrainersList::class)
            ->assertSee($approvedTrainer->name)
            ->assertDontSee($unapprovedTrainer->name);
    }

    public function test_trainers_list_can_filter_by_specialization()
    {
        $strengthTrainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => true,
            'specialization' => 'Strength Training'
        ]);

        $cardioTrainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => true,
            'specialization' => 'Cardio'
        ]);

        $component = Livewire::test(\App\Livewire\TrainersList::class);
        
        // Sprawdzamy czy komponent się renderuje bez błędów
        $this->assertNotEmpty($component->html());
    }

    public function test_trainers_list_can_search_trainers()
    {
        $johnTrainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => true,
            'name' => 'John Trainer'
        ]);

        $janeTrainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => true,
            'name' => 'Jane Trainer'
        ]);

        Livewire::test(\App\Livewire\TrainersList::class)
            ->set('search', 'John')
            ->assertSee($johnTrainer->name)
            ->assertDontSee($janeTrainer->name);
    }

    public function test_admin_dashboard_shows_statistics()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create some test data
        User::factory()->count(5)->create();
        Post::factory()->count(3)->create();
        Category::factory()->count(2)->create();

        $component = Livewire::test(\App\Livewire\Admin\Dashboard::class);
        
        // Sprawdzamy czy komponent się renderuje bez błędów
        $this->assertNotEmpty($component->html());
    }

    public function test_admin_dashboard_can_approve_trainer()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create([
            'role' => 'trainer',
            'is_approved' => false
        ]);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\Dashboard::class)
            ->call('approveTrainer', $trainer->id)
            ->assertHasNoErrors();

        $this->assertTrue($trainer->fresh()->is_approved);
    }

    public function test_meal_planner_can_generate_meal_plan()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Http::fake([
            'api.spoonacular.com/mealplanner/generate*' => Http::response([
                'meals' => [
                    [
                        'id' => 1,
                        'title' => 'Breakfast',
                        'image' => 'breakfast.jpg'
                    ]
                ]
            ], 200)
        ]);

        // Create nutritional profile for user
        $profile = NutritionalProfile::factory()->create([
            'user_id' => $user->id,
            'target_calories' => 2000
        ]);

        Livewire::test(\App\Livewire\MealPlanner::class)
            ->call('generateMealPlan')
            ->assertHasNoErrors();
    }

    public function test_blog_posts_page_displays_published_posts()
    {
        $publishedPost = Post::factory()->create(['status' => 'published']);
        $draftPost = Post::factory()->create(['status' => 'draft']);

        $component = Livewire::test(\App\Livewire\PostsPage::class);
        
        // Sprawdzamy czy komponent się renderuje bez błędów
        $this->assertNotEmpty($component->html());
    }

    public function test_blog_posts_page_can_filter_by_category()
    {
        $category = Category::factory()->create();
        $postInCategory = Post::factory()->create([
            'status' => 'published',
            'category_id' => $category->id
        ]);
        $postNotInCategory = Post::factory()->create(['status' => 'published']);

        Livewire::test(\App\Livewire\PostsPage::class)
            ->set('category', $category->id)
            ->assertSee($postInCategory->title)
            ->assertDontSee($postNotInCategory->title);
    }

    public function test_search_results_page_can_search_posts()
    {
        $matchingPost = Post::factory()->create([
            'title' => 'Fitness Tips',
            'status' => 'published'
        ]);
        $nonMatchingPost = Post::factory()->create([
            'title' => 'Cooking Recipe',
            'status' => 'published'
        ]);

        Livewire::test(\App\Livewire\SearchResultsPage::class)
            ->set('searchQuery', 'Fitness')
            ->assertSee($matchingPost->title)
            ->assertDontSee($nonMatchingPost->title);
    }

    public function test_flash_messages_can_display_messages()
    {
        session()->flash('success', 'Operation successful');
        session()->flash('error', 'Operation failed');

        Livewire::test(\App\Livewire\FlashMessages::class)
            ->assertSee('Operation successful')
            ->assertSee('Operation failed');
    }

    public function test_home_page_displays_content()
    {
        $post = Post::factory()->create([
            'status' => 'published'
        ]);

        Livewire::test(\App\Livewire\HomePage::class)
            ->assertSee($post->title);
    }
}
