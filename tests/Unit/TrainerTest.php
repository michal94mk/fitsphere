<?php

namespace Tests\Unit;

use App\Models\Trainer;
use App\Models\TrainerTranslation;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerTest extends TestCase
{
    use RefreshDatabase;

    public function test_trainer_can_be_created()
    {
        $trainer = Trainer::create([
            'name' => 'Test Trainer',
            'email' => 'trainer@example.com',
            'password' => bcrypt('password'),
            'specialization' => 'Strength Training',
            'description' => 'Experienced trainer',
            'bio' => 'Professional trainer with 10 years of experience',
            'is_approved' => true,
        ]);

        $this->assertDatabaseHas('trainers', [
            'name' => 'Test Trainer',
            'email' => 'trainer@example.com',
            'specialization' => 'Strength Training',
            'is_approved' => 1,
        ]);
    }

    public function test_trainer_has_profile_photo_url()
    {
        // Trainer without image
        $trainer = Trainer::factory()->create([
            'email' => 'trainer@example.com',
            'image' => null,
        ]);
        
        $hash = md5(strtolower(trim('trainer@example.com')));
        $expectedUrl = "https://www.gravatar.com/avatar/{$hash}?d=mp&s=160";
        
        $this->assertEquals($expectedUrl, $trainer->getProfilePhotoUrlAttribute());
        
        // Trainer with image
        $trainer = Trainer::factory()->create([
            'image' => 'trainers/profile-image.jpg',
        ]);
        
        $this->assertEquals(asset('storage/trainers/profile-image.jpg'), $trainer->getProfilePhotoUrlAttribute());
    }

    public function test_trainer_has_relationships()
    {
        $trainer = Trainer::factory()->create();
        $user = \App\Models\User::factory()->create();
        
        // Create reservations for this trainer
        Reservation::create([
            'trainer_id' => $trainer->id,
            'user_id' => $user->id,
            'date' => '2023-12-01',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'confirmed',
        ]);
        
        Reservation::create([
            'trainer_id' => $trainer->id,
            'user_id' => $user->id,
            'date' => '2023-12-02',
            'start_time' => '14:00',
            'end_time' => '15:00',
            'status' => 'confirmed',
        ]);
        
        $this->assertCount(2, $trainer->reservations);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $trainer->reservations);
    }

    public function test_trainer_translations()
    {
        $trainer = Trainer::factory()->create([
            'specialization' => 'Weight Training',
            'description' => 'Experienced trainer',
            'bio' => 'Professional trainer',
        ]);
        
        // Create a translation
        TrainerTranslation::create([
            'trainer_id' => $trainer->id,
            'locale' => 'pl',
            'specialization' => 'Trening siłowy',
            'description' => 'Doświadczony trener',
            'bio' => 'Profesjonalny trener',
        ]);
        
        // Test translation exists
        $this->assertTrue($trainer->hasTranslation('pl'));
        
        // Set app locale to Polish
        app()->setLocale('pl');
        
        // Test translated content
        $this->assertEquals('Trening siłowy', $trainer->getTranslatedSpecialization());
        $this->assertEquals('Doświadczony trener', $trainer->getTranslatedDescription());
        $this->assertEquals('Profesjonalny trener', $trainer->getTranslatedBio());
        
        // Test default locale fallback
        app()->setLocale('en');
        $this->assertEquals('Weight Training', $trainer->getTranslatedSpecialization());
    }
} 