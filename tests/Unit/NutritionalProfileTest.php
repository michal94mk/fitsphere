<?php

namespace Tests\Unit;

use App\Models\NutritionalProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NutritionalProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_nutritional_profile_can_be_created()
    {
        $user = User::factory()->create();

        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 80.5,
            'height' => 180.0,
            'activity_level' => 'moderate',
            'goal' => 'maintain',
            'target_calories' => 2500,
            'target_protein' => 180,
            'target_carbs' => 300,
            'target_fat' => 70,
            'dietary_restrictions' => ['gluten', 'lactose'],
        ]);

        $this->assertDatabaseHas('nutritional_profiles', [
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'activity_level' => 'moderate',
            'goal' => 'maintain',
        ]);
        
        // Test casts work properly
        $this->assertEquals(80.5, $profile->weight);
        $this->assertEquals(180.0, $profile->height);
        $this->assertEquals(['gluten', 'lactose'], $profile->dietary_restrictions);
    }

    public function test_nutritional_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 80.0,
            'height' => 180.0,
            'activity_level' => 'moderate',
            'goal' => 'maintain',
        ]);
        
        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($user->id, $profile->user->id);
    }

    public function test_calculate_bmi()
    {
        $user = User::factory()->create();
        
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'weight' => 80.0,
            'height' => 180.0,
        ]);
        
        // BMI = weight(kg) / height(m)²
        // 80 / (1.8 * 1.8) = 24.69
        $this->assertEquals(24.69, $profile->calculateBMI());
        
        // Test profile without weight/height
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'weight' => null,
            'height' => 180.0,
        ]);
        
        $this->assertNull($profile->calculateBMI());
    }

    public function test_calculate_bmr()
    {
        $user = User::factory()->create();
        
        // Test male BMR
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 80.0,
            'height' => 180.0,
        ]);
        
        // Male: 88.362 + (13.397 * weight) + (4.799 * height) - (5.677 * age)
        $expectedBmr = 88.362 + (13.397 * 80.0) + (4.799 * 180.0) - (5.677 * 30);
        $this->assertEquals($expectedBmr, $profile->calculateBMR());
        
        // Test female BMR
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'female',
            'weight' => 65.0,
            'height' => 165.0,
        ]);
        
        // Female: 447.593 + (9.247 * weight) + (3.098 * height) - (4.330 * age)
        $expectedBmr = 447.593 + (9.247 * 65.0) + (3.098 * 165.0) - (4.330 * 30);
        $this->assertEquals($expectedBmr, $profile->calculateBMR());
        
        // Test missing data
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => null,
            'gender' => 'male',
            'weight' => 80.0,
            'height' => 180.0,
        ]);
        
        $this->assertNull($profile->calculateBMR());
    }

    public function test_calculate_daily_calories()
    {
        $user = User::factory()->create();
        
        // Test maintain goal
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 80.0,
            'height' => 180.0,
            'activity_level' => 'moderate',
            'goal' => 'maintain',
        ]);
        
        $bmr = 88.362 + (13.397 * 80.0) + (4.799 * 180.0) - (5.677 * 30);
        $expectedCalories = round($bmr * 1.55); // moderate activity
        $this->assertEquals($expectedCalories, $profile->calculateDailyCalories());
        
        // Test lose goal
        $profile->goal = 'lose';
        $profile->save();
        
        $expectedCalories = round($bmr * 1.55 * 0.85); // With 15% deficit
        $this->assertEquals($expectedCalories, $profile->calculateDailyCalories());
        
        // Test gain goal
        $profile->goal = 'gain';
        $profile->save();
        
        $expectedCalories = round($bmr * 1.55 * 1.15); // With 15% surplus
        $this->assertEquals($expectedCalories, $profile->calculateDailyCalories());
    }
} 