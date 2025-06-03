<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use App\Services\DeepLTranslateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\App;
use App\Exceptions\ApiException;
use Throwable;
use App\Services\LogService;

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
    
    public function boot(SpoonacularService $spoonacularService, DeepLTranslateService $translateService)
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
        
        session()->flash('success', __('nutrition_calculator.profile_saved'));
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
            $this->loading = false;
        }
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
        
        try {
            // Look for recipe in search results first to avoid additional API call
            $recipeFound = false;
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
                        try {
                            $fullRecipe = $this->spoonacularService->getRecipeInformation($recipeId);
                            if ($fullRecipe) {
                                $this->selectedRecipe = $fullRecipe;
                                $recipeFound = true;
                            } else {
                                $this->selectedRecipe = $recipe;
                                $recipeFound = true;
                            }
                        } catch (ApiException $e) {
                            app(LogService::class)->exception($e, 'API error fetching full recipe details');
                            $this->selectedRecipe = $recipe; // Use limited data from search results
                            $recipeFound = true;
                        }
                        
                        break;
                    }
                }
            }
            
            // If not found in search results, fetch from API
            if (!$recipeFound) {
                try {
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
                        $recipeFound = true;
                    }
                } catch (ApiException $e) {
                    app(LogService::class)->exception($e, 'API error fetching recipe details');
                    session()->flash('error', __('nutrition_calculator.recipe_api_error'));
                }
            }
            
            if ($recipeFound) {
                // First show the modal
                $this->showRecipeModal = true;
                
                // If language is Polish, set translation flag
                // Actual translation will start after modal is fully loaded (in JS)
                if ($this->autoTranslate) {
                    $this->translateRecipe = true;
                }
            } else {
                session()->flash('error', __('nutrition_calculator.recipe_fetch_error'));
            }
        } catch (Throwable $e) {
            app(LogService::class)->exception($e, 'Error retrieving recipe details');
            session()->flash('error', __('nutrition_calculator.recipe_fetch_error'));
        } finally {
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
        
        // Check if DeepL API is available and working
        $useDeepL = !empty(config('services.deepl.key')) && config('services.deepl.key') !== 'your_deepl_api_key_here';
        
        if ($useDeepL) {
            try {
                // Add timeout handling - if translation takes more than 15 seconds, use original
                $startTime = microtime(true);
                
                $this->translatedTitle = $this->translateService->translate($this->selectedRecipe['title'], 'en', 'pl', 'text');
                
                $duration = microtime(true) - $startTime;
                app(LogService::class)->info('Title translation completed', [
                    'duration' => $duration,
                    'title_length' => strlen($this->selectedRecipe['title'])
                ]);
                
                // Notify interface that title has been translated and show it
                $this->dispatch('titleTranslated');
                
                // Move on to translating instructions
                $this->translateInstructions();
                return;
            } catch (ApiException $e) {
                app(LogService::class)->exception($e, 'API error translating recipe title - falling back to dictionary');
                // Fall through to fallback translation
            } catch (Throwable $e) {
                app(LogService::class)->exception($e, 'Error translating recipe title - falling back to dictionary');
                // Fall through to fallback translation
            }
        }
        
        // Use fallback translation
        $this->translatedTitle = $this->fallbackTranslate($this->selectedRecipe['title']);
        $this->dispatch('titleTranslated');
        $this->translateInstructions();
    }
    
    /**
     * Simple fallback translation for common cooking terms
     */
    private function fallbackTranslate($text)
    {
        $dictionary = [
            // Mięso i ryby
            'chicken' => 'kurczak',
            'chicken breast' => 'pierś z kurczaka',
            'chicken thigh' => 'udko kurczaka',
            'beef' => 'wołowina',
            'pork' => 'wieprzowina',
            'fish' => 'ryba',
            'salmon' => 'łosoś',
            'tuna' => 'tuńczyk',
            'cod' => 'dorsz',
            'turkey' => 'indyk',
            'lamb' => 'jagnięcina',
            'bacon' => 'bekon',
            'ham' => 'szynka',
            'sausage' => 'kiełbasa',
            
            // Węglowodany
            'pasta' => 'makaron',
            'spaghetti' => 'spaghetti',
            'noodles' => 'makaron',
            'rice' => 'ryż',
            'bread' => 'chleb',
            'flour' => 'mąka',
            'oats' => 'owies',
            'quinoa' => 'komosa ryżowa',
            'barley' => 'jęczmień',
            
            // Nabiał
            'milk' => 'mleko',
            'butter' => 'masło',
            'cheese' => 'ser',
            'cream' => 'śmietana',
            'yogurt' => 'jogurt',
            'egg' => 'jajko',
            'eggs' => 'jajka',
            
            // Warzywa
            'onion' => 'cebula',
            'garlic' => 'czosnek',
            'tomato' => 'pomidor',
            'tomatoes' => 'pomidory',
            'potato' => 'ziemniak',
            'potatoes' => 'ziemniaki',
            'carrot' => 'marchewka',
            'carrots' => 'marchewki',
            'cabbage' => 'kapusta',
            'broccoli' => 'brokuły',
            'spinach' => 'szpinak',
            'lettuce' => 'sałata',
            'cucumber' => 'ogórek',
            'pepper' => 'papryka',
            'peppers' => 'papryka',
            'mushroom' => 'grzyb',
            'mushrooms' => 'grzyby',
            'zucchini' => 'cukinia',
            'eggplant' => 'bakłażan',
            'celery' => 'seler',
            'leek' => 'por',
            'corn' => 'kukurydza',
            'peas' => 'groszek',
            'beans' => 'fasola',
            
            // Owoce
            'apple' => 'jabłko',
            'apples' => 'jabłka',
            'banana' => 'banan',
            'orange' => 'pomarańcza',
            'lemon' => 'cytryna',
            'lime' => 'limonka',
            'strawberry' => 'truskawka',
            'strawberries' => 'truskawki',
            'blueberry' => 'jagoda',
            'blueberries' => 'jagody',
            'grape' => 'winogrono',
            'grapes' => 'winogrona',
            
            // Przyprawy i zioła
            'salt' => 'sól',
            'pepper' => 'pieprz',
            'sugar' => 'cukier',
            'honey' => 'miód',
            'oil' => 'olej',
            'olive oil' => 'oliwa z oliwek',
            'vinegar' => 'ocet',
            'basil' => 'bazylia',
            'oregano' => 'oregano',
            'thyme' => 'tymianek',
            'rosemary' => 'rozmaryn',
            'parsley' => 'pietruszka',
            'dill' => 'koper',
            'ginger' => 'imbir',
            'paprika' => 'papryka',
            'cumin' => 'kminek',
            'cinnamon' => 'cynamon',
            
            // Jednostki miary
            'cup' => 'szklanka',
            'cups' => 'szklanki',
            'tablespoon' => 'łyżka stołowa',
            'tablespoons' => 'łyżki stołowe',
            'teaspoon' => 'łyżeczka',
            'teaspoons' => 'łyżeczki',
            'ounce' => 'uncja',
            'ounces' => 'uncje',
            'pound' => 'funt',
            'pounds' => 'funty',
            'gram' => 'gram',
            'grams' => 'gramy',
            'kilogram' => 'kilogram',
            'liter' => 'litr',
            'milliliter' => 'mililitr',
            'piece' => 'sztuka',
            'pieces' => 'sztuki',
            'slice' => 'plaster',
            'slices' => 'plastry',
            'clove' => 'ząbek',
            'cloves' => 'ząbki',
            
            // Metody gotowania
            'baked' => 'pieczony',
            'grilled' => 'grillowany',
            'fried' => 'smażony',
            'roasted' => 'pieczony',
            'steamed' => 'gotowany na parze',
            'boiled' => 'gotowany',
            'sauteed' => 'duszony',
            'braised' => 'duszony',
            
            // Potrawy
            'soup' => 'zupa',
            'salad' => 'sałatka',
            'sandwich' => 'kanapka',
            'pizza' => 'pizza',
            'cake' => 'ciasto',
            'bread' => 'chleb',
            'recipe' => 'przepis',
            'recipes' => 'przepisy',
            'meal' => 'posiłek',
            'breakfast' => 'śniadanie',
            'lunch' => 'obiad',
            'dinner' => 'kolacja',
            'snack' => 'przekąska',
            
            // Przymiotniki
            'fresh' => 'świeży',
            'dried' => 'suszony',
            'frozen' => 'mrożony',
            'raw' => 'surowy',
            'cooked' => 'gotowany',
            'spicy' => 'pikantny',
            'sweet' => 'słodki',
            'sour' => 'kwaśny',
            'salty' => 'słony',
            'bitter' => 'gorzki',
            'delicious' => 'pyszny',
            'tasty' => 'smaczny',
            'hot' => 'gorący',
            'cold' => 'zimny',
            'warm' => 'ciepły',
            'large' => 'duży',
            'small' => 'mały',
            'medium' => 'średni',
            
            // Słowa łączące
            'with' => 'z',
            'and' => 'i',
            'or' => 'lub',
            'of' => 'z',
            'in' => 'w',
            'on' => 'na',
            'for' => 'dla',
            'to' => 'do',
            'from' => 'z',
            'into' => 'do',
            'over' => 'nad',
            'under' => 'pod',
            'about' => 'około',
            'without' => 'bez'
        ];
        
        $words = explode(' ', strtolower($text));
        $translatedWords = [];
        
        foreach ($words as $word) {
            // Remove punctuation for lookup
            $cleanWord = preg_replace('/[^\w]/', '', $word);
            if (isset($dictionary[$cleanWord])) {
                $translatedWords[] = $dictionary[$cleanWord];
            } else {
                $translatedWords[] = $word; // Keep original if not found
            }
        }
        
        $result = implode(' ', $translatedWords);
        return ucfirst($result); // Capitalize first letter
    }
    
    public function translateInstructions()
    {
        if (!$this->selectedRecipe) {
            // Move to ingredients
            $this->translateIngredients();
            return;
        }
        
        $this->dispatch('translatingInstructions');
        
        // Check if DeepL API is available and working
        $useDeepL = !empty(config('services.deepl.key')) && config('services.deepl.key') !== 'your_deepl_api_key_here';
        
        try {
            if (isset($this->selectedRecipe['instructions']) && !empty($this->selectedRecipe['instructions'])) {
                if ($useDeepL) {
                    try {
                        $this->translatedInstructions = $this->translateService->translate($this->selectedRecipe['instructions'], 'en', 'pl', 'html');
                        
                        // Notify interface that instructions have been translated
                        $this->dispatch('instructionsTranslated');
                        
                        // Move to ingredients
                        $this->translateIngredients();
                        return;
                    } catch (ApiException $e) {
                        app(LogService::class)->exception($e, 'API error translating recipe instructions - using original');
                        $this->translatedInstructions = $this->selectedRecipe['instructions']; // Use original
                    } catch (Throwable $e) {
                        app(LogService::class)->exception($e, 'Error translating recipe instructions - using original');
                        $this->translatedInstructions = $this->selectedRecipe['instructions']; // Use original
                    }
                } else {
                    // No DeepL, use original
                    $this->translatedInstructions = $this->selectedRecipe['instructions'];
                }
                
                // Notify interface that instructions have been translated (or kept original)
                $this->dispatch('instructionsTranslated');
                
                // Move to ingredients regardless of success
                $this->translateIngredients();
            } elseif (isset($this->selectedRecipe['analyzedInstructions']) && is_array($this->selectedRecipe['analyzedInstructions']) && 
                    count($this->selectedRecipe['analyzedInstructions']) > 0 && isset($this->selectedRecipe['analyzedInstructions'][0]['steps'])) {
                
                $steps = [];
                foreach ($this->selectedRecipe['analyzedInstructions'][0]['steps'] as $step) {
                    $steps[] = $step['step'];
                }
                
                $translatedSteps = [];
                
                if ($useDeepL) {
                    // Translate steps individually to avoid blocking interface
                    foreach ($steps as $index => $step) {
                        try {
                            $translatedStep = $this->translateService->translate($step, 'en', 'pl', 'text');
                            if ($translatedStep) {
                                $translatedSteps[$index] = $translatedStep;
                            } else {
                                $translatedSteps[$index] = $step;
                            }
                        } catch (ApiException $e) {
                            app(LogService::class)->exception($e, 'API error translating recipe step ' . ($index + 1));
                            $translatedSteps[$index] = $step; // Use original text
                        } catch (Throwable $e) {
                            $translatedSteps[$index] = $step; // Use original text
                        }
                    }
                } else {
                    // No DeepL, use original steps
                    $translatedSteps = $steps;
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
        } catch (Throwable $e) {
            app(LogService::class)->exception($e, 'Unexpected error translating instructions');
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
        
        // Check if DeepL API is available and working
        $useDeepL = !empty(config('services.deepl.key')) && config('services.deepl.key') !== 'your_deepl_api_key_here';
        
        try {
            $ingredients = $this->selectedRecipe['extendedIngredients'];
            
            // Translate ingredients individually
            foreach ($ingredients as $index => $ingredient) {
                $originalText = $ingredient['original'] ?? ($ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['name']);
                
                if ($useDeepL) {
                    try {
                        $translated = $this->translateService->translate($originalText, 'en', 'pl', 'text');
                        if ($translated) {
                            $this->translatedIngredients[$index] = $translated;
                        } else {
                            $this->translatedIngredients[$index] = $originalText;
                        }
                    } catch (ApiException $e) {
                        app(LogService::class)->exception($e, 'API error translating ingredient ' . ($index + 1) . ' - using fallback');
                        $this->translatedIngredients[$index] = $this->fallbackTranslate($originalText);
                    } catch (Throwable $e) {
                        app(LogService::class)->exception($e, 'Error translating ingredient ' . ($index + 1) . ' - using fallback');
                        $this->translatedIngredients[$index] = $this->fallbackTranslate($originalText);
                    }
                } else {
                    // No DeepL, use fallback translation
                    $this->translatedIngredients[$index] = $this->fallbackTranslate($originalText);
                }
            }
            
            // Notify interface that ingredients have been translated
            $this->dispatch('ingredientsTranslated');
            
            // Finish translation process
            $this->finishTranslation();
        } catch (Throwable $e) {
            app(LogService::class)->exception($e, 'Error translating ingredients');
            $this->finishTranslation(); // Finish even with errors
        }
    }
    
    private function finishTranslation()
    {
        // Set the flag that translation is complete
        $this->dispatch('translationComplete');
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