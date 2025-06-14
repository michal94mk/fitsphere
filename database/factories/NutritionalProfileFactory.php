<?php

namespace Database\Factories;

use App\Models\NutritionalProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NutritionalProfileFactory extends Factory
{
    protected $model = NutritionalProfile::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'age' => $this->faker->numberBetween(18, 65),
            'height' => $this->faker->numberBetween(150, 200),
            'weight' => $this->faker->numberBetween(50, 100),
            'activity_level' => $this->faker->randomElement(['sedentary', 'light', 'moderate', 'active', 'very_active']),
            'target_calories' => $this->faker->numberBetween(1500, 3000),
            'target_protein' => $this->faker->numberBetween(50, 200),
            'target_carbs' => $this->faker->numberBetween(100, 400),
            'target_fat' => $this->faker->numberBetween(30, 100),
            'dietary_restrictions' => [],
        ];
    }
} 