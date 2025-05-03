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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateBMI(): ?float
    {
        if (!$this->weight || !$this->height) {
            return null;
        }

        $heightInMeters = $this->height / 100;
        return round($this->weight / ($heightInMeters * $heightInMeters), 2);
    }

    public function calculateBMR(): ?float
    {
        if (!$this->weight || !$this->height || !$this->age || !$this->gender) {
            return null;
        }

        if ($this->gender === 'male') {
            return 88.362 + (13.397 * $this->weight) + (4.799 * $this->height) - (5.677 * $this->age);
        } else {
            return 447.593 + (9.247 * $this->weight) + (3.098 * $this->height) - (4.330 * $this->age);
        }
    }

    public function calculateDailyCalories(): ?float
    {
        $bmr = $this->calculateBMR();
        if (!$bmr || !$this->activity_level) {
            return null;
        }

        $activityMultipliers = [
            'sedentary' => 1.2,
            'light' => 1.375,
            'moderate' => 1.55,
            'active' => 1.725,
            'very_active' => 1.9,
        ];

        $calories = $bmr * $activityMultipliers[$this->activity_level];
        
        if ($this->goal === 'lose') {
            $calories *= 0.85;
        } elseif ($this->goal === 'gain') {
            $calories *= 1.15;
        }
        
        return round($calories);
    }
}
