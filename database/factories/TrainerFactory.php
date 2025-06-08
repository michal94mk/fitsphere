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
        // Available trainer images
        $trainerImages = [
            'images/trainers/trainer1.jpg',
            'images/trainers/trainer2.jpg',
            'images/trainers/trainer3.jpg',
        ];
        
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'specialization' => $this->faker->randomElement(['Fitness', 'Yoga', 'Cardio', 'Strength', 'Pilates']),
            'description' => $this->faker->sentence,
            'bio' => $this->faker->sentence,
            'image' => $this->faker->randomElement($trainerImages),
            'experience' => $this->faker->numberBetween(1, 15),
            'is_approved' => true,
            'remember_token' => Str::random(10),
        ];
    }
} 