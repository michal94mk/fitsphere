<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NutritionalProfile;
use App\Models\MealPlan;
use App\Services\SpoonacularService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NutritionController extends Controller
{
    protected $spoonacularService;

    public function __construct(SpoonacularService $spoonacularService)
    {
        $this->spoonacularService = $spoonacularService;
    }

    /**
     * Pobierz profil żywieniowy użytkownika.
     */
    public function getProfile()
    {
        $profile = Auth::user()->nutritionalProfile;
        
        if (!$profile) {
            return response()->json([
                'message' => 'Profil żywieniowy nie istnieje',
                'profile' => null
            ]);
        }
        
        return response()->json([
            'message' => 'Profil żywieniowy pobrany pomyślnie',
            'profile' => $profile
        ]);
    }

    /**
     * Aktualizuj profil żywieniowy użytkownika.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validatedData = $request->validate([
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:male,female,other',
            'weight' => 'nullable|numeric|min:1|max:500',
            'height' => 'nullable|numeric|min:1|max:300',
            'activity_level' => 'nullable|in:sedentary,light,moderate,active,very_active',
            'goal' => 'nullable|in:maintain,lose,gain',
            'dietary_restrictions' => 'nullable|array',
        ]);
        
        $profile = $user->nutritionalProfile;
        
        if (!$profile) {
            $profile = new NutritionalProfile(['user_id' => $user->id]);
        }
        
        $profile->fill($validatedData);
        
        // Oblicz wartości docelowe, jeśli podano wystarczająco danych
        if ($profile->weight && $profile->height && $profile->age && $profile->gender && $profile->activity_level) {
            $dailyCalories = $profile->calculateDailyCalories();
            $profile->target_calories = $dailyCalories;
            
            // Standardowe proporcje makroskładników: 30% białko, 40% węglowodany, 30% tłuszcze
            $profile->target_protein = ($dailyCalories * 0.30) / 4; // 4 kalorie na gram białka
            $profile->target_carbs = ($dailyCalories * 0.40) / 4;   // 4 kalorie na gram węglowodanów
            $profile->target_fat = ($dailyCalories * 0.30) / 9;     // 9 kalorii na gram tłuszczu
        }
        
        $profile->save();
        
        return response()->json([
            'message' => 'Profil żywieniowy zaktualizowany pomyślnie',
            'profile' => $profile
        ]);
    }

    /**
     * Wyszukaj przepisy.
     */
    public function searchRecipes(Request $request)
    {
        $query = $request->input('query', '');
        $params = [];
        
        // Dodaj parametry filtrowania
        if ($request->has('diet')) {
            $params['diet'] = $request->input('diet');
        }
        
        if ($request->has('intolerances')) {
            $params['intolerances'] = $request->input('intolerances');
        }
        
        if ($request->has('maxCalories')) {
            $params['maxCalories'] = $request->input('maxCalories');
        }
        
        $results = $this->spoonacularService->searchRecipes($query, $params);
        
        return response()->json($results);
    }

    /**
     * Pobierz informacje o przepisie.
     */
    public function getRecipeInformation($id)
    {
        $recipeInfo = $this->spoonacularService->getRecipeInformation($id);
        
        if (!$recipeInfo) {
            return response()->json([
                'message' => 'Nie udało się pobrać informacji o przepisie',
                'recipe' => null
            ], 404);
        }
        
        return response()->json([
            'message' => 'Informacje o przepisie pobrane pomyślnie',
            'recipe' => $recipeInfo
        ]);
    }

    /**
     * Wygeneruj plan posiłków.
     */
    public function generateMealPlan(Request $request)
    {
        $user = Auth::user();
        $profile = $user->nutritionalProfile;
        
        if (!$profile || !$profile->target_calories) {
            return response()->json([
                'message' => 'Brak profilu żywieniowego z określonymi celami kalorycznymi',
                'plan' => null
            ], 400);
        }
        
        $params = [];
        
        if ($request->has('diet')) {
            $params['diet'] = $request->input('diet');
        }
        
        if ($request->has('exclude')) {
            $params['exclude'] = $request->input('exclude');
        }
        
        // Używamy docelowych kalorii z profilu
        $mealPlan = $this->spoonacularService->generateMealPlan($profile->target_calories, $params);
        
        if (!$mealPlan) {
            return response()->json([
                'message' => 'Nie udało się wygenerować planu posiłków',
                'plan' => null
            ], 500);
        }
        
        return response()->json([
            'message' => 'Plan posiłków wygenerowany pomyślnie',
            'plan' => $mealPlan
        ]);
    }

    /**
     * Zapisz posiłek do planu.
     */
    public function saveMealToPlan(Request $request)
    {
        $validatedData = $request->validate([
            'recipe_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'notes' => 'nullable|string',
        ]);
        
        $recipeInfo = $this->spoonacularService->getRecipeInformation($validatedData['recipe_id']);
        
        if (!$recipeInfo) {
            return response()->json([
                'message' => 'Nie udało się pobrać informacji o przepisie',
            ], 404);
        }
        
        // Pobierz dane o wartościach odżywczych
        $caloriesInfo = collect($recipeInfo['nutrition']['nutrients'])->firstWhere('name', 'Calories');
        $proteinInfo = collect($recipeInfo['nutrition']['nutrients'])->firstWhere('name', 'Protein');
        $carbsInfo = collect($recipeInfo['nutrition']['nutrients'])->firstWhere('name', 'Carbohydrates');
        $fatInfo = collect($recipeInfo['nutrition']['nutrients'])->firstWhere('name', 'Fat');
        
        $mealPlan = new MealPlan([
            'user_id' => Auth::id(),
            'name' => $validatedData['name'],
            'date' => $validatedData['date'],
            'meal_type' => $validatedData['meal_type'],
            'recipe_data' => $recipeInfo,
            'calories' => $caloriesInfo ? $caloriesInfo['amount'] : null,
            'protein' => $proteinInfo ? $proteinInfo['amount'] : null,
            'carbs' => $carbsInfo ? $carbsInfo['amount'] : null,
            'fat' => $fatInfo ? $fatInfo['amount'] : null,
            'notes' => $validatedData['notes'],
        ]);
        
        $mealPlan->save();
        
        return response()->json([
            'message' => 'Posiłek zapisany pomyślnie',
            'meal_plan' => $mealPlan
        ]);
    }

    /**
     * Pobierz zapisane posiłki.
     */
    public function getMealPlans(Request $request)
    {
        $startDate = $request->input('start_date', now()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        
        $mealPlans = MealPlan::getForDateRange(Auth::id(), $startDate, $endDate);
        
        $dailyTotals = [];
        
        // Grupuj posiłki według dnia
        $groupedMeals = $mealPlans->groupBy('date');
        
        // Oblicz łączne wartości odżywcze dla każdego dnia
        foreach ($groupedMeals as $date => $meals) {
            $dailyTotals[$date] = [
                'calories' => $meals->sum('calories'),
                'protein' => $meals->sum('protein'),
                'carbs' => $meals->sum('carbs'),
                'fat' => $meals->sum('fat'),
            ];
        }
        
        return response()->json([
            'message' => 'Plany posiłków pobrane pomyślnie',
            'meal_plans' => $mealPlans,
            'daily_totals' => $dailyTotals
        ]);
    }

    /**
     * Usuń zapisany posiłek.
     */
    public function deleteMealPlan($id)
    {
        $mealPlan = MealPlan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$mealPlan) {
            return response()->json([
                'message' => 'Nie znaleziono planu posiłku',
            ], 404);
        }
        
        $mealPlan->delete();
        
        return response()->json([
            'message' => 'Plan posiłku usunięty pomyślnie',
        ]);
    }
}
