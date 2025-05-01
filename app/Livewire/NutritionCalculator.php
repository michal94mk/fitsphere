<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\NutritionalProfile;
use App\Services\SpoonacularService;
use App\Services\LibreTranslateService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\App;

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
        
        // Tworzenie tymczasowego obiektu profilu do obliczeń
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
        
        // Obliczanie makroskładników
        $this->protein = round(($this->dailyCalories * 0.30) / 4, 0); // 4 kalorie na gram białka
        $this->carbs = round(($this->dailyCalories * 0.40) / 4, 0);   // 4 kalorie na gram węglowodanów
        $this->fat = round(($this->dailyCalories * 0.30) / 9, 0);     // 9 kalorii na gram tłuszczu
        
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
        
        // Jeśli użytkownik ma profil z ograniczeniami dietetycznymi
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
            // Sprawdź czy mamy dostęp do API tłumaczenia
            if (config('services.libretranslate.key') || config('services.libretranslate.url') !== 'https://libretranslate.com') {
                $translatedQuery = $this->translateService->translate($originalQuery, 'pl', 'en');
                
                if ($translatedQuery && $translatedQuery !== $originalQuery) {
                    $searchTerm = $translatedQuery;
                    $wasTranslated = true;
                }
            } else {
                // Jako fallback, spróbuj użyć tłumaczenia Spoonacular
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
    
    /**
     * View the details of a recipe
     * 
     * @param int $recipeId
     */
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
                    
                    // Najpierw pokaż modal
                    $this->showRecipeModal = true;
                    $this->loading = false;
                    
                    // Jeśli język to polski, ustaw flagę tłumaczenia
                    // Właściwe tłumaczenie rozpocznie się po pełnym załadowaniu modala (w JS)
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
            
            // Najpierw pokaż modal
            $this->showRecipeModal = true;
            $this->loading = false;
            
            // Jeśli język to polski, ustaw flagę tłumaczenia
            // Właściwe tłumaczenie rozpocznie się po pełnym załadowaniu modala (w JS)
            if ($this->autoTranslate) {
                $this->translateRecipe = true;
            }
        } else {
            session()->flash('error', __('nutrition_calculator.recipe_fetch_error'));
            $this->loading = false;
        }
    }
    
    /**
     * Rozpoczyna sekwencyjne tłumaczenie po pełnym załadowaniu modala
     * Ta metoda jest wywoływana z poziomu JS po załadowaniu modala
     */
    public function startSequentialTranslation()
    {
        $this->performTranslation();
    }
    
    /**
     * Etapowe tłumaczenie - tłumaczy każdą część oddzielnie, aby nie blokować interfejsu
     */
    public function performTranslation()
    {
        if (!$this->selectedRecipe) {
            return;
        }
        
        // Powiadom interfejs, że rozpoczyna się tłumaczenie
        $this->dispatch('translationStarted');
        
        // Tłumacz tytuł - najpierw, bo jest najważniejszy
        $this->translateTitle();
    }
    
    /**
     * Tłumaczy tytuł przepisu
     */
    public function translateTitle()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['title']) || empty($this->selectedRecipe['title'])) {
            // Przejdź od razu do tłumaczenia instrukcji
            $this->translateInstructions();
            return;
        }
        
        $this->dispatch('translatingTitle');
        
        try {
            $this->translatedTitle = $this->translateService->translate($this->selectedRecipe['title'], 'en', 'pl', 'text');
            
            // Powiadom interfejs, że tytuł został przetłumaczony i pokaż go
            $this->dispatch('titleTranslated');
            
            // Przejdź do tłumaczenia instrukcji
            $this->translateInstructions();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error translating title: ' . $e->getMessage());
            $this->translatedTitle = $this->selectedRecipe['title']; // Użyj oryginalnego tytułu
            $this->translateInstructions(); // Kontynuuj z instrukcjami
        }
    }
    
    /**
     * Tłumaczy instrukcje przepisu
     */
    public function translateInstructions()
    {
        if (!$this->selectedRecipe) {
            // Przejdź do składników
            $this->translateIngredients();
            return;
        }
        
        $this->dispatch('translatingInstructions');
        
        try {
            if (isset($this->selectedRecipe['instructions']) && !empty($this->selectedRecipe['instructions'])) {
                $this->translatedInstructions = $this->translateService->translate($this->selectedRecipe['instructions'], 'en', 'pl', 'html');
                
                // Powiadom interfejs, że instrukcje zostały przetłumaczone
                $this->dispatch('instructionsTranslated');
                
                // Przejdź do składników
                $this->translateIngredients();
            } elseif (isset($this->selectedRecipe['analyzedInstructions']) && is_array($this->selectedRecipe['analyzedInstructions']) && 
                      count($this->selectedRecipe['analyzedInstructions']) > 0 && isset($this->selectedRecipe['analyzedInstructions'][0]['steps'])) {
                
                $steps = [];
                foreach ($this->selectedRecipe['analyzedInstructions'][0]['steps'] as $step) {
                    $steps[] = $step['step'];
                }
                
                $translatedSteps = [];
                
                // Tłumacz kroki pojedynczo, aby nie blokować zbyt długo
                foreach ($steps as $index => $step) {
                    try {
                        $translatedStep = $this->translateService->translate($step, 'en', 'pl', 'text');
                        if ($translatedStep) {
                            $translatedSteps[$index] = $translatedStep;
                        } else {
                            $translatedSteps[$index] = $step;
                        }
                    } catch (\Exception $e) {
                        $translatedSteps[$index] = $step; // Użyj oryginalnego tekstu
                    }
                }
                
                // Formatuj kroki jako listę HTML
                $htmlList = '<ol class="list-decimal pl-5 space-y-2">';
                foreach ($translatedSteps as $step) {
                    $htmlList .= '<li class="text-gray-700">' . htmlspecialchars($step) . '</li>';
                }
                $htmlList .= '</ol>';
                
                $this->translatedInstructions = $htmlList;
                
                // Powiadom interfejs, że instrukcje zostały przetłumaczone
                $this->dispatch('instructionsTranslated');
                
                // Przejdź do składników
                $this->translateIngredients();
            } else {
                // Brak instrukcji, przejdź do składników
                $this->translateIngredients();
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error translating instructions: ' . $e->getMessage());
            // W przypadku błędu, przejdź do składników
            $this->translateIngredients();
        }
    }
    
    /**
     * Tłumaczy składniki przepisu
     */
    public function translateIngredients()
    {
        if (!$this->selectedRecipe || !isset($this->selectedRecipe['extendedIngredients']) || !is_array($this->selectedRecipe['extendedIngredients'])) {
            // Zakończ tłumaczenie
            $this->finishTranslation();
            return;
        }
        
        $this->dispatch('translatingIngredients');
        
        try {
            $ingredients = $this->selectedRecipe['extendedIngredients'];
            
            // Tłumacz składniki pojedynczo
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
                    $this->translatedIngredients[$index] = $originalText; // Użyj oryginalnego tekstu
                }
            }
            
            // Powiadom interfejs, że składniki zostały przetłumaczone
            $this->dispatch('ingredientsTranslated');
            
            // Zakończ tłumaczenie
            $this->finishTranslation();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error translating ingredients: ' . $e->getMessage());
            $this->finishTranslation(); // Zakończ mimo błędu
        }
    }
    
    /**
     * Kończy proces tłumaczenia
     */
    private function finishTranslation()
    {
        $this->loading = false;
        
        // Powiadom interfejs, że tłumaczenie zostało zakończone
        $this->dispatch('translationComplete');
    }
    
    /**
     * Toggle translation of recipe
     */
    public function toggleRecipeTranslation()
    {
        $this->translateRecipe = !$this->translateRecipe;
        
        if ($this->translateRecipe && $this->selectedRecipe) {
            // Rozpocznij tłumaczenie
            $this->startSequentialTranslation();
        } else {
            // Jeśli przełączamy z powrotem na tekst oryginalny, informujemy interfejs
            $this->dispatch('switchingToOriginal');
            
            // Po krótkim opóźnieniu powiadom, że tłumaczenie zostało zakończone
            // To da czas na pokazanie animacji
            $this->dispatch('translationComplete');
        }
    }
    
    /**
     * Handle language change
     */
    public function handleLanguageChange($locale)
    {
        // Update the auto-translate flag based on the new locale
        $this->autoTranslate = $locale === 'pl';
        
        // If a recipe is already displayed and we're switching to Polish, translate it
        if ($this->autoTranslate && $this->selectedRecipe && !$this->translateRecipe) {
            $this->translateRecipe = true;
            $this->performTranslation();
        }
        
        // Force re-render when language changes
        $this->dispatch('$refresh');
    }
    
    /**
     * Close the recipe details modal
     */
    public function closeRecipeModal()
    {
        $this->selectedRecipe = null;
        $this->showRecipeModal = false;
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
