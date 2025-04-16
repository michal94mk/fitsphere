<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'date',
        'meal_type',
        'recipe_data',
        'calories',
        'protein',
        'carbs',
        'fat',
        'notes',
        'is_favorite',
    ];

    protected $casts = [
        'recipe_data' => 'array',
        'date' => 'date',
        'calories' => 'float',
        'protein' => 'float',
        'carbs' => 'float',
        'fat' => 'float',
        'is_favorite' => 'boolean',
    ];

    /**
     * Get the user that owns the meal plan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get meal plans for a specific date range.
     */
    public static function getForDateRange($userId, $startDate, $endDate)
    {
        return self::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('meal_type')
            ->get();
    }

    /**
     * Get daily nutritional totals for a given date.
     */
    public static function getDailyTotals($userId, $date)
    {
        $meals = self::where('user_id', $userId)
            ->where('date', $date)
            ->get();

        $totals = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fat' => 0,
        ];

        foreach ($meals as $meal) {
            $totals['calories'] += $meal->calories ?? 0;
            $totals['protein'] += $meal->protein ?? 0;
            $totals['carbs'] += $meal->carbs ?? 0;
            $totals['fat'] += $meal->fat ?? 0;
        }

        return $totals;
    }

    /**
     * Get daily nutritional totals for a given date range.
     */
    public static function getDailyTotalsForDateRange($userId, $startDate, $endDate)
    {
        $meals = self::where('user_id', $userId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get()
            ->groupBy(function($meal) {
                return $meal->date->format('Y-m-d');
            });
        
        $totals = [];
        
        foreach ($meals as $date => $dayMeals) {
            $totals[$date] = [
                'calories' => $dayMeals->sum(function($meal) { return $meal->calories ?? 0; }),
                'protein' => $dayMeals->sum(function($meal) { return $meal->protein ?? 0; }),
                'carbs' => $dayMeals->sum(function($meal) { return $meal->carbs ?? 0; }),
                'fat' => $dayMeals->sum(function($meal) { return $meal->fat ?? 0; }),
            ];
        }
        
        return $totals;
    }
}
