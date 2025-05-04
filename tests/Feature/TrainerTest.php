<?php

namespace Tests\Feature;

use App\Models\Trainer;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_trainers_list_page_can_be_viewed()
    {
        // Create some trainers
        $trainers = Trainer::factory()->count(3)->create();

        // Visit the trainers page
        $response = $this->get(route('trainers.list'));

        // Assert response is successful
        $response->assertStatus(200);
        
        // Assert page contains some basic HTML elements
        $response->assertSee('Trainers', false);
    }

    public function test_trainer_details_page_can_be_viewed()
    {
        // Create a trainer
        $trainer = Trainer::factory()->create([
            'name' => 'John Doe',
            'specialization' => 'Fitness',
            'bio' => 'Professional fitness trainer'
        ]);

        // Visit the trainer details page
        $response = $this->get(route('trainer.show', $trainer->id));

        // Assert response is successful
        $response->assertStatus(200);
    }
} 