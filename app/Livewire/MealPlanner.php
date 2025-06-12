<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MealPlan;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use App\Services\DeepLTranslateService;
use App\Services\LogService;
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
    public $servingSize = 1;
    public $loading = false;
    public $searchLoading = false;
    
    // Dietary preferences
    public $dietary = [];
    public $excludeIngredients = '';
    
    // Recipe search properties
    public $searchQuery = '';
    public $searchResults = [];
    public $dietFilters = null;
    public $intolerances = null;
    public $maxCalories = null;
    
    // Translation properties
    public $translateRecipe = false;
    public $translatedInstructions = null;
    public $translatedIngredients = [];
    public $translatedTitle = null;
    
    // Loading states for recipe details
    public $loadingRecipeDetails = false;
    public $titleLoading = false;
    public $instructionsLoading = false;
    public $ingredientsLoading = false;
    
    // Flag to indicate if viewing a saved meal (already in plan)
    public $viewingSavedMeal = false;
    
    protected $spoonacularService;
    protected $translateService;
    
    protected $listeners = ['dateSelected' => 'updateDate', 'switch-locale' => 'handleLanguageChange'];
    
    public function boot(SpoonacularService $spoonacularService, DeepLTranslateService $translateService)
    {
        $this->spoonacularService = $spoonacularService;
        $this->translateService = $translateService;
    }
    
    public function mount()
    {
        // Initialize all loading states first
        $this->loadingRecipeDetails = false;
        $this->titleLoading = false;
        $this->instructionsLoading = false;
        $this->ingredientsLoading = false;
        $this->loading = false;
        
        $this->date = Carbon::now()->format('Y-m-d');
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        
        // Load meal plans only if user or trainer is logged in
        if (Auth::check()) {
            $this->loadMealPlans();
            
            // Load dietary restrictions if user has a profile
            $authenticatedUser = Auth::user();
            if ($authenticatedUser && $authenticatedUser->nutritionalProfile && is_array($authenticatedUser->nutritionalProfile->dietary_restrictions)) {
                $this->dietary = $authenticatedUser->nutritionalProfile->dietary_restrictions;
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
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            return;
        }
        
        $this->mealPlans = MealPlan::getForDateRange($authenticatedUser->id, $this->startDate, $this->endDate);
        
        // Group meals by day and calculate total nutritional values
        $this->dailyTotals = MealPlan::getDailyTotalsForDateRange($authenticatedUser->id, $this->startDate, $this->endDate);
    }
    
    public function loadMealPlansForDate($date)
    {
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            return;
        }
        
        $this->mealPlans = MealPlan::where('user_id', $authenticatedUser->id)
            ->where('date', $date)
            ->orderBy('meal_type')
            ->get();
            
        $this->dailyTotals = [
            $date => MealPlan::getDailyTotals($authenticatedUser->id, $date)
        ];
    }
    
    public function generateMealPlan()
    {
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('meal_planner.login_required')]);
            return;
        }
        
        $authenticatedUser = Auth::user();
        
        // Check if user has a nutritional profile
        $profile = $authenticatedUser->nutritionalProfile;
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
            
            // Log image URLs for debugging
            if ($this->generatedPlan && isset($this->generatedPlan['meals'])) {
                \Illuminate\Support\Facades\Log::info('Generated meal plan images:', [
                    'meals' => collect($this->generatedPlan['meals'])->map(function ($meal) {
                        return [
                            'id' => $meal['id'] ?? 'unknown',
                            'title' => $meal['title'] ?? 'unknown',
                            'image' => $meal['image'] ?? 'no image',
                            'has_image' => isset($meal['image']) && !empty($meal['image'])
                        ];
                    })->toArray()
                ]);
            }
            
            // Add success message when plan is generated
            if ($this->generatedPlan && isset($this->generatedPlan['meals']) && count($this->generatedPlan['meals']) > 0) {
                session()->flash('plan_generated', __('meal_planner.plan_generated_success'));
            }
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
        \Illuminate\Support\Facades\Log::info('selectRecipe called', [
            'recipeId' => $recipeId,
            'recipeName' => $recipeName,
            'user_id' => Auth::id()
        ]);
        
        if (!Auth::check()) {
            session()->flash('error', __('meal_planner.login_required'));
            return;
        }
        
        $this->loadingRecipeDetails = true;
        $this->loading = false; // Turn off general loading
        
        // Reset translation state
        $this->translateRecipe = false;
        $this->translatedTitle = null;
        $this->translatedInstructions = null;
        $this->translatedIngredients = [];
        
        // Reset individual loading states
        $this->titleLoading = false;
        $this->instructionsLoading = false;
        $this->ingredientsLoading = false;
        
        // Reset flag - we're viewing a new recipe, not a saved meal
        $this->viewingSavedMeal = false;
        
        try {
            $newRecipe = $this->spoonacularService->getRecipeInformation($recipeId);
            
            if ($newRecipe) {
                $newRecipe['name'] = $recipeName;
                
                // Only update selectedRecipe after successful load
                $this->selectedRecipe = $newRecipe;
                
                // Log detailed recipe structure for debugging
                \Illuminate\Support\Facades\Log::info('Recipe selected successfully', [
                    'recipe_id' => $recipeId,
                    'recipe_name' => $recipeName,
                    'recipe_keys' => array_keys($newRecipe),
                    'has_image' => isset($newRecipe['image']),
                    'has_nutrition' => isset($newRecipe['nutrition']),
                    'has_ingredients' => isset($newRecipe['extendedIngredients']),
                    'has_instructions' => isset($newRecipe['instructions']) || isset($newRecipe['analyzedInstructions']),
                    'servings' => $newRecipe['servings'] ?? 'not set',
                    'ready_in_minutes' => $newRecipe['readyInMinutes'] ?? 'not set'
                ]);
                
                // Auto-translate if Polish locale is active
                if (app()->getLocale() === 'pl') {
                    $this->translateRecipe = true;
                    $this->performTranslation();
                }
            } else {
                \Illuminate\Support\Facades\Log::warning('Recipe API returned null/empty response', [
                    'recipe_id' => $recipeId,
                    'recipe_name' => $recipeName
                ]);
                session()->flash('error', 'Recipe not found or empty response from API');
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
            $this->loadingRecipeDetails = false;
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
        
        $authenticatedUser = Auth::user();
        
        try {
            \Illuminate\Support\Facades\Log::info('Starting to add meal to plan', [
                'user_id' => $authenticatedUser->id,
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
            $scaledServing = $this->servingSize ?? 1;
            
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
                'user_id' => $authenticatedUser->id,
                'name' => $this->selectedRecipe['name'],
                'date' => $this->date,
                'meal_type' => $this->mealType,
                'recipe_data' => $this->selectedRecipe,
                'calories' => max(0, $calories * $scaledServing),  // Scale by serving size
                'protein' => max(0, $protein * $scaledServing),
                'carbs' => max(0, $carbs * $scaledServing),
                'fat' => max(0, $fat * $scaledServing),
                'notes' => $this->notes,
                'serving_size' => $scaledServing,  // Store the serving size used
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
            
            // Reset recipe selection and translation state
            $this->closeRecipeDetails();
            
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
        \Illuminate\Support\Facades\Log::info('deleteMealPlan called', [
            'id' => $id,
            'user_id' => Auth::id()
        ]);
        
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            session()->flash('error', 'You must be logged in to delete meals.');
            return;
        }
        
        $mealPlan = MealPlan::where('id', $id)
            ->where('user_id', $authenticatedUser->id)
            ->first();
        
        if (!$mealPlan) {
            session()->flash('error', 'Meal plan not found.');
            return;
        }
        
        $mealPlan->delete();
        
        session()->flash('message', 'Meal plan has been deleted.');
        
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
        
        // If recipe is selected and new locale is Polish, trigger translation
        if ($this->selectedRecipe && $locale === 'pl' && !$this->translateRecipe) {
            $this->translateRecipe = true;
            $this->performTranslation();
        }
    }
    
    public function toggleRecipeTranslation()
    {
        $this->translateRecipe = !$this->translateRecipe;
        
        if ($this->translateRecipe) {
            // Start translation sequence
            $this->performTranslation();
        } else {
            // Reset all translations to show original content
            $this->translatedTitle = null;
            $this->translatedInstructions = null;
            $this->translatedIngredients = [];
        }
    }
    
    public function performTranslation()
    {
        if (!$this->selectedRecipe) {
            return;
        }
        
        // Start with title translation
        $this->translateTitle();
    }
    
    public function translateTitle()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['name'])) {
            $this->translateInstructions();
            return;
        }
        
        // Start title loading
        $this->titleLoading = true;
        $this->dispatch('translatingTitle');
        
        // Check if DeepL API is available
        $useDeepL = !empty(config('services.deepl.key')) && config('services.deepl.key') !== 'your_deepl_api_key_here';
        
        try {
            if ($useDeepL) {
                $this->translatedTitle = $this->translateService->translate($this->selectedRecipe['name'], 'en', 'pl', 'text');
            } else {
                $this->translatedTitle = $this->fallbackTranslate($this->selectedRecipe['name']);
            }
        } catch (Throwable $e) {
            app(LogService::class)->exception($e, 'Error translating recipe title');
            $this->translatedTitle = $this->selectedRecipe['name'];
        }
        
        // Stop title loading
        $this->titleLoading = false;
        $this->dispatch('titleTranslated');
        
        // Move to instructions
        $this->translateInstructions();
    }
    
    public function translateInstructions()
    {
        if (!$this->selectedRecipe) {
            $this->translateIngredients();
            return;
        }
        
        // Start instructions loading
        $this->instructionsLoading = true;
        $this->dispatch('translatingInstructions');
        
        // Check if DeepL API is available
        $useDeepL = !empty(config('services.deepl.key')) && config('services.deepl.key') !== 'your_deepl_api_key_here';
        
        try {
            if (isset($this->selectedRecipe['instructions']) && !empty($this->selectedRecipe['instructions'])) {
                if ($useDeepL) {
                    try {
                        $this->translatedInstructions = $this->translateService->translate($this->selectedRecipe['instructions'], 'en', 'pl', 'html');
                    } catch (ApiException $e) {
                        app(LogService::class)->exception($e, 'API error translating recipe instructions - using original');
                        $this->translatedInstructions = $this->selectedRecipe['instructions'];
                    }
                } else {
                    $this->translatedInstructions = $this->selectedRecipe['instructions'];
                }
                
                // Stop instructions loading
                $this->instructionsLoading = false;
                $this->dispatch('instructionsTranslated');
                
                $this->translateIngredients();
            } elseif (isset($this->selectedRecipe['analyzedInstructions']) && is_array($this->selectedRecipe['analyzedInstructions']) && 
                    count($this->selectedRecipe['analyzedInstructions']) > 0 && isset($this->selectedRecipe['analyzedInstructions'][0]['steps'])) {
                
                $steps = [];
                foreach ($this->selectedRecipe['analyzedInstructions'][0]['steps'] as $step) {
                    $steps[] = $step['step'];
                }
                
                $translatedSteps = [];
                
                if ($useDeepL) {
                    foreach ($steps as $index => $step) {
                        try {
                            $translatedStep = $this->translateService->translate($step, 'en', 'pl', 'text');
                            $translatedSteps[$index] = $translatedStep ?: $step;
                        } catch (Throwable $e) {
                            $translatedSteps[$index] = $step;
                        }
                    }
                } else {
                    $translatedSteps = $steps;
                }
                
                // Format steps as HTML list
                $htmlList = '<ol class="list-decimal pl-5 space-y-2">';
                foreach ($translatedSteps as $step) {
                    $htmlList .= '<li class="text-gray-700">' . htmlspecialchars($step) . '</li>';
                }
                $htmlList .= '</ol>';
                
                $this->translatedInstructions = $htmlList;
                
                // Stop instructions loading
                $this->instructionsLoading = false;
                $this->dispatch('instructionsTranslated');
                
                $this->translateIngredients();
            } else {
                // Stop instructions loading
                $this->instructionsLoading = false;
                $this->dispatch('instructionsTranslated');
                
                $this->translateIngredients();
            }
        } catch (Throwable $e) {
            app(LogService::class)->exception($e, 'Error translating instructions');
            
            // Stop instructions loading
            $this->instructionsLoading = false;
            $this->dispatch('instructionsTranslated');
            
            $this->translateIngredients();
        }
    }
    
    public function translateIngredients()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['extendedIngredients']) || !is_array($this->selectedRecipe['extendedIngredients'])) {
            return;
        }
        
        // Start ingredients loading
        $this->ingredientsLoading = true;
        $this->dispatch('translatingIngredients');
        
        // Check if DeepL API is available
        $useDeepL = !empty(config('services.deepl.key')) && config('services.deepl.key') !== 'your_deepl_api_key_here';
        
        try {
            $ingredients = $this->selectedRecipe['extendedIngredients'];
            
            foreach ($ingredients as $index => $ingredient) {
                $originalText = $ingredient['original'] ?? ($ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['name']);
                
                if ($useDeepL) {
                    try {
                        $translated = $this->translateService->translate($originalText, 'en', 'pl', 'text');
                        $this->translatedIngredients[$index] = $translated ?: $this->fallbackTranslate($originalText);
                    } catch (Throwable $e) {
                        $this->translatedIngredients[$index] = $this->fallbackTranslate($originalText);
                    }
                } else {
                    $this->translatedIngredients[$index] = $this->fallbackTranslate($originalText);
                }
            }
        } catch (Throwable $e) {
            app(LogService::class)->exception($e, 'Error translating ingredients');
        }
        
        // Stop ingredients loading
        $this->ingredientsLoading = false;
        $this->dispatch('ingredientsTranslated');
    }
    
    private function fallbackTranslate($text)
    {
        // Simple fallback translation using SpoonacularService dictionary
        try {
            return $this->spoonacularService->translate($text, 'en', 'pl');
        } catch (Throwable $e) {
            return $text; // Return original if translation fails
        }
    }
    

    
    public function closeRecipeDetails()
    {
        $this->selectedRecipe = null;
        $this->viewingSavedMeal = false;
        $this->mealType = 'breakfast';
        $this->notes = '';
        $this->servingSize = 1;
        
        // Reset translation states
        $this->translateRecipe = false;
        $this->translatedTitle = null;
        $this->translatedInstructions = null;
        $this->translatedIngredients = [];
        
        // Reset loading states
        $this->loadingRecipeDetails = false;
        $this->titleLoading = false;
        $this->instructionsLoading = false;
        $this->ingredientsLoading = false;
    }
    
    public function viewSavedMealDetails($mealId)
    {
        $authenticatedUser = Auth::user();
        if (!$authenticatedUser) {
            $this->dispatch('login-required', ['message' => __('meal_planner.login_required')]);
            return;
        }
        
        // Start loading
        $this->loadingRecipeDetails = true;
        
        // Find the meal plan
        $mealPlan = MealPlan::where('id', $mealId)
            ->where('user_id', $authenticatedUser->id)
            ->first();
            
        if (!$mealPlan) {
            $this->loadingRecipeDetails = false;
            session()->flash('error', 'Meal plan not found.');
            return;
        }
        
        // Check if recipe data is available
        if (!$mealPlan->recipe_data || !is_array($mealPlan->recipe_data)) {
            $this->loadingRecipeDetails = false;
            session()->flash('error', 'Recipe details not available for this meal.');
            return;
        }
        
        // Reset translation state
        $this->translateRecipe = false;
        $this->translatedTitle = null;
        $this->translatedInstructions = null;
        $this->translatedIngredients = [];
        
        // Reset loading states
        $this->titleLoading = false;
        $this->instructionsLoading = false;
        $this->ingredientsLoading = false;
        
        // Set flag to indicate we're viewing a saved meal
        $this->viewingSavedMeal = true;
        
        // Set the selected recipe from saved meal data
        $this->selectedRecipe = $mealPlan->recipe_data;
        $this->selectedRecipe['name'] = $mealPlan->name;
        
        // Stop main loading
        $this->loadingRecipeDetails = false;
        
        // Auto-translate if Polish locale is active
        if (app()->getLocale() === 'pl') {
            $this->translateRecipe = true;
            $this->performTranslation();
        }
    }
    
    public function searchRecipes()
    {
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('meal_planner.login_required')]);
            return;
        }
        
        if (empty($this->searchQuery)) {
            session()->flash('search_error', __('nutrition_calculator.search_error'));
            return;
        }
        
        $this->searchLoading = true;
        
        $params = [];
        
        if ($this->dietFilters) {
            $params['diet'] = $this->dietFilters;
        }
        
        if ($this->intolerances) {
            $params['intolerances'] = $this->intolerances;
        }
        
        if ($this->maxCalories) {
            $params['maxCalories'] = $this->maxCalories;
        }
        
        // If user has a profile with dietary restrictions
        $authenticatedUser = Auth::user();
        if ($authenticatedUser && $authenticatedUser->nutritionalProfile && !empty($authenticatedUser->nutritionalProfile->dietary_restrictions)) {
            if (!isset($params['intolerances'])) {
                $params['intolerances'] = implode(',', $authenticatedUser->nutritionalProfile->dietary_restrictions);
            }
        }
        
        // Save original query for comparison
        $originalQuery = $this->searchQuery;
        $searchTerm = $originalQuery;
        
        // For Polish users, attempt to translate the query to English for better search results
        $wasTranslated = false;
        
        try {
            if (App::getLocale() === 'pl') {
                // Check if we have access to translation API
                if (config('services.deepl.key')) {
                    try {
                        $translatedQuery = $this->translateService->translate($originalQuery, 'pl', 'en');
                        
                        if ($translatedQuery && $translatedQuery !== $originalQuery) {
                            $searchTerm = $translatedQuery;
                            $wasTranslated = true;
                        }
                    } catch (ApiException $e) {
                        app(LogService::class)->exception($e, 'Translation API error during recipe search');
                        // Falls back to Spoonacular translation
                    } catch (Throwable $e) {
                        app(LogService::class)->exception($e, 'Error translating search query');
                    }
                }
                
                // As a fallback, try using Spoonacular's translation
                if (!$wasTranslated) {
                    try {
                        $spoonacularTranslated = $this->spoonacularService->translate($originalQuery, 'pl', 'en');
                        if ($spoonacularTranslated && $spoonacularTranslated !== $originalQuery) {
                            $searchTerm = $spoonacularTranslated;
                            $wasTranslated = true;
                        }
                    } catch (ApiException $e) {
                        app(LogService::class)->exception($e, 'Spoonacular translation error during recipe search');
                        // Continue with original query
                    } catch (Throwable $e) {
                        app(LogService::class)->exception($e, 'Error translating search query with Spoonacular');
                    }
                }
            }
            
            // Perform the search with the potentially translated term
            try {
                $results = $this->spoonacularService->searchRecipes($searchTerm, $params);
                
                // If search was translated, show the translated term to the user
                if ($wasTranslated) {
                    // Only show translation information if the website is in Polish
                    if (App::getLocale() === 'pl') {
                        $message = __('nutrition_calculator.translation_detail', [
                            'original' => $originalQuery,
                            'translated' => $searchTerm
                        ]);
                        session()->flash('info', $message);
                    }
                }
                
                $this->searchResults = $results;
            } catch (ApiException $e) {
                app(LogService::class)->exception($e, 'API error during recipe search');
                session()->flash('error', __('nutrition_calculator.search_api_error'));
                $this->searchResults = ['results' => []];
            }
        } catch (Throwable $e) {
            app(LogService::class)->exception($e, 'Unexpected error during recipe search');
            session()->flash('error', __('nutrition_calculator.search_error_general'));
            $this->searchResults = ['results' => []];
        } finally {
            $this->searchLoading = false;
        }
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.meal-planner');
    }
}
