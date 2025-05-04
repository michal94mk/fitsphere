<?php

namespace Tests\Unit;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_can_be_created()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'date' => '2023-12-01',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'confirmed',
            'notes' => 'Test reservation notes',
        ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'date' => '2023-12-01',
            'status' => 'confirmed',
            'notes' => 'Test reservation notes',
        ]);
    }

    public function test_reservation_has_relationships()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'date' => '2023-12-01',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'confirmed',
        ]);
        
        $this->assertInstanceOf(User::class, $reservation->user);
        $this->assertInstanceOf(Trainer::class, $reservation->trainer);
        $this->assertEquals($user->id, $reservation->user->id);
        $this->assertEquals($trainer->id, $reservation->trainer->id);
    }

    public function test_reservation_casts_dates_correctly()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        
        $reservation = Reservation::create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'date' => '2023-12-01',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'confirmed',
        ]);
        
        $this->assertIsObject($reservation->date);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $reservation->date);
        $this->assertEquals('2023-12-01', $reservation->date->toDateString());
        
        // Test time casts
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $reservation->start_time);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $reservation->end_time);
        $this->assertEquals('10:00', $reservation->start_time->format('H:i'));
        $this->assertEquals('11:00', $reservation->end_time->format('H:i'));
    }
} 