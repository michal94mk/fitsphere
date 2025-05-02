<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use App\Services\LibreTranslateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\App;

/**
 * Nutrition calculator component with recipe search functionality
 * Provides BMI calculation, calorie needs, and macronutrient recommendations
 */
class NutritionCalculator extends Component
{
    public $age;
    public $gender;
    public $weight;
    public $height;
    public $activityLevel;
    public $goal;
    public $dietaryRestrictions = [];
    
    public $bmi;
    public $dailyCalories;
    public $protein;
    public $carbs;
    public $fat;
    
    public $searchQuery = '';
    public $searchResults = [];
    public $loading = false;
    public $dietFilters = null;
    public $intolerances = null;
    public $maxCalories = null;
    public $showDietaryInfo = false;
    
    public $selectedRecipe = null;
    public $showRecipeModal = false;
    public $translateRecipe = false;
    public $translatedInstructions = null;
    public $translatedIngredients = [];
    public $translatedTitle = null;
    public $autoTranslate = false;
    
    protected $spoonacularService;
    protected $translateService;
    
    protected $listeners = [
        'switch-locale' => 'handleLanguageChange',
        'startTranslation' => 'performTranslation'
    ];
    
    public function boot(SpoonacularService $spoonacularService, LibreTranslateService $translateService)
    {
        $this->spoonacularService = $spoonacularService;
        $this->translateService = $translateService;
        // Enable auto-translation when language is Polish
        $this->autoTranslate = App::getLocale() === 'pl';
    }
    
    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }
        
        $profile = $user->nutritionalProfile;
        
        if ($profile) {
            $this->age = $profile->age;
            $this->gender = $profile->gender;
            $this->weight = $profile->weight;
            $this->height = $profile->height;
            $this->activityLevel = $profile->activity_level;
            $this->goal = $profile->goal;
            $this->dietaryRestrictions = $profile->dietary_restrictions ?? [];
            
            // Only show dietary info if all required fields are filled
            if ($this->weight && $this->height && $this->age && $this->gender && $this->activityLevel) {
                $this->showDietaryInfo = true;
                
                // Create temporary profile for calculations
                $tempProfile = new NutritionalProfile([
                    'age' => $this->age,
                    'gender' => $this->gender,
                    'weight' => $this->weight,
                    'height' => $this->height,
                    'activity_level' => $this->activityLevel,
                    'goal' => $this->goal,
                ]);
                
                $this->bmi = $tempProfile->calculateBMI();
                $this->dailyCalories = $tempProfile->calculateDailyCalories();
                
                // Calculate macronutrients
                $this->protein = round(($this->dailyCalories * 0.30) / 4, 0); // 4 calories per gram of protein
                $this->carbs = round(($this->dailyCalories * 0.40) / 4, 0);   // 4 calories per gram of carbs
                $this->fat = round(($this->dailyCalories * 0.30) / 9, 0);     // 9 calories per gram of fat
            }
        }
    }
    
    public function calculateNutrition()
    {
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('nutrition_calculator.login_required')]);
            return;
        }

        if (!$this->weight || !$this->height || !$this->age || !$this->gender || !$this->activityLevel) {
            session()->flash('error', __('nutrition_calculator.profile_error'));
            return;
        }
        
        // Create temporary profile for calculations
        $profile = new NutritionalProfile([
            'age' => $this->age,
            'gender' => $this->gender,
            'weight' => $this->weight,
            'height' => $this->height,
            'activity_level' => $this->activityLevel,
            'goal' => $this->goal,
        ]);
        
        $this->bmi = $profile->calculateBMI();
        $this->dailyCalories = $profile->calculateDailyCalories();
        
        // Calculate macronutrients
        $this->protein = round(($this->dailyCalories * 0.30) / 4, 0); // 4 calories per gram of protein
        $this->carbs = round(($this->dailyCalories * 0.40) / 4, 0);   // 4 calories per gram of carbs
        $this->fat = round(($this->dailyCalories * 0.30) / 9, 0);     // 9 calories per gram of fat
        
        $this->showDietaryInfo = true;
    }
    
    public function saveProfile()
    {
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('nutrition_calculator.login_required')]);
            return;
        }
        
        // Validate required fields
        if (!$this->weight || !$this->height || !$this->age || !$this->gender || !$this->activityLevel) {
            session()->flash('error', __('nutrition_calculator.profile_error'));
            return;
        }
        
        $user = Auth::user();
        
        $profile = $user->nutritionalProfile;
        
        if (!$profile) {
            $profile = new NutritionalProfile(['user_id' => $user->id]);
        }
        
        $profile->age = $this->age;
        $profile->gender = $this->gender;
        $profile->weight = $this->weight;
        $profile->height = $this->height;
        $profile->activity_level = $this->activityLevel;
        $profile->goal = $this->goal;
        $profile->dietary_restrictions = $this->dietaryRestrictions;
        
        if ($this->dailyCalories) {
            $profile->target_calories = $this->dailyCalories;
            $profile->target_protein = $this->protein;
            $profile->target_carbs = $this->carbs;
            $profile->target_fat = $this->fat;
        }
        
        $profile->save();
        
        session()->flash('message', __('nutrition_calculator.profile_saved'));
    }
    
    public function searchRecipes()
    {
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('nutrition_calculator.login_required')]);
            return;
        }
        
        if (empty($this->searchQuery)) {
            session()->flash('search_error', __('nutrition_calculator.search_error'));
            return;
        }
        
        $this->loading = true;
        
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
        $user = Auth::user();
        if ($user && $user->nutritionalProfile && !empty($user->nutritionalProfile->dietary_restrictions)) {
            if (!isset($params['intolerances'])) {
                $params['intolerances'] = implode(',', $user->nutritionalProfile->dietary_restrictions);
            }
        }
        
        // Save original query for comparison
        $originalQuery = $this->searchQuery;
        $searchTerm = $originalQuery;
        
        // For Polish users, attempt to translate the query to English for better search results
        // since Spoonacular primarily has English content
        $wasTranslated = false;
        
        if (App::getLocale() === 'pl') {
            // Check if we have access to translation API
            if (config('services.libretranslate.key') || config('services.libretranslate.url') !== 'https://libretranslate.com') {
                $translatedQuery = $this->translateService->translate($originalQuery, 'pl', 'en');
                
                if ($translatedQuery && $translatedQuery !== $originalQuery) {
                    $searchTerm = $translatedQuery;
                    $wasTranslated = true;
                }
            } else {
                // As a fallback, try using Spoonacular's translation
                $spoonacularTranslated = $this->spoonacularService->translate($originalQuery, 'pl', 'en');
                if ($spoonacularTranslated && $spoonacularTranslated !== $originalQuery) {
                    $searchTerm = $spoonacularTranslated;
                    $wasTranslated = true;
                }
            }
        }
        
        // Perform the search with the potentially translated term
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
        $this->loading = false;
    }
    
    public function viewRecipeDetails($recipeId)
    {
        if (!Auth::check()) {
            $this->dispatch('login-required', ['message' => __('nutrition_calculator.login_required')]);
            return;
        }
        
        $this->loading = true;
        
        // Reset translation state
        $this->translateRecipe = false;
        $this->translatedInstructions = null;
        $this->translatedIngredients = [];
        $this->translatedTitle = null;
        
        // Look for recipe in search results first to avoid additional API call
        if (!empty($this->searchResults) && isset($this->searchResults['results'])) {
            foreach ($this->searchResults['results'] as $recipe) {
                if ($recipe['id'] == $recipeId) {
                    // Debug the structure of the recipe data
                    \Illuminate\Support\Facades\Log::info('Recipe data from search results:', [
                        'recipe_id' => $recipe['id'],
                        'title' => $recipe['title'],
                        'has_instructions' => isset($recipe['instructions']),
                        'has_analyzed_instructions' => isset($recipe['analyzedInstructions']),
                        'has_extended_ingredients' => isset($recipe['extendedIngredients']),
                        'data_keys' => array_keys($recipe)
                    ]);
                    
                    // For search results, we might need to fetch complete data
                    // Since search results might not include all details we need
                    $fullRecipe = $this->spoonacularService->getRecipeInformation($recipeId);
                    if ($fullRecipe) {
                        $this->selectedRecipe = $fullRecipe;
                    } else {
                        $this->selectedRecipe = $recipe;
                    }
                    
                    // First show the modal
                    $this->showRecipeModal = true;
                    $this->loading = false;
                    
                    // If language is Polish, set translation flag
                    // Actual translation will start after modal is fully loaded (in JS)
                    if ($this->autoTranslate) {
                        $this->translateRecipe = true;
                    }
                    
                    return;
                }
            }
        }
        
        // If not found in search results, fetch from API
        $recipe = $this->spoonacularService->getRecipeInformation($recipeId);
        
        if ($recipe) {
            // Debug the structure of the recipe data from direct API call
            \Illuminate\Support\Facades\Log::info('Recipe data from direct API call:', [
                'recipe_id' => $recipe['id'],
                'title' => $recipe['title'],
                'has_instructions' => isset($recipe['instructions']),
                'has_analyzed_instructions' => isset($recipe['analyzedInstructions']),
                'has_extended_ingredients' => isset($recipe['extendedIngredients']),
                'data_keys' => array_keys($recipe)
            ]);
            
            $this->selectedRecipe = $recipe;
            
            // First show the modal
            $this->showRecipeModal = true;
            $this->loading = false;
            
            // If language is Polish, set translation flag
            // Actual translation will start after modal is fully loaded (in JS)
            if ($this->autoTranslate) {
                $this->translateRecipe = true;
            }
        } else {
            session()->flash('error', __('nutrition_calculator.recipe_fetch_error'));
            $this->loading = false;
        }
    }
    
    public function startSequentialTranslation()
    {
        $this->performTranslation();
    }
    
    public function performTranslation()
    {
        if (!$this->selectedRecipe) {
            return;
        }
        
        // Notify interface that translation is starting
        $this->dispatch('translationStarted');
        
        // Translate title first as it's most important
        $this->translateTitle();
    }
    
    public function translateTitle()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['title']) || empty($this->selectedRecipe['title'])) {
            // Go directly to translating instructions
            $this->translateInstructions();
            return;
        }
        
        $this->dispatch('translatingTitle');
        
        try {
            $this->translatedTitle = $this->translateService->translate($this->selectedRecipe['title'], 'en', 'pl', 'text');
            
            // Notify interface that title has been translated and show it
            $this->dispatch('titleTranslated');
            
            // Move on to translating instructions
            $this->translateInstructions();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error translating title: ' . $e->getMessage());
            $this->translatedTitle = $this->selectedRecipe['title']; // Use original title
            $this->translateInstructions(); // Continue with instructions
        }
    }
    
    public function translateInstructions()
    {
        if (!$this->selectedRecipe) {
            // Move to ingredients
            $this->translateIngredients();
            return;
        }
        
        $this->dispatch('translatingInstructions');
        
        try {
            if (isset($this->selectedRecipe['instructions']) && !empty($this->selectedRecipe['instructions'])) {
                $this->translatedInstructions = $this->translateService->translate($this->selectedRecipe['instructions'], 'en', 'pl', 'html');
                
                // Notify interface that instructions have been translated
                $this->dispatch('instructionsTranslated');
                
                // Move to ingredients
                $this->translateIngredients();
            } elseif (isset($this->selectedRecipe['analyzedInstructions']) && is_array($this->selectedRecipe['analyzedInstructions']) && 
                      count($this->selectedRecipe['analyzedInstructions']) > 0 && isset($this->selectedRecipe['analyzedInstructions'][0]['steps'])) {
                
                $steps = [];
                foreach ($this->selectedRecipe['analyzedInstructions'][0]['steps'] as $step) {
                    $steps[] = $step['step'];
                }
                
                $translatedSteps = [];
                
                // Translate steps individually to avoid blocking interface
                foreach ($steps as $index => $step) {
                    try {
                        $translatedStep = $this->translateService->translate($step, 'en', 'pl', 'text');
                        if ($translatedStep) {
                            $translatedSteps[$index] = $translatedStep;
                        } else {
                            $translatedSteps[$index] = $step;
                        }
                    } catch (\Exception $e) {
                        $translatedSteps[$index] = $step; // Use original text
                    }
                }
                
                // Format steps as HTML list
                $htmlList = '<ol class="list-decimal pl-5 space-y-2">';
                foreach ($translatedSteps as $step) {
                    $htmlList .= '<li class="text-gray-700">' . htmlspecialchars($step) . '</li>';
                }
                $htmlList .= '</ol>';
                
                $this->translatedInstructions = $htmlList;
                
                // Notify interface that instructions have been translated
                $this->dispatch('instructionsTranslated');
                
                // Move to ingredients
                $this->translateIngredients();
            } else {
                // No instructions, move to ingredients
                $this->translateIngredients();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error translating instructions: ' . $e->getMessage());
            // In case of error, move to ingredients
            $this->translateIngredients();
        }
    }
    
    public function translateIngredients()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['extendedIngredients']) || !is_array($this->selectedRecipe['extendedIngredients'])) {
            // Finish translation
            $this->finishTranslation();
            return;
        }
        
        $this->dispatch('translatingIngredients');
        
        try {
            $ingredients = $this->selectedRecipe['extendedIngredients'];
            
            // Translate ingredients individually
            foreach ($ingredients as $index => $ingredient) {
                $originalText = $ingredient['original'] ?? ($ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['name']);
                
                try {
                    $translated = $this->translateService->translate($originalText, 'en', 'pl', 'text');
                    if ($translated) {
                        $this->translatedIngredients[$index] = $translated;
                    } else {
                        $this->translatedIngredients[$index] = $originalText;
                    }
                } catch (\Exception $e) {
                    $this->translatedIngredients[$index] = $originalText;
                }
            }
            
            // Notify interface that ingredients have been translated
            $this->dispatch('ingredientsTranslated');
            
            // Finish translation process
            $this->finishTranslation();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error translating ingredients: ' . $e->getMessage());
            $this->finishTranslation(); // Finish even with errors
        }
    }
    
    private function finishTranslation()
    {
        // Set the flag that translation is complete
        $this->dispatch('translationCompleted');
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
            
            // Notify JS that we've toggled back to original language
            $this->dispatch('translationToggled', $this->translateRecipe);
        }
    }
    
    public function handleLanguageChange($locale)
    {
        // Update auto-translate setting based on new locale
        $this->autoTranslate = $locale === 'pl';
        
        // If recipe modal is open and new locale is Polish, trigger translation
        if ($this->showRecipeModal && $this->selectedRecipe && $locale === 'pl' && !$this->translateRecipe) {
            $this->translateRecipe = true;
            $this->performTranslation();
        }
    }
    
    public function closeRecipeModal()
    {
        $this->showRecipeModal = false;
        $this->selectedRecipe = null;
        $this->translateRecipe = false;
        $this->translatedInstructions = null;
        $this->translatedIngredients = [];
        $this->translatedTitle = null;
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.nutrition-calculator');
    }
}
