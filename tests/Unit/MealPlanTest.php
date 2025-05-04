<?php

namespace Tests\Unit;

use App\Models\MealPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MealPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_meal_plan_can_be_created()
    {
        $user = User::factory()->create();

        $mealPlan = MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Breakfast Plan',
            'date' => '2023-12-01',
            'meal_type' => 'breakfast',
            'recipe_data' => [
                'ingredients' => ['oats', 'milk', 'banana'],
                'preparation' => 'Mix all ingredients and microwave for 2 minutes',
            ],
            'calories' => 450.5,
            'protein' => 20.5,
            'carbs' => 65.0,
            'fat' => 12.0,
            'notes' => 'High protein breakfast',
            'is_favorite' => true,
        ]);

        $this->assertDatabaseHas('meal_plans', [
            'user_id' => $user->id,
            'name' => 'Breakfast Plan',
            'date' => '2023-12-01',
            'meal_type' => 'breakfast',
            'notes' => 'High protein breakfast',
            'is_favorite' => 1,
        ]);
        
        // Test casts work properly
        $this->assertEquals(['ingredients' => ['oats', 'milk', 'banana'], 'preparation' => 'Mix all ingredients and microwave for 2 minutes'], $mealPlan->recipe_data);
        $this->assertEquals(450.5, $mealPlan->calories);
        $this->assertEquals(20.5, $mealPlan->protein);
        $this->assertEquals(65.0, $mealPlan->carbs);
        $this->assertEquals(12.0, $mealPlan->fat);
        $this->assertTrue($mealPlan->is_favorite);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $mealPlan->date);
    }

    public function test_meal_plan_belongs_to_user()
    {
        $user = User::factory()->create();
        
        $mealPlan = MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Lunch Plan',
            'date' => '2023-12-01',
            'meal_type' => 'lunch',
            'recipe_data' => ['test' => 'data'],
        ]);
        
        $this->assertInstanceOf(User::class, $mealPlan->user);
        $this->assertEquals($user->id, $mealPlan->user->id);
    }

    public function test_get_for_date_range()
    {
        $user = User::factory()->create();
        
        // Create meal plans for different dates
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Breakfast Plan',
            'date' => '2023-12-01',
            'meal_type' => 'breakfast',
            'recipe_data' => ['test' => 'data'],
        ]);
        
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Lunch Plan',
            'date' => '2023-12-02',
            'meal_type' => 'lunch',
            'recipe_data' => ['test' => 'data'],
        ]);
        
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Dinner Plan',
            'date' => '2023-12-03',
            'meal_type' => 'dinner',
            'recipe_data' => ['test' => 'data'],
        ]);
        
        // Create meal for another user (should not be returned)
        $anotherUser = User::factory()->create();
        MealPlan::create([
            'user_id' => $anotherUser->id,
            'name' => 'Another User Plan',
            'date' => '2023-12-02',
            'meal_type' => 'lunch',
            'recipe_data' => ['test' => 'data'],
        ]);
        
        $mealPlans = MealPlan::getForDateRange($user->id, '2023-12-01', '2023-12-02');
        $this->assertCount(2, $mealPlans);
        
        $mealPlans = MealPlan::getForDateRange($user->id, '2023-12-01', '2023-12-03');
        $this->assertCount(3, $mealPlans);
    }

    public function test_get_daily_totals()
    {
        $user = User::factory()->create();
        
        // Create multiple meals for the same day
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Breakfast Plan',
            'date' => '2023-12-01',
            'meal_type' => 'breakfast',
            'recipe_data' => ['test' => 'data'],
            'calories' => 400,
            'protein' => 20,
            'carbs' => 50,
            'fat' => 10,
        ]);
        
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Lunch Plan',
            'date' => '2023-12-01',
            'meal_type' => 'lunch',
            'recipe_data' => ['test' => 'data'],
            'calories' => 600,
            'protein' => 35,
            'carbs' => 70,
            'fat' => 15,
        ]);
        
        $totals = MealPlan::getDailyTotals($user->id, '2023-12-01');
        
        $this->assertEquals(1000, $totals['calories']);
        $this->assertEquals(55, $totals['protein']);
        $this->assertEquals(120, $totals['carbs']);
        $this->assertEquals(25, $totals['fat']);
    }

    public function test_get_daily_totals_for_date_range()
    {
        $user = User::factory()->create();
        
        // Create meals for different days
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Day 1 Plan',
            'date' => '2023-12-01',
            'recipe_data' => ['test' => 'data'],
            'calories' => 1000,
            'protein' => 50,
            'carbs' => 120,
            'fat' => 25,
        ]);
        
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Day 2 Plan',
            'date' => '2023-12-02',
            'recipe_data' => ['test' => 'data'],
            'calories' => 1200,
            'protein' => 60,
            'carbs' => 150,
            'fat' => 30,
        ]);
        
        $totals = MealPlan::getDailyTotalsForDateRange($user->id, '2023-12-01', '2023-12-02');
        
        $this->assertCount(2, $totals);
        $this->assertEquals(1000, $totals['2023-12-01']['calories']);
        $this->assertEquals(50, $totals['2023-12-01']['protein']);
        $this->assertEquals(1200, $totals['2023-12-02']['calories']);
        $this->assertEquals(60, $totals['2023-12-02']['protein']);
    }
} 