<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'trainer_id' => User::factory()->create(['role' => 'trainer'])->id,
            'date' => $this->faker->dateTimeBetween('+1 day', '+2 months')->format('Y-m-d'),
            'start_time' => $this->faker->time('H:i:s'),
            'end_time' => $this->faker->time('H:i:s'),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled']),
            'notes' => $this->faker->optional(0.7)->sentence,
        ];
    }
    
    public function confirmed(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'confirmed',
            ];
        });
    }
} 