<?php

namespace Tests\Unit;

use App\Services\SpoonacularService;
use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpoonacularServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SpoonacularService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SpoonacularService();
        
        // Mock API key
        config(['services.spoonacular.key' => 'test_api_key']);
    }

    public function test_translate_uses_dictionary_for_known_terms()
    {
        $result = $this->service->translate('kurczak', 'pl', 'en');
        
        $this->assertEquals('chicken', $result);
    }

    public function test_translate_handles_multi_word_queries()
    {
        $result = $this->service->translate('pierś z kurczaka', 'pl', 'en');
        
        // The dictionary doesn't have this exact phrase, so it should return null
        $this->assertNull($result);
    }

    public function test_translate_throws_exception_without_api_key()
    {
        // Create new service instance without API key
        config(['services.spoonacular.key' => null]);
        $service = new SpoonacularService();
        
        $this->expectException(ApiException::class);
        $service->translate('test', 'pl', 'en');
    }

    public function test_search_recipes_throws_exception_without_api_key()
    {
        // Create new service instance without API key
        config(['services.spoonacular.key' => null]);
        $service = new SpoonacularService();
        
        $this->expectException(ApiException::class);
        $service->searchRecipes('chicken');
    }

    public function test_search_recipes_translates_polish_queries()
    {
        Http::fake([
            'api.spoonacular.com/recipes/complexSearch*' => Http::response([
                'results' => [],
                'totalResults' => 0
            ], 200)
        ]);

        $result = $this->service->searchRecipes('kurczak');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('results', $result);
    }

    public function test_get_recipe_information_throws_exception_without_api_key()
    {
        config(['services.spoonacular.key' => null]);
        
        $this->expectException(ApiException::class);
        $this->service->getRecipeInformation(123);
    }

    public function test_get_recipe_information_returns_cached_data()
    {
        $recipeData = [
            'id' => 123,
            'title' => 'Test Recipe',
            'instructions' => 'Test instructions'
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($recipeData);

        Http::fake([
            'api.spoonacular.com/recipes/123/information*' => Http::response($recipeData, 200)
        ]);

        $result = $this->service->getRecipeInformation(123);
        
        $this->assertEquals($recipeData, $result);
    }

    public function test_generate_meal_plan_throws_exception_without_api_key()
    {
        // Create new service instance without API key
        config(['services.spoonacular.key' => null]);
        $service = new SpoonacularService();
        
        $this->expectException(ApiException::class);
        $service->generateMealPlan(2000);
    }

    public function test_generate_meal_plan_returns_valid_data()
    {
        $mealPlanData = [
            'meals' => [
                [
                    'id' => 123,
                    'title' => 'Breakfast',
                    'image' => 'breakfast.jpg'
                ]
            ]
        ];

        Http::fake([
            'api.spoonacular.com/mealplanner/generate*' => Http::response($mealPlanData, 200)
        ]);

        $result = $this->service->generateMealPlan(2000);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('meals', $result);
    }

    public function test_format_image_url_handles_full_urls()
    {
        $url = 'https://example.com/image.jpg';
        $result = $this->service->formatImageUrl($url);
        
        $this->assertEquals($url, $result);
    }

    public function test_format_image_url_handles_recipe_ids()
    {
        $result = $this->service->formatImageUrl('123');
        
        $this->assertEquals('https://spoonacular.com/recipeImages/123-312x231.jpg', $result);
    }

    public function test_generate_image_url_from_id()
    {
        $result = $this->service->generateImageUrlFromId(123);
        
        $this->assertEquals('https://spoonacular.com/recipeImages/123-312x231.jpg', $result);
    }

    public function test_get_random_recipes_throws_exception_without_api_key()
    {
        config(['services.spoonacular.key' => null]);
        
        $this->expectException(ApiException::class);
        $this->service->getRandomRecipes(3);
    }

    public function test_get_random_recipes_returns_fresh_data_when_skip_cache()
    {
        $recipesData = [
            'recipes' => [
                [
                    'id' => 123,
                    'title' => 'Random Recipe'
                ]
            ]
        ];

        Http::fake([
            'api.spoonacular.com/recipes/random*' => Http::response($recipesData, 200)
        ]);

        $result = $this->service->getRandomRecipes(3, ['skipCache' => true]);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('recipes', $result);
    }

    public function test_get_food_information_throws_exception_without_api_key()
    {
        // Create new service instance without API key
        config(['services.spoonacular.key' => null]);
        $service = new SpoonacularService();
        
        $this->expectException(ApiException::class);
        $service->getFoodInformation('apple');
    }

    public function test_get_food_information_returns_cached_data()
    {
        $foodData = [
            [
                'id' => 1,
                'name' => 'Apple',
                'image' => 'apple.jpg'
            ]
        ];

        Cache::shouldReceive('remember')
            ->once()
            ->andReturn($foodData);

        Http::fake([
            'api.spoonacular.com/food/ingredients/search*' => Http::response($foodData, 200)
        ]);

        $result = $this->service->getFoodInformation('apple');
        
        $this->assertEquals($foodData, $result);
    }
}
