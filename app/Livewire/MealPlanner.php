<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\SpoonacularService;
use App\Services\DeepLTranslateService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MealPlanner extends Component
{
    // Calendar properties
    public $currentWeekStart;
    public $selectedDate;
    
    // Saved plans
    public $savedPlans = [];
    
    // Generated meals
    public $generatedMeals = [];
    
    // Selected recipe for details
    public $selectedRecipe = null;
    
    // Search
    public $searchQuery = '';
    public $searchResults = [];
    public $searchLoading = false;
    
    // Translations
    public $translatedIngredients = [];
    public $translatedInstructions = '';
    

    
    public function mount()
    {
        // Start calendar from today instead of beginning of week
        $this->currentWeekStart = Carbon::now();
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->loadSavedPlans();
    }
    
    public function previousWeek()
    {
        $newWeekStart = $this->currentWeekStart->copy()->subWeek();
        
        // Prevent navigating to weeks that start before today
        if ($newWeekStart->lt(Carbon::now())) {
            return; // Don't allow going to past weeks
        }
        
        $this->currentWeekStart = $newWeekStart;
        $this->loadSavedPlans();
    }
    
    public function nextWeek()
    {
        $this->currentWeekStart = $this->currentWeekStart->addWeek();
        $this->loadSavedPlans();
    }
    
    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }
    
    public function updatedSelectedDate()
    {
        // Update week if selected date is outside of the current week
        $selectedCarbon = Carbon::parse($this->selectedDate);
        $weekStart = $selectedCarbon->copy()->startOfWeek();
        
        if (!$weekStart->isSameWeek($this->currentWeekStart)) {
            $this->currentWeekStart = $weekStart;
            $this->loadSavedPlans();
        }
    }
    
    public function generateMealPlan()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->flash('error', __('meal_planner.login_required'));
            return;
        }
        
        // Check if user has nutritional profile
        $user = Auth::user();
        $profile = $user->nutritionalProfile;
        
        if (!$profile || !$profile->target_calories) {
            session()->flash('error', __('meal_planner.profile_required'));
            return;
        }
        
        if (!$this->selectedDate) {
            session()->flash('error', __('meal_planner.select_day_first'));
            return;
        }
        
        // Always clear previous generated meals to ensure fresh generation
        $this->generatedMeals = [];
        
        try {
            $spoonacularService = app(SpoonacularService::class);
            
            // Build search parameters based on user's nutritional profile
            $searchParams = [];
            
            // Set calorie target per meal (divide daily calories by 3 meals)
            $caloriesPerMeal = round($profile->target_calories / 3);
            $searchParams['maxCalories'] = $caloriesPerMeal + 200; // Add some flexibility
            $searchParams['minCalories'] = max(200, $caloriesPerMeal - 200);
            
            // Add dietary restrictions if any
            if ($profile->dietary_restrictions && is_array($profile->dietary_restrictions)) {
                $excludeTags = [];
                $includeTags = [];
                
                foreach ($profile->dietary_restrictions as $restriction) {
                    switch ($restriction) {
                        case 'vegetarian':
                            $includeTags[] = 'vegetarian';
                            break;
                        case 'vegan':
                            $includeTags[] = 'vegan';
                            break;
                        case 'gluten_free':
                            $includeTags[] = 'gluten free';
                            break;
                        case 'dairy_free':
                            $includeTags[] = 'dairy free';
                            break;
                        case 'keto':
                            $includeTags[] = 'ketogenic';
                            break;
                    }
                }
                
                if (!empty($includeTags)) {
                    $searchParams['includeTags'] = implode(',', $includeTags);
                }
            }
            
            // Generate recipes based on user profile
            $recipes = $spoonacularService->getRandomRecipes(3, $searchParams);
            
            if (isset($recipes['recipes']) && count($recipes['recipes']) > 0) {
                foreach ($recipes['recipes'] as $recipe) {
                    // Get detailed recipe information
                    $detailedRecipe = $spoonacularService->getRecipeInformation($recipe['id']);
                    
                    if ($detailedRecipe) {
                        // Add basic nutrition values
                        $nutrition = $this->extractNutrition($detailedRecipe);
                        $detailedRecipe['nutrition'] = $nutrition;
                        
                        $this->generatedMeals[] = $detailedRecipe;
                    }
                }
                
                session()->flash('success', __('meal_planner.plan_generated_with_profile'));
            } else {
                session()->flash('error', __('meal_planner.generation_failed'));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('meal_planner.api_error') . ': ' . $e->getMessage());
        }
    }
    
    public function savePlanToDate($date)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->flash('error', __('meal_planner.login_required'));
            return;
        }
        
        if (empty($this->generatedMeals)) {
            session()->flash('error', __('meal_planner.no_plan_to_save'));
            return;
        }
        
        // Save plan to JSON file with user prefix
        $userId = Auth::id();
        $this->savedPlans[$date] = $this->generatedMeals;
        $this->savePlansToFile();
        
        session()->flash('success', __('meal_planner.plan_saved'));
        
        // Clear generated plan
        $this->generatedMeals = [];
    }
    
    public function deletePlanFromDate($date)
    {
        if (isset($this->savedPlans[$date])) {
            unset($this->savedPlans[$date]);
            $this->savePlansToFile();
            session()->flash('success', __('meal_planner.plan_deleted'));
        }
    }
    
    public function removeMealFromPlan($date, $index)
    {
        if (isset($this->savedPlans[$date][$index])) {
            unset($this->savedPlans[$date][$index]);
            
            // Re-index array to prevent gaps
            $this->savedPlans[$date] = array_values($this->savedPlans[$date]);
            
            // If no meals left for this date, remove the date entry entirely
            if (empty($this->savedPlans[$date])) {
                unset($this->savedPlans[$date]);
            }
            
            $this->savePlansToFile();
            session()->flash('success', __('meal_planner.meal_removed'));
        }
    }
    
    public function addRecipeToPlan($recipeId)
    {
        // Check if user is logged in
        if (!Auth::check()) {
            session()->flash('error', __('meal_planner.login_required'));
            return;
        }
        
        if (!$this->selectedDate) {
            session()->flash('error', __('meal_planner.select_day_first'));
            return;
        }
        
        try {
            $spoonacularService = app(SpoonacularService::class);
            $recipe = $spoonacularService->getRecipeInformation($recipeId);
            
            if ($recipe) {
                // Add nutrition values
                $nutrition = $this->extractNutrition($recipe);
                $recipe['nutrition'] = $nutrition;
                
                // Initialize saved plans for the date if it doesn't exist
                if (!isset($this->savedPlans[$this->selectedDate])) {
                    $this->savedPlans[$this->selectedDate] = [];
                }
                
                // Add recipe to the selected date's plan
                $this->savedPlans[$this->selectedDate][] = $recipe;
                $this->savePlansToFile();
                
                session()->flash('success', __('meal_planner.recipe_added_to_plan'));
                
                // Close recipe details if open
                $this->selectedRecipe = null;
            } else {
                session()->flash('error', __('meal_planner.recipe_load_error'));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('meal_planner.recipe_load_error') . ': ' . $e->getMessage());
        }
    }
    
    public function viewRecipeDetails($recipeId)
    {
        try {
            $spoonacularService = app(SpoonacularService::class);
            $recipe = $spoonacularService->getRecipeInformation($recipeId);
            
            if ($recipe) {
                // Add nutrition values
                $nutrition = $this->extractNutrition($recipe);
                $recipe['nutrition'] = $nutrition;
                
                $this->selectedRecipe = $recipe;
                
                // Reset translations
                $this->translatedIngredients = [];
                $this->translatedInstructions = '';
            }
        } catch (\Exception $e) {
            session()->flash('error', __('meal_planner.recipe_load_error') . ': ' . $e->getMessage());
        }
    }
    
    public function searchRecipes()
    {
        if (empty($this->searchQuery)) {
            return;
        }
        
        $this->searchLoading = true;
        
        try {
            $spoonacularService = app(SpoonacularService::class);
            $results = $spoonacularService->searchRecipes($this->searchQuery);
            $this->searchResults = $results['results'] ?? [];
        } catch (\Exception $e) {
            session()->flash('error', __('meal_planner.search_error') . ': ' . $e->getMessage());
            $this->searchResults = [];
        } finally {
            $this->searchLoading = false;
        }
    }
    
    public function translateIngredients()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['extendedIngredients'])) {
            return;
        }
        
        try {
            $translateService = app(DeepLTranslateService::class);
            $ingredients = [];
            foreach ($this->selectedRecipe['extendedIngredients'] as $ingredient) {
                $ingredients[] = $ingredient['original'];
            }
            
            $translatedText = $translateService->translate(implode("\n", $ingredients), 'en', 'pl');
            $this->translatedIngredients = explode("\n", $translatedText);
        } catch (\Exception $e) {
            session()->flash('error', __('meal_planner.translation_error') . ': ' . $e->getMessage());
        }
    }
    
    public function translateInstructions()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['instructions'])) {
            return;
        }
        
        try {
            $translateService = app(DeepLTranslateService::class);
            $this->translatedInstructions = $translateService->translate(
                strip_tags($this->selectedRecipe['instructions']), 
                'en', 
                'pl'
            );
        } catch (\Exception $e) {
            session()->flash('error', __('meal_planner.translation_error') . ': ' . $e->getMessage());
        }
    }
    
    private function loadSavedPlans()
    {
        if (!Auth::check()) {
            $this->savedPlans = [];
            return;
        }
        
        $userId = Auth::id();
        $filename = "meal_plans_user_{$userId}.json";
        
        if (Storage::exists($filename)) {
            $data = Storage::get($filename);
            $this->savedPlans = json_decode($data, true) ?? [];
        } else {
            $this->savedPlans = [];
        }
    }
    
    private function savePlansToFile()
    {
        if (!Auth::check()) {
            return;
        }
        
        $userId = Auth::id();
        $filename = "meal_plans_user_{$userId}.json";
        Storage::put($filename, json_encode($this->savedPlans, JSON_PRETTY_PRINT));
    }
    
    private function extractNutrition($recipe)
    {
        $nutrition = [
            'calories' => 0,
            'protein' => 0,
            'carbs' => 0,
            'fat' => 0
        ];
        
        if (isset($recipe['nutrition']['nutrients'])) {
            foreach ($recipe['nutrition']['nutrients'] as $nutrient) {
                switch ($nutrient['name']) {
                    case 'Calories':
                        $nutrition['calories'] = $nutrient['amount'];
                        break;
                    case 'Protein':
                        $nutrition['protein'] = $nutrient['amount'];
                        break;
                    case 'Carbohydrates':
                        $nutrition['carbs'] = $nutrient['amount'];
                        break;
                    case 'Fat':
                        $nutrition['fat'] = $nutrient['amount'];
                        break;
                }
            }
        }
        
        return $nutrition;
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.meal-planner');
    }
}
