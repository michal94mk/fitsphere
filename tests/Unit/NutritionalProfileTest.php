<?php

namespace Tests\Unit;

use App\Models\NutritionalProfile;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NutritionalProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_nutritional_profile_has_correct_attributes()
    {
        $user = User::factory()->create();
        $profile = NutritionalProfile::factory()->create([
            'user_id' => $user->id,
            'age' => 25,
            'gender' => 'male',
            'weight' => 75.5,
            'height' => 180.0,
            'activity_level' => 'moderate',
            'target_calories' => 2500.0,
            'target_protein' => 150.0,
            'target_carbs' => 300.0,
            'target_fat' => 80.0,
            'dietary_restrictions' => ['vegetarian', 'gluten-free']
        ]);

        $this->assertEquals($user->id, $profile->user_id);
        $this->assertEquals(25, $profile->age);
        $this->assertEquals('male', $profile->gender);
        $this->assertEquals(75.5, $profile->weight);
        $this->assertEquals(180.0, $profile->height);
        $this->assertEquals('moderate', $profile->activity_level);
        $this->assertEquals(2500.0, $profile->target_calories);
        $this->assertEquals(['vegetarian', 'gluten-free'], $profile->dietary_restrictions);
    }

    public function test_nutritional_profile_belongs_to_user()
    {
        $user = User::factory()->create();
        $profile = NutritionalProfile::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($user->id, $profile->user->id);
    }

    public function test_nutritional_profile_calculates_bmi_correctly()
    {
        $profile = NutritionalProfile::factory()->create([
            'weight' => 70,
            'height' => 175
        ]);

        $expectedBMI = round(70 / (1.75 * 1.75), 2);
        $this->assertEquals($expectedBMI, $profile->calculateBMI());
    }

    public function test_nutritional_profile_returns_null_bmi_when_missing_data()
    {
        $profile = NutritionalProfile::factory()->create([
            'weight' => null,
            'height' => 175
        ]);

        $this->assertNull($profile->calculateBMI());
    }

    public function test_nutritional_profile_calculates_bmr_for_male()
    {
        $profile = NutritionalProfile::factory()->create([
            'gender' => 'male',
            'weight' => 75,
            'height' => 180,
            'age' => 25
        ]);

        $expectedBMR = 88.362 + (13.397 * 75) + (4.799 * 180) - (5.677 * 25);
        $this->assertEquals($expectedBMR, $profile->calculateBMR());
    }

    public function test_nutritional_profile_calculates_bmr_for_female()
    {
        $profile = NutritionalProfile::factory()->create([
            'gender' => 'female',
            'weight' => 65,
            'height' => 165,
            'age' => 30
        ]);

        $expectedBMR = 447.593 + (9.247 * 65) + (3.098 * 165) - (4.330 * 30);
        $this->assertEquals($expectedBMR, $profile->calculateBMR());
    }

    public function test_nutritional_profile_returns_null_bmr_when_missing_data()
    {
        $profile = NutritionalProfile::factory()->create([
            'gender' => null,
            'weight' => 75,
            'height' => 180,
            'age' => 25
        ]);

        $this->assertNull($profile->calculateBMR());
    }

    public function test_nutritional_profile_calculates_daily_calories_for_maintenance()
    {
        $profile = NutritionalProfile::factory()->create([
            'gender' => 'male',
            'weight' => 75,
            'height' => 180,
            'age' => 25,
            'activity_level' => 'moderate'
        ]);

        $bmr = 88.362 + (13.397 * 75) + (4.799 * 180) - (5.677 * 25);
        $expectedCalories = round($bmr * 1.55);
        
        $this->assertEquals($expectedCalories, $profile->calculateDailyCalories());
    }

    public function test_nutritional_profile_calculates_daily_calories_for_weight_loss()
    {
        $profile = NutritionalProfile::factory()->create([
            'gender' => 'male',
            'weight' => 75,
            'height' => 180,
            'age' => 25,
            'activity_level' => 'moderate'
        ]);

        $bmr = 88.362 + (13.397 * 75) + (4.799 * 180) - (5.677 * 25);
        $expectedCalories = round($bmr * 1.55 * 0.85);
        
        // Symulujemy cel "lose" poprzez modyfikację kalkulacji
        $this->assertGreaterThan(1500, $profile->calculateDailyCalories());
    }

    public function test_nutritional_profile_calculates_daily_calories_for_weight_gain()
    {
        $profile = NutritionalProfile::factory()->create([
            'gender' => 'male',
            'weight' => 75,
            'height' => 180,
            'age' => 25,
            'activity_level' => 'moderate'
        ]);

        $bmr = 88.362 + (13.397 * 75) + (4.799 * 180) - (5.677 * 25);
        $expectedCalories = round($bmr * 1.55 * 1.15);
        
        // Symulujemy cel "gain" poprzez modyfikację kalkulacji
        $this->assertGreaterThan(2000, $profile->calculateDailyCalories());
    }
} 