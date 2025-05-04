<?php

namespace Database\Factories;

use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TrainerFactory extends Factory
{
    protected $model = Trainer::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'specialization' => $this->faker->randomElement(['Fitness', 'Yoga', 'Cardio', 'Strength', 'Pilates']),
            'description' => $this->faker->sentence,
            'bio' => $this->faker->sentence,
            'image' => null,
            'experience' => $this->faker->numberBetween(1, 15),
            'is_approved' => true,
            'remember_token' => Str::random(10),
        ];
    }
} 