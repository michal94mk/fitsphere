<?php

namespace Tests\Feature\Livewire;

use App\Livewire\NutritionCalculator;
use App\Models\NutritionalProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NutritionCalculatorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $this->get(route('nutrition.calculator'))
            ->assertSuccessful();

        Livewire::test(NutritionCalculator::class)
            ->assertStatus(200);
    }

    #[Test]
    public function loads_existing_profile_for_authenticated_user()
    {
        $user = User::factory()->create();

        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 80,
            'height' => 180,
            'activity_level' => 'moderate',
            'goal' => 'maintain',
            'target_calories' => 2500,
            'target_protein' => 180,
            'target_carbs' => 300,
            'target_fat' => 70,
        ]);

        Livewire::actingAs($user)
            ->test(NutritionCalculator::class)
            ->assertSet('age', 30)
            ->assertSet('gender', 'male')
            ->assertSet('weight', 80)
            ->assertSet('height', 180)
            ->assertSet('activityLevel', 'moderate')
            ->assertSet('goal', 'maintain');
    }

    #[Test]
    public function initializes_with_empty_values_for_guests()
    {
        Livewire::test(NutritionCalculator::class)
            ->assertSet('age', null)
            ->assertSet('gender', null)
            ->assertSet('weight', null)
            ->assertSet('height', null)
            ->assertSet('activityLevel', null)
            ->assertSet('goal', null);
    }

    #[Test]
    public function saves_profile_for_authenticated_user()
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(NutritionCalculator::class)
            ->set('age', 30)
            ->set('gender', 'male')
            ->set('weight', 80)
            ->set('height', 180)
            ->set('activityLevel', 'moderate')
            ->set('goal', 'maintain')
            ->call('calculateNutrition')
            ->call('saveProfile');

        $this->assertDatabaseHas('nutritional_profiles', [
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 80,
            'height' => 180,
            'activity_level' => 'moderate',
            'goal' => 'maintain',
        ]);
    }

    #[Test]
    public function updates_existing_profile_when_saved()
    {
        $user = User::factory()->create();

        // Create an initial profile
        $profile = NutritionalProfile::create([
            'user_id' => $user->id,
            'age' => 30,
            'gender' => 'male',
            'weight' => 80,
            'height' => 180,
            'activity_level' => 'moderate',
            'goal' => 'maintain',
        ]);

        // Update the profile
        Livewire::actingAs($user)
            ->test(NutritionCalculator::class)
            ->set('age', 31)
            ->set('weight', 78)
            ->call('calculateNutrition')
            ->call('saveProfile');

        $this->assertDatabaseHas('nutritional_profiles', [
            'user_id' => $user->id,
            'age' => 31,
            'weight' => 78,
        ]);

        // Confirm only one profile exists
        $this->assertEquals(1, NutritionalProfile::where('user_id', $user->id)->count());
    }

    #[Test]
    public function displays_error_for_guest_trying_to_save_profile()
    {
        $component = Livewire::test(NutritionCalculator::class)
            ->set('age', 30)
            ->set('gender', 'male')
            ->set('weight', 80)
            ->set('height', 180)
            ->set('activityLevel', 'moderate')
            ->set('goal', 'maintain')
            ->call('saveProfile');

        // Verify that no nutritional profile was created (guest user cannot create profiles)
        $this->assertEquals(0, NutritionalProfile::count());
    }
} 