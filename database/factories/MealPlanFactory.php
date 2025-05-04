<?php

namespace Database\Factories;

use App\Models\MealPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealPlanFactory extends Factory
{
    protected $model = MealPlan::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence,
            'daily_calories' => $this->faker->numberBetween(1500, 3000),
            'daily_protein' => $this->faker->numberBetween(80, 200),
            'daily_carbs' => $this->faker->numberBetween(150, 350),
            'daily_fat' => $this->faker->numberBetween(40, 100),
            'meals' => json_encode([
                [
                    'name' => 'Breakfast',
                    'time' => '08:00',
                    'foods' => [
                        ['name' => 'Oatmeal', 'calories' => 150, 'protein' => 6, 'carbs' => 27, 'fat' => 2.5],
                        ['name' => 'Banana', 'calories' => 105, 'protein' => 1.3, 'carbs' => 27, 'fat' => 0.4],
                    ]
                ],
                [
                    'name' => 'Lunch',
                    'time' => '13:00',
                    'foods' => [
                        ['name' => 'Chicken Breast', 'calories' => 165, 'protein' => 31, 'carbs' => 0, 'fat' => 3.6],
                        ['name' => 'Brown Rice', 'calories' => 216, 'protein' => 5, 'carbs' => 45, 'fat' => 1.8],
                    ]
                ],
            ]),
        ];
    }
} 