<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\SpoonacularService;
use App\Services\DeepLTranslateService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

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
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->loadSavedPlans();
    }
    
    public function previousWeek()
    {
        $newWeekStart = $this->currentWeekStart->copy()->subWeek();
        
        // Prevent navigating to weeks that start before today
        if ($newWeekStart->startOfWeek()->lt(Carbon::now()->startOfWeek())) {
            return; // Don't allow going to previous weeks
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
        if (!$this->selectedDate) {
            session()->flash('error', __('meal_planner.select_day_first'));
            return;
        }
        
        try {
            $spoonacularService = app(SpoonacularService::class);
            
            // Generate 3 random recipes
            $recipes = $spoonacularService->getRandomRecipes(3);
            
            if (isset($recipes['recipes']) && count($recipes['recipes']) > 0) {
                $this->generatedMeals = [];
                
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
                
                session()->flash('success', __('meal_planner.plan_generated'));
            } else {
                session()->flash('error', __('meal_planner.generation_failed'));
            }
        } catch (\Exception $e) {
            session()->flash('error', __('meal_planner.api_error') . ': ' . $e->getMessage());
        }
    }
    
    public function savePlanToDate($date)
    {
        if (empty($this->generatedMeals)) {
            session()->flash('error', __('meal_planner.no_plan_to_save'));
            return;
        }
        
        // Save plan to JSON file
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
    
    public function addRecipeToPlan($recipeId)
    {
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
        if (Storage::exists('meal_plans.json')) {
            $data = Storage::get('meal_plans.json');
            $this->savedPlans = json_decode($data, true) ?? [];
        } else {
            $this->savedPlans = [];
        }
    }
    
    private function savePlansToFile()
    {
        Storage::put('meal_plans.json', json_encode($this->savedPlans, JSON_PRETTY_PRINT));
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
