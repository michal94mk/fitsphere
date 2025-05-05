<?php

namespace Tests\Feature\Livewire;

use App\Livewire\CreateReservation;
use App\Models\Reservation;
use App\Models\Trainer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CreateReservationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function unauthenticated_users_are_redirected_to_login()
    {
        $trainer = Trainer::factory()->create();

        Livewire::test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->assertRedirect(route('login'));
    }

    #[Test]
    public function renders_successfully_for_authenticated_user()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create([
            'name' => 'John Trainer',
            'specialization' => 'Strength Training',
        ]);

        $this->actingAs($user)
            ->get(route('reservation.create', $trainer->id))
            ->assertSuccessful();

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->assertStatus(200)
            ->assertSee('John Trainer')
            ->assertSee('Strength Training');
    }

    #[Test]
    public function initializes_with_todays_date()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        $today = Carbon::today()->format('Y-m-d');

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->assertSet('date', $today);
    }

    #[Test]
    public function can_select_date_and_time_slots()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->set('date', $tomorrow)
            ->call('selectTimeSlot', '10:00')
            ->assertSet('startTime', '10:00')
            ->call('selectTimeSlot', '11:00')
            ->assertSet('endTime', '11:00');
    }

    #[Test]
    public function can_reset_time_selection()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->call('selectTimeSlot', '10:00')
            ->assertSet('startTime', '10:00')
            ->call('selectTimeSlot', '11:00')
            ->assertSet('endTime', '11:00')
            ->call('resetTimeSelection')
            ->assertSet('startTime', null)
            ->assertSet('endTime', null);
    }

    #[Test]
    public function changing_date_resets_time_selection()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
        $dayAfterTomorrow = Carbon::tomorrow()->addDay()->format('Y-m-d');

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->set('date', $tomorrow)
            ->call('selectTimeSlot', '10:00')
            ->assertSet('startTime', '10:00')
            ->set('date', $dayAfterTomorrow)
            ->assertSet('startTime', null);
    }

    #[Test]
    public function cannot_create_reservation_with_invalid_data()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->set('date', $yesterday)
            ->set('startTime', '10:00')
            ->set('endTime', '11:00')
            ->call('createReservation')
            ->assertHasErrors(['date' => 'after_or_equal']);

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->set('date', Carbon::tomorrow()->format('Y-m-d'))
            ->set('startTime', null)
            ->set('endTime', '11:00')
            ->call('createReservation')
            ->assertHasErrors(['startTime' => 'required']);

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->set('date', Carbon::tomorrow()->format('Y-m-d'))
            ->set('startTime', '11:00')
            ->set('endTime', '10:00')
            ->call('createReservation')
            ->assertHasErrors(['endTime' => 'after']);
    }

    #[Test]
    public function can_create_valid_reservation()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->set('date', $tomorrow)
            ->set('startTime', '10:00')
            ->set('endTime', '11:00')
            ->set('notes', 'Test reservation note')
            ->call('createReservation')
            ->assertRedirect(route('user.reservations'))
            ->assertSessionHas('success', 'Rezerwacja została utworzona i oczekuje na potwierdzenie przez trenera.');

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'date' => $tomorrow,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'pending',
            'notes' => 'Test reservation note',
        ]);
    }

    #[Test]
    public function cannot_create_overlapping_reservation()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Create an existing reservation
        Reservation::create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'date' => $tomorrow,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'confirmed',
        ]);

        // Try to create an overlapping reservation
        Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->set('date', $tomorrow)
            ->set('startTime', '10:30')
            ->set('endTime', '11:30')
            ->call('createReservation');

        // Verify no new reservation was created
        $this->assertDatabaseCount('reservations', 1);
    }

    #[Test]
    public function can_navigate_calendar_months()
    {
        $user = User::factory()->create();
        $trainer = Trainer::factory()->create();
        $today = Carbon::today();
        $nextMonth = $today->copy()->addMonth()->format('Y-m-d');
        $prevMonth = $today->copy()->subMonth()->format('Y-m-d');

        $component = Livewire::actingAs($user)
            ->test(CreateReservation::class, ['trainerId' => $trainer->id])
            ->assertSet('date', $today->format('Y-m-d'));

        $component->call('nextMonth')
            ->assertSet('date', $nextMonth);

        $component->call('previousMonth')
            ->assertSet('date', $today->format('Y-m-d'));

        $component->call('previousMonth')
            ->assertSet('date', $prevMonth);
    }
} 