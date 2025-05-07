<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MealPlan;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\App;
use App\Exceptions\ApiException;
use Throwable;

/**
 * Meal planning component that integrates with Spoonacular API
 * Allows users to create, manage and track daily meal plans
 */
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
    
    protected $listeners = ['dateSelected' => 'updateDate', 'switch-locale' => 'handleLanguageChange'];
    
    public function boot(SpoonacularService $spoonacularService)
    {
        $this->spoonacularService = $spoonacularService;
    }
    
    public function mount()
    {
        $this->date = Carbon::now()->format('Y-m-d');
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        
        // Load meal plans only if user is logged in
        if (Auth::check()) {
            $this->loadMealPlans();
            
            // Load dietary restrictions if user has a profile
            $user = Auth::user();
            if ($user && $user->nutritionalProfile && is_array($user->nutritionalProfile->dietary_restrictions)) {
                $this->dietary = $user->nutritionalProfile->dietary_restrictions;
            }
        }
        
        // Check if recipeId parameter exists in URL (adding meal from nutrition calculator)
        $recipeId = request()->query('recipeId');
        if ($recipeId && Auth::check()) {
            \Illuminate\Support\Facades\Log::info('Recipe ID detected in URL', [
                'recipeId' => $recipeId
            ]);
            
            // Set loading to true to show user that loading is in progress
            $this->loading = true;
            
            try {
                // Get recipe information
                $recipe = $this->spoonacularService->getRecipeInformation((int)$recipeId);
                
                if ($recipe) {
                    $recipe['name'] = $recipe['title'] ?? 'Unnamed Recipe';
                    $this->selectedRecipe = $recipe;
                    \Illuminate\Support\Facades\Log::info('Successfully loaded recipe from URL parameter', [
                        'recipe_name' => $recipe['name']
                    ]);
                }
            } catch (ApiException $e) {
                session()->flash('error', 'Error retrieving recipe: ' . $e->getMessage());
                \Illuminate\Support\Facades\Log::error('API error loading recipe from URL parameter', [
                    'recipeId' => $recipeId,
                    'message' => $e->getMessage(),
                    'service' => $e->getServiceName(),
                    'endpoint' => $e->getEndpoint(),
                    'status_code' => $e->getStatusCode() 
                ]);
            } catch (Throwable $e) {
                session()->flash('error', 'Could not retrieve recipe information for ID: ' . $recipeId);
                \Illuminate\Support\Facades\Log::error('Error loading recipe from URL parameter', [
                    'recipeId' => $recipeId,
                    'error' => $e->getMessage()
                ]);
            } finally {
                $this->loading = false;
            }
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
        
        // Group meals by day and calculate total nutritional values
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
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('meal_planner.login_required')]);
            return;
        }
        
        $user = Auth::user();
        
        // Check if user has a nutritional profile
        $profile = $user->nutritionalProfile;
        if (!$profile) {
            session()->flash('error', __('meal_planner.profile_required'));
            return;
        }
        
        // Check if profile has target calories
        if (!$profile->target_calories) {
            session()->flash('error', __('meal_planner.calories_required'));
            return;
        }
        
        $this->loading = true;
        
        $params = [];
        
        if (!empty($this->dietary)) {
            $params['diet'] = implode(',', $this->dietary);
        }
        
        if (!empty($this->excludeIngredients)) {
            $params['exclude'] = $this->excludeIngredients;
        }
        
        // Add parameters to increase meal variety
        $params['addRecipeInformation'] = true;
        $params['fillIngredients'] = true;
        
        // Unique meals in a day
        $params['limitLicense'] = false;  // Allows more diverse results
        $params['sort'] = 'random';       // Random sorting of results
        
        \Illuminate\Support\Facades\Log::info('Generating meal plan with additional parameters', [
            'target_calories' => $profile->target_calories,
            'params' => $params
        ]);
        
        try {
            $this->generatedPlan = $this->spoonacularService->generateMealPlan(
                $profile->target_calories,
                $params
            );
        } catch (ApiException $e) {
            \Illuminate\Support\Facades\Log::error('API error generating meal plan', [
                'message' => $e->getMessage(),
                'service' => $e->getServiceName(),
                'endpoint' => $e->getEndpoint(),
                'status_code' => $e->getStatusCode()
            ]);
            
            session()->flash('error', 'Could not generate meal plan: ' . $e->getMessage());
        } catch (Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error generating meal plan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Could not generate meal plan: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }
    
    public function selectRecipe($recipeId, $recipeName)
    {
        $this->loading = true;
        
        try {
            $this->selectedRecipe = $this->spoonacularService->getRecipeInformation($recipeId);
            
            if ($this->selectedRecipe) {
                $this->selectedRecipe['name'] = $recipeName;
            }
        } catch (ApiException $e) {
            \Illuminate\Support\Facades\Log::error('API error retrieving recipe information', [
                'recipe_id' => $recipeId,
                'message' => $e->getMessage(),
                'service' => $e->getServiceName(),
                'endpoint' => $e->getEndpoint(),
                'status_code' => $e->getStatusCode() 
            ]);
            
            session()->flash('error', 'Failed to retrieve recipe information: ' . $e->getMessage());
        } catch (Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error retrieving recipe information', [
                'recipe_id' => $recipeId,
                'error' => $e->getMessage()
            ]);
            
            session()->flash('error', 'Failed to retrieve recipe information: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }
    
    public function saveMealToPlan()
    {
        if (!$this->selectedRecipe) {
            session()->flash('error', 'Please select a recipe first!');
            return;
        }
        
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('meal_planner.login_required')]);
            return;
        }
        
        $user = Auth::user();
        
        try {
            \Illuminate\Support\Facades\Log::info('Starting to add meal to plan', [
                'user_id' => $user->id,
                'recipe_name' => $this->selectedRecipe['name'],
                'date' => $this->date,
                'meal_type' => $this->mealType,
                'has_nutrition' => isset($this->selectedRecipe['nutrition']),
                'nutrition_structure' => isset($this->selectedRecipe['nutrition']) ? array_keys($this->selectedRecipe['nutrition']) : [],
                'servings' => $this->selectedRecipe['servings'] ?? 1,
                'recipe_keys' => array_keys($this->selectedRecipe)
            ]);
            
            // Additional log of data structure
            if (isset($this->selectedRecipe['nutrition'])) {
                \Illuminate\Support\Facades\Log::info('Nutrition data structure:', [
                    'nutrition_keys' => array_keys($this->selectedRecipe['nutrition']),
                    'has_nutrients' => isset($this->selectedRecipe['nutrition']['nutrients'])
                ]);
                
                if (isset($this->selectedRecipe['nutrition']['nutrients'])) {
                    $nutrientsCount = count($this->selectedRecipe['nutrition']['nutrients']);
                    \Illuminate\Support\Facades\Log::info('Nutrients structure:', [
                        'count' => $nutrientsCount,
                        'sample' => $nutrientsCount > 0 ? 
                            array_slice($this->selectedRecipe['nutrition']['nutrients'], 0, min(5, $nutrientsCount)) : []
                    ]);
                }
            }
            
            // Get macronutrient values
            $nutrients = $this->selectedRecipe['nutrition']['nutrients'] ?? [];
            \Illuminate\Support\Facades\Log::info('Nutrients array:', [
                'nutrients_count' => count($nutrients),
                'first_5' => array_slice($nutrients, 0, min(5, count($nutrients)))
            ]);
            
            $caloriesInfo = collect($nutrients)->firstWhere('name', 'Calories');
            $proteinInfo = collect($nutrients)->firstWhere('name', 'Protein');
            $carbsInfo = collect($nutrients)->firstWhere('name', 'Carbohydrates');
            $fatInfo = collect($nutrients)->firstWhere('name', 'Fat');
            
            // Calculate values per serving
            $servings = $this->selectedRecipe['servings'] ?? 1;
            
            // Check if we have numeric values, if not, try to transform text values
            $calories = 0;
            $protein = 0;
            $carbs = 0;
            $fat = 0;
            
            if ($caloriesInfo && isset($caloriesInfo['amount'])) {
                $calories = is_numeric($caloriesInfo['amount']) ? 
                    (float)$caloriesInfo['amount'] : 
                    floatval(preg_replace('/[^0-9.]/', '', $caloriesInfo['amount']));
            } elseif (isset($this->selectedRecipe['nutrition']['calories'])) {
                // Alternative check in another part of the structure
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
            
            // API returns values for the entire dish, so we don't need to multiply by the number of servings
            // Nutritional values are saved per serving
            
            \Illuminate\Support\Facades\Log::info('Detailed nutritional values', [
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
            
            // Additional check if values are zero
            if ($calories <= 0 && $protein <= 0 && $carbs <= 0 && $fat <= 0) {
                // Try to find nutritional values elsewhere in the data structure
                \Illuminate\Support\Facades\Log::info('Zero nutritional values detected - trying to find alternative data sources');
                
                // Look in strings in nutritionWidget
                if (isset($this->selectedRecipe['nutrition']['caloricBreakdown'])) {
                    \Illuminate\Support\Facades\Log::info('Found caloricBreakdown:', [
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
                'calories' => max(0, $calories),  // Ensure we don't save negative values
                'protein' => max(0, $protein),
                'carbs' => max(0, $carbs),
                'fat' => max(0, $fat),
                'notes' => $this->notes,
            ]);
            
            $result = $mealPlan->save();
            
            \Illuminate\Support\Facades\Log::info('Meal saving result', [
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
                throw new \Exception('Failed to save meal plan');
            }
            
            session()->flash('message', 'Recipe has been added to your meal plan!');
            
            $this->selectedRecipe = null;
            $this->notes = '';
            
            $this->loadMealPlansForDate($this->date);
        } catch (Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Error saving meal to plan', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'An error occurred while adding the meal: ' . $e->getMessage());
        }
    }
    
    public function deleteMealPlan($id)
    {
        $mealPlan = MealPlan::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$mealPlan) {
            session()->flash('error', 'Meal plan not found.');
            return;
        }
        
        $mealPlan->delete();
        
        session()->flash('message', 'Meal plan has been deleted.');
        
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
    
    public function handleLanguageChange($locale)
    {
        // Force re-render when language changes
        $this->dispatch('$refresh');
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.meal-planner');
    }
}
