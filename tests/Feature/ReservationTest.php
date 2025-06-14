<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Reservation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    #[DoesNotPerformAssertions]
    public function test_user_can_create_reservation() {}

    #[DoesNotPerformAssertions]
    public function test_cannot_create_overlapping_reservations() {}

    #[DoesNotPerformAssertions]
    public function test_can_cancel_reservation() {}

    #[DoesNotPerformAssertions]
    public function test_can_get_upcoming_reservations() {}
} 