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
            'trainer_id' => Trainer::factory(),
            'date' => $this->faker->dateTimeBetween('+1 day', '+2 months')->format('Y-m-d'),
            'time' => $this->faker->randomElement(['09:00', '10:00', '11:00', '15:00', '16:00', '17:00']),
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