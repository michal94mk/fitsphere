<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\NutritionalProfile;
use App\Models\MealPlan;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        $this->assertEquals('user', $user->role);
    }

    public function test_user_has_profile_photo_url()
    {
        // User without image
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'image' => null,
        ]);
        
        $hash = md5(strtolower(trim('test@example.com')));
        $expectedUrl = "https://www.gravatar.com/avatar/{$hash}?d=mp&s=160";
        
        $this->assertEquals($expectedUrl, $user->getProfilePhotoUrlAttribute());
        
        // User with image
        $user = User::factory()->create([
            'image' => 'images/users/profile-image.jpg',
        ]);
        
        $this->assertEquals(asset('storage/images/users/profile-image.jpg'), $user->getProfilePhotoUrlAttribute());
    }

    public function test_user_has_relationships()
    {
        $user = User::factory()->create();
        $trainer = \App\Models\Trainer::factory()->create();
        
        // Test relationship with nutritional profile
        NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 75.0,
            'height' => 180.0,
        ]);
        
        // Test relationship with meal plans
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Test Meal Plan 1',
            'date' => '2023-12-01',
            'recipe_data' => ['test' => 'data'],
        ]);
        
        MealPlan::create([
            'user_id' => $user->id,
            'name' => 'Test Meal Plan 2',
            'date' => '2023-12-02',
            'recipe_data' => ['test' => 'data'],
        ]);
        
        // Test relationship with reservations
        Reservation::create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'date' => '2023-12-01',
            'start_time' => '10:00',
            'end_time' => '11:00',
            'status' => 'confirmed',
        ]);
        
        $this->assertInstanceOf(NutritionalProfile::class, $user->nutritionalProfile);
        $this->assertCount(2, $user->mealPlans);
        $this->assertCount(1, $user->reservations);
    }
} 