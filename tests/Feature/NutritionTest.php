<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\NutritionalProfile;
use App\Models\MealPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NutritionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    #[Test]
    public function nutrition_calculator_page_can_be_viewed()
    {
        // Visit the nutrition calculator page
        $response = $this->get(route('nutrition.calculator'));

        // Assert response is successful
        $response->assertStatus(200);
        
        // Assert page contains some basic HTML elements
        $response->assertSee('Nutrition Calculator', false);
    }

    #[Test]
    public function meal_planner_page_can_be_viewed()
    {
        // Visit the meal planner page
        $response = $this->get(route('meal-planner'));

        // Assert response is successful
        $response->assertStatus(200);
        
        // Assert page contains some basic HTML elements
        $response->assertSee('Meal Planner', false);
    }
} 