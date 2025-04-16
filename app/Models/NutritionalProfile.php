<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutritionalProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'weight',
        'height',
        'activity_level',
        'goal',
        'target_calories',
        'target_protein',
        'target_carbs',
        'target_fat',
        'dietary_restrictions',
    ];

    protected $casts = [
        'dietary_restrictions' => 'array',
        'weight' => 'float',
        'height' => 'float',
        'target_calories' => 'float',
        'target_protein' => 'float',
        'target_carbs' => 'float',
        'target_fat' => 'float',
    ];

    /**
     * Get the user that owns the nutritional profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate BMI based on weight and height.
     */
    public function calculateBMI(): ?float
    {
        if (!$this->weight || !$this->height) {
            return null;
        }

        // BMI = weight(kg) / (height(m))²
        $heightInMeters = $this->height / 100;
        return round($this->weight / ($heightInMeters * $heightInMeters), 2);
    }

    /**
     * Calculate base metabolic rate using Harris-Benedict formula.
     */
    public function calculateBMR(): ?float
    {
        if (!$this->weight || !$this->height || !$this->age || !$this->gender) {
            return null;
        }

        // Harris-Benedict formula
        if ($this->gender === 'male') {
            return 88.362 + (13.397 * $this->weight) + (4.799 * $this->height) - (5.677 * $this->age);
        } else {
            return 447.593 + (9.247 * $this->weight) + (3.098 * $this->height) - (4.330 * $this->age);
        }
    }

    /**
     * Calculate daily calorie needs based on activity level.
     */
    public function calculateDailyCalories(): ?float
    {
        $bmr = $this->calculateBMR();
        if (!$bmr || !$this->activity_level) {
            return null;
        }

        $activityMultipliers = [
            'sedentary' => 1.2, // Minimal activity
            'light' => 1.375, // Light exercise 1-3 days a week
            'moderate' => 1.55, // Moderate exercise 3-5 days a week
            'active' => 1.725, // Hard exercise 6-7 days a week
            'very_active' => 1.9, // Very hard exercise or physical job
        ];

        $calories = $bmr * $activityMultipliers[$this->activity_level];
        
        // Adjust based on goal
        if ($this->goal === 'lose') {
            $calories *= 0.85; // 15% deficit for weight loss
        } elseif ($this->goal === 'gain') {
            $calories *= 1.15; // 15% surplus for weight gain
        }
        
        return round($calories);
    }
}
