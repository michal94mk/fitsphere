<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Reservation;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_reservation_has_correct_relationships()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create(['role' => 'trainer']);
        
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id
        ]);

        $this->assertEquals($user->id, $reservation->user->id);
        $this->assertEquals($trainer->id, $reservation->trainer->id);
    }

    public function test_reservation_can_be_created_via_factory()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create(['role' => 'trainer']);
        
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'notes' => 'Test notes'
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'notes' => 'Test notes'
        ]);
    }

    public function test_reservation_can_be_updated()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create(['role' => 'trainer']);
        
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'notes' => 'Original notes'
        ]);

        $reservation->update(['notes' => 'Updated notes']);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'notes' => 'Updated notes'
        ]);
    }

    public function test_reservation_status_can_be_changed()
    {
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        $reservation->update(['status' => 'confirmed']);

        $this->assertEquals('confirmed', $reservation->fresh()->status);
    }

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get('/admin');

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);

        $response = $this->get('/admin');

        $response->assertStatus(302); // Middleware redirects to home instead of 403
    }
}
