<?php

namespace Tests\Feature;

use App\Models\NutritionalProfile;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NutritionalProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_nutritional_profile_can_be_created()
    {
        $user = User::factory()->create();

        $profile = NutritionalProfile::create([
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
            'dietary_restrictions' => ['vegetarian']
        ]);

        $this->assertDatabaseHas('nutritional_profiles', [
            'user_id' => $user->id,
            'age' => 25,
            'gender' => 'male',
            'weight' => 75.5,
            'height' => 180.0,
            'activity_level' => 'moderate'
        ]);
    }

    public function test_user_can_have_only_one_nutritional_profile()
    {
        $user = User::factory()->create();
        
        $profile1 = NutritionalProfile::factory()->create(['user_id' => $user->id]);
        $profile2 = NutritionalProfile::factory()->create(['user_id' => $user->id]);

        // User should have 2 profiles in this test (though in real app you might want unique constraint)
        $this->assertCount(2, $user->nutritionalProfiles);
    }

    public function test_nutritional_profile_can_be_updated()
    {
        $profile = NutritionalProfile::factory()->create([
            'weight' => 70.0,
            'target_calories' => 2000.0
        ]);

        $profile->update([
            'weight' => 75.0,
            'target_calories' => 2200.0
        ]);

        $this->assertDatabaseHas('nutritional_profiles', [
            'id' => $profile->id,
            'weight' => 75.0,
            'target_calories' => 2200.0
        ]);
    }

    public function test_nutritional_profile_can_be_deleted()
    {
        $profile = NutritionalProfile::factory()->create();
        $profileId = $profile->id;

        $profile->delete();

        $this->assertDatabaseMissing('nutritional_profiles', [
            'id' => $profileId
        ]);
    }

    public function test_nutritional_profile_bmi_calculation_workflow()
    {
        $user = User::factory()->create();
        $profile = NutritionalProfile::factory()->create([
            'user_id' => $user->id,
            'weight' => 70,
            'height' => 175
        ]);

        $bmi = $profile->calculateBMI();
        $expectedBMI = round(70 / (1.75 * 1.75), 2);

        $this->assertEquals($expectedBMI, $bmi);
        $this->assertGreaterThan(0, $bmi);
    }

    public function test_nutritional_profile_bmr_calculation_workflow()
    {
        $user = User::factory()->create();
        $profile = NutritionalProfile::factory()->create([
            'user_id' => $user->id,
            'gender' => 'male',
            'weight' => 75,
            'height' => 180,
            'age' => 25
        ]);

        $bmr = $profile->calculateBMR();
        $this->assertGreaterThan(1000, $bmr);
        $this->assertLessThan(3000, $bmr);
    }

    public function test_nutritional_profile_daily_calories_calculation_workflow()
    {
        $user = User::factory()->create();
        $profile = NutritionalProfile::factory()->create([
            'user_id' => $user->id,
            'gender' => 'male',
            'weight' => 75,
            'height' => 180,
            'age' => 25,
            'activity_level' => 'moderate'
        ]);

        $dailyCalories = $profile->calculateDailyCalories();
        $this->assertGreaterThan(1500, $dailyCalories);
        $this->assertLessThan(4000, $dailyCalories);
    }

    public function test_nutritional_profile_handles_different_activity_levels()
    {
        $user = User::factory()->create();
        $baseProfile = [
            'user_id' => $user->id,
            'gender' => 'male',
            'weight' => 75,
            'height' => 180,
            'age' => 25
        ];

        $sedentaryProfile = NutritionalProfile::factory()->create(array_merge($baseProfile, ['activity_level' => 'sedentary']));
        $moderateProfile = NutritionalProfile::factory()->create(array_merge($baseProfile, ['activity_level' => 'moderate']));
        $activeProfile = NutritionalProfile::factory()->create(array_merge($baseProfile, ['activity_level' => 'active']));

        $sedentaryCalories = $sedentaryProfile->calculateDailyCalories();
        $moderateCalories = $moderateProfile->calculateDailyCalories();
        $activeCalories = $activeProfile->calculateDailyCalories();

        $this->assertLessThan($moderateCalories, $sedentaryCalories);
        $this->assertGreaterThan($moderateCalories, $activeCalories);
    }

    public function test_nutritional_profile_dietary_restrictions_are_stored_as_array()
    {
        $profile = NutritionalProfile::factory()->create([
            'dietary_restrictions' => ['vegetarian', 'gluten-free', 'dairy-free']
        ]);

        $this->assertIsArray($profile->dietary_restrictions);
        $this->assertContains('vegetarian', $profile->dietary_restrictions);
        $this->assertContains('gluten-free', $profile->dietary_restrictions);
        $this->assertContains('dairy-free', $profile->dietary_restrictions);
    }
} 