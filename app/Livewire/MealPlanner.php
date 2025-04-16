<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MealPlan;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;

class MealPlanner extends Component
{
    public $date;
    public $startDate;
    public $endDate;
    public $mealPlans = [];
    public $dailyTotals = [];
    
    public $generatedPlan = null;
    public $selectedRecipe = null;
    public $mealType = 'breakfast';
    public $notes = '';
    public $loading = false;
    
    public $dietary = [];
    public $excludeIngredients = '';
    
    protected $spoonacularService;
    
    protected $listeners = ['dateSelected' => 'updateDate'];
    
    public function boot(SpoonacularService $spoonacularService)
    {
        $this->spoonacularService = $spoonacularService;
    }
    
    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        
        $this->loadMealPlans();
        
        // Jeśli użytkownik ma profil, załaduj ograniczenia dietetyczne
        $user = Auth::user();
        if ($user && $user->nutritionalProfile && is_array($user->nutritionalProfile->dietary_restrictions)) {
            $this->dietary = $user->nutritionalProfile->dietary_restrictions;
        }
        
        // Sprawdź, czy w URL jest parametr recipeId (dodawanie posiłku z kalkulatora diety)
        $recipeId = request()->query('recipeId');
        if ($recipeId) {
            \Illuminate\Support\Facades\Log::info('Wykryto parametr recipeId w URL', [
                'recipeId' => $recipeId
            ]);
            
            // Ustaw loading na true, aby pokazać użytkownikowi, że trwa ładowanie
            $this->loading = true;
            
            // Pobierz informacje o przepisie
            $recipe = $this->spoonacularService->getRecipeInformation((int)$recipeId);
            
            if ($recipe) {
                $recipe['name'] = $recipe['title'] ?? 'Przepis bez nazwy';
                $this->selectedRecipe = $recipe;
                \Illuminate\Support\Facades\Log::info('Pomyślnie załadowano przepis z parametru URL', [
                    'recipe_name' => $recipe['name']
                ]);
            } else {
                session()->flash('error', 'Nie udało się pobrać informacji o przepisie o ID: ' . $recipeId);
                \Illuminate\Support\Facades\Log::error('Błąd ładowania przepisu z parametru URL', [
                    'recipeId' => $recipeId
                ]);
            }
            
            $this->loading = false;
        }
    }
    
    public function updateDate($newDate)
    {
        $this->date = $newDate;
        $this->loadMealPlansForDate($newDate);
    }
    
    public function loadMealPlans()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }
        
        $this->mealPlans = MealPlan::getForDateRange($user->id, $this->startDate, $this->endDate);
        
        // Grupuj posiłki według dnia i obliczaj łączne wartości odżywcze
        $this->dailyTotals = MealPlan::getDailyTotalsForDateRange($user->id, $this->startDate, $this->endDate);
    }
    
    public function loadMealPlansForDate($date)
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }
        
        $this->mealPlans = MealPlan::where('user_id', $user->id)
            ->where('date', $date)
            ->orderBy('meal_type')
            ->get();
            
        $this->dailyTotals = [
            $date => MealPlan::getDailyTotals($user->id, $date)
        ];
    }
    
    public function generateMealPlan()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }
        
        $this->loading = true;
        
        $profile = $user->nutritionalProfile;
        
        if (!$profile || !$profile->target_calories) {
            session()->flash('error', 'Najpierw uzupełnij profil żywieniowy z docelową kalorycznością!');
            $this->loading = false;
            return;
        }
        
        $params = [];
        
        if (!empty($this->dietary)) {
            $params['diet'] = implode(',', $this->dietary);
        }
        
        if (!empty($this->excludeIngredients)) {
            $params['exclude'] = $this->excludeIngredients;
        }
        
        // Dodanie parametrów zwiększających różnorodność posiłków
        $params['addRecipeInformation'] = true;
        $params['fillIngredients'] = true;
        
        // Unikatowe posiłki w dniu
        $params['limitLicense'] = false;  // Pozwala uzyskać więcej różnorodnych wyników
        $params['sort'] = 'random';       // Losowe sortowanie wyników
        
        \Illuminate\Support\Facades\Log::info('Generowanie planu posiłków z dodatkowymi parametrami', [
            'target_calories' => $profile->target_calories,
            'params' => $params
        ]);
        
        $this->generatedPlan = $this->spoonacularService->generateMealPlan(
            $profile->target_calories,
            $params
        );
        
        $this->loading = false;
    }
    
    public function selectRecipe($recipeId, $recipeName)
    {
        $this->loading = true;
        
        $this->selectedRecipe = $this->spoonacularService->getRecipeInformation($recipeId);
        
        if ($this->selectedRecipe) {
            $this->selectedRecipe['name'] = $recipeName;
        } else {
            session()->flash('error', 'Nie udało się pobrać informacji o przepisie.');
        }
        
        $this->loading = false;
    }
    
    public function saveMealToPlan()
    {
        if (!$this->selectedRecipe) {
            session()->flash('error', 'Najpierw wybierz przepis!');
            return;
        }
        
        $user = Auth::user();
        if (!$user) {
            session()->flash('error', 'Musisz być zalogowany, aby dodawać posiłki do planu.');
            return;
        }
        
        try {
            \Illuminate\Support\Facades\Log::info('Rozpoczęcie dodawania posiłku do planu', [
                'user_id' => $user->id,
                'recipe_name' => $this->selectedRecipe['name'],
                'date' => $this->date,
                'meal_type' => $this->mealType,
                'has_nutrition' => isset($this->selectedRecipe['nutrition']),
                'nutrition_structure' => isset($this->selectedRecipe['nutrition']) ? array_keys($this->selectedRecipe['nutrition']) : [],
                'servings' => $this->selectedRecipe['servings'] ?? 1,
                'recipe_keys' => array_keys($this->selectedRecipe)
            ]);
            
            // Dodatkowy log struktury danych
            if (isset($this->selectedRecipe['nutrition'])) {
                \Illuminate\Support\Facades\Log::info('Struktura danych nutrition:', [
                    'nutrition_keys' => array_keys($this->selectedRecipe['nutrition']),
                    'has_nutrients' => isset($this->selectedRecipe['nutrition']['nutrients'])
                ]);
                
                if (isset($this->selectedRecipe['nutrition']['nutrients'])) {
                    $nutrientsCount = count($this->selectedRecipe['nutrition']['nutrients']);
                    \Illuminate\Support\Facades\Log::info('Struktura nutrients:', [
                        'count' => $nutrientsCount,
                        'sample' => $nutrientsCount > 0 ? 
                            array_slice($this->selectedRecipe['nutrition']['nutrients'], 0, min(5, $nutrientsCount)) : []
                    ]);
                }
            }
            
            // Pobierz wartości makroskładników
            $nutrients = $this->selectedRecipe['nutrition']['nutrients'] ?? [];
            \Illuminate\Support\Facades\Log::info('Tablica nutrients:', [
                'nutrients_count' => count($nutrients),
                'first_5' => array_slice($nutrients, 0, min(5, count($nutrients)))
            ]);
            
            $caloriesInfo = collect($nutrients)->firstWhere('name', 'Calories');
            $proteinInfo = collect($nutrients)->firstWhere('name', 'Protein');
            $carbsInfo = collect($nutrients)->firstWhere('name', 'Carbohydrates');
            $fatInfo = collect($nutrients)->firstWhere('name', 'Fat');
            
            // Oblicz wartości w przeliczeniu na porcję
            $servings = $this->selectedRecipe['servings'] ?? 1;
            
            // Sprawdź czy mamy liczbowe wartości, jeśli nie, spróbuj przekształcić tekstowe
            $calories = 0;
            $protein = 0;
            $carbs = 0;
            $fat = 0;
            
            if ($caloriesInfo && isset($caloriesInfo['amount'])) {
                $calories = is_numeric($caloriesInfo['amount']) ? 
                    (float)$caloriesInfo['amount'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $caloriesInfo['amount']));
            } elseif (isset($this->selectedRecipe['nutrition']['calories'])) {
                // Alternatywne sprawdzenie w innym miejscu struktury
                $calories = is_numeric($this->selectedRecipe['nutrition']['calories']) ? 
                    (float)$this->selectedRecipe['nutrition']['calories'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $this->selectedRecipe['nutrition']['calories']));
            }
            
            if ($proteinInfo && isset($proteinInfo['amount'])) {
                $protein = is_numeric($proteinInfo['amount']) ? 
                    (float)$proteinInfo['amount'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $proteinInfo['amount']));
            } elseif (isset($this->selectedRecipe['nutrition']['protein'])) {
                $protein = is_numeric($this->selectedRecipe['nutrition']['protein']) ? 
                    (float)$this->selectedRecipe['nutrition']['protein'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $this->selectedRecipe['nutrition']['protein']));
            }
            
            if ($carbsInfo && isset($carbsInfo['amount'])) {
                $carbs = is_numeric($carbsInfo['amount']) ? 
                    (float)$carbsInfo['amount'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $carbsInfo['amount']));
            } elseif (isset($this->selectedRecipe['nutrition']['carbs'])) {
                $carbs = is_numeric($this->selectedRecipe['nutrition']['carbs']) ? 
                    (float)$this->selectedRecipe['nutrition']['carbs'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $this->selectedRecipe['nutrition']['carbs']));
            }
            
            if ($fatInfo && isset($fatInfo['amount'])) {
                $fat = is_numeric($fatInfo['amount']) ? 
                    (float)$fatInfo['amount'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $fatInfo['amount']));
            } elseif (isset($this->selectedRecipe['nutrition']['fat'])) {
                $fat = is_numeric($this->selectedRecipe['nutrition']['fat']) ? 
                    (float)$this->selectedRecipe['nutrition']['fat'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $this->selectedRecipe['nutrition']['fat']));
            }
            
            // API zwraca wartości na całe danie, więc nie musimy mnożyć przez liczbę porcji
            // Zawartość wartości odżywczych jest zachowywana na porcję
            
            \Illuminate\Support\Facades\Log::info('Wartości odżywcze szczegółowo', [
                'calories_raw' => $caloriesInfo, 
                'protein_raw' => $proteinInfo,
                'carbs_raw' => $carbsInfo,
                'fat_raw' => $fatInfo,
                'calories_calculated' => $calories,
                'protein_calculated' => $protein,
                'carbs_calculated' => $carbs,
                'fat_calculated' => $fat,
                'servings' => $servings,
                'numeric_check' => [
                    'calories_is_numeric' => $caloriesInfo && isset($caloriesInfo['amount']) ? is_numeric($caloriesInfo['amount']) : false,
                    'protein_is_numeric' => $proteinInfo && isset($proteinInfo['amount']) ? is_numeric($proteinInfo['amount']) : false,
                    'carbs_is_numeric' => $carbsInfo && isset($carbsInfo['amount']) ? is_numeric($carbsInfo['amount']) : false,
                    'fat_is_numeric' => $fatInfo && isset($fatInfo['amount']) ? is_numeric($fatInfo['amount']) : false
                ]
            ]);
            
            // Dodatkowe sprawdzenie czy wartości są zerowe
            if ($calories <= 0 && $protein <= 0 && $carbs <= 0 && $fat <= 0) {
                // Spróbujmy znaleźć wartości odżywcze w innym miejscu struktury danych
                \Illuminate\Support\Facades\Log::info('Wykryto zerowe wartości odżywcze - próba znalezienia alternatywnych źródeł danych');
                
                // Szukamy w stringach w nutritionWidget
                if (isset($this->selectedRecipe['nutrition']['caloricBreakdown'])) {
                    \Illuminate\Support\Facades\Log::info('Znaleziono caloricBreakdown:', [
                        'data' => $this->selectedRecipe['nutrition']['caloricBreakdown']
                    ]);
                }
            }
            
            $mealPlan = new MealPlan([
                'user_id' => $user->id,
                'name' => $this->selectedRecipe['name'],
                'date' => $this->date,
                'meal_type' => $this->mealType,
                'recipe_data' => $this->selectedRecipe,
                'calories' => max(0, $calories),  // Upewniamy się, że nie zapisujemy wartości ujemnych
                'protein' => max(0, $protein),
                'carbs' => max(0, $carbs),
                'fat' => max(0, $fat),
                'notes' => $this->notes,
            ]);
            
            $result = $mealPlan->save();
            
            \Illuminate\Support\Facades\Log::info('Wynik zapisywania posiłku', [
                'success' => $result,
                'meal_plan_id' => $mealPlan->id,
                'saved_data' => [
                    'calories' => $mealPlan->calories,
                    'protein' => $mealPlan->protein,
                    'carbs' => $mealPlan->carbs,
                    'fat' => $mealPlan->fat
                ]
            ]);
            
            if (!$result) {
                throw new \Exception('Nie udało się zapisać planu posiłku');
            }
            
            session()->flash('message', 'Przepis został dodany do planu posiłków!');
            
            $this->selectedRecipe = null;
            $this->notes = '';
            
            $this->loadMealPlansForDate($this->date);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Błąd przy zapisywaniu posiłku do planu', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Wystąpił błąd przy dodawaniu posiłku: ' . $e->getMessage());
        }
    }
    
    public function deleteMealPlan($id)
    {
        $mealPlan = MealPlan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$mealPlan) {
            session()->flash('error', 'Nie znaleziono planu posiłku.');
            return;
        }
        
        $mealPlan->delete();
        
        session()->flash('message', 'Plan posiłku został usunięty.');
        
        $this->loadMealPlansForDate($this->date);
    }
    
    public function toggleFavorite($id)
    {
        $mealPlan = MealPlan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$mealPlan) {
            return;
        }
        
        $mealPlan->is_favorite = !$mealPlan->is_favorite;
        $mealPlan->save();
        
        $this->loadMealPlansForDate($this->date);
    }
    
    public function nextWeek()
    {
        $this->startDate = Carbon::parse($this->startDate)->addWeek()->format('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->addWeek()->format('Y-m-d');
        $this->loadMealPlans();
    }
    
    public function prevWeek()
    {
        $this->startDate = Carbon::parse($this->startDate)->subWeek()->format('Y-m-d');
        $this->endDate = Carbon::parse($this->endDate)->subWeek()->format('Y-m-d');
        $this->loadMealPlans();
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.meal-planner');
    }
}
