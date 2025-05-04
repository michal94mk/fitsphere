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
            'age' => $this->faker->numberBetween(18, 70),
            'height' => $this->faker->numberBetween(150, 200),
            'weight' => $this->faker->numberBetween(45, 120),
            'activity_level' => $this->faker->randomElement(['sedentary', 'light', 'moderate', 'high', 'very_high']),
            'goal' => $this->faker->randomElement(['lose', 'maintain', 'gain']),
            'daily_calories' => $this->faker->numberBetween(1500, 3000),
            'carbs_percentage' => $this->faker->numberBetween(30, 50),
            'protein_percentage' => $this->faker->numberBetween(20, 35),
            'fat_percentage' => $this->faker->numberBetween(20, 35),
        ];
    }
} 