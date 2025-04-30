<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SpoonacularService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.spoonacular.com';
    
    /**
     * Dictionary of common Polish food items to English
     */
    protected $foodDictionary = [
        // Meats
        'kurczak' => 'chicken',
        'indyk' => 'turkey',
        'wołowina' => 'beef',
        'wieprzowina' => 'pork',
        'kaczka' => 'duck',
        'cielęcina' => 'veal',
        'jagnięcina' => 'lamb',
        'królik' => 'rabbit',
        
        // Seafood
        'łosoś' => 'salmon',
        'tuńczyk' => 'tuna',
        'dorsz' => 'cod',
        'krewetki' => 'shrimp',
        'małże' => 'mussels',
        'kalmary' => 'squid',
        'ośmiornica' => 'octopus',
        'krab' => 'crab',
        
        // Vegetables
        'ziemniaki' => 'potatoes',
        'marchew' => 'carrot',
        'cebula' => 'onion',
        'czosnek' => 'garlic',
        'pomidor' => 'tomato',
        'ogórek' => 'cucumber',
        'papryka' => 'bell pepper',
        'bakłażan' => 'eggplant',
        'cukinia' => 'zucchini',
        'kapusta' => 'cabbage',
        'brokuł' => 'broccoli',
        'kalafior' => 'cauliflower',
        'szpinak' => 'spinach',
        'sałata' => 'lettuce',
        'burak' => 'beetroot',
        'dynia' => 'pumpkin',
        'groszek' => 'peas',
        'fasola' => 'beans',
        'kukurydza' => 'corn',
        
        // Fruits
        'jabłko' => 'apple',
        'gruszka' => 'pear',
        'śliwka' => 'plum',
        'brzoskwinia' => 'peach',
        'morela' => 'apricot',
        'wiśnia' => 'cherry',
        'truskawka' => 'strawberry',
        'malina' => 'raspberry',
        'jeżyna' => 'blackberry',
        'jagoda' => 'blueberry',
        'borówka' => 'cranberry',
        'banan' => 'banana',
        'pomarańcza' => 'orange',
        'cytryna' => 'lemon',
        'limonka' => 'lime',
        'grejpfrut' => 'grapefruit',
        'ananas' => 'pineapple',
        'mango' => 'mango',
        'awokado' => 'avocado',
        'kiwi' => 'kiwi',
        
        // Grains & Carbs
        'ryż' => 'rice',
        'makaron' => 'pasta',
        'kasza' => 'groats',
        'kasza gryczana' => 'buckwheat',
        'kasza jęczmienna' => 'barley groats',
        'kasza manna' => 'semolina',
        'quinoa' => 'quinoa',
        'płatki owsiane' => 'oatmeal',
        'chleb' => 'bread',
        'bułka' => 'roll',
        'bagietka' => 'baguette',
        
        // Dairy & Eggs
        'mleko' => 'milk',
        'ser' => 'cheese',
        'twaróg' => 'cottage cheese',
        'jajko' => 'egg',
        'śmietana' => 'cream',
        'masło' => 'butter',
        'jogurt' => 'yogurt',
        'kefir' => 'kefir',
        
        // Polish dishes
        'pierogi' => 'dumplings',
        'bigos' => 'hunter\'s stew',
        'gołąbki' => 'cabbage rolls',
        'żurek' => 'sour rye soup',
        'barszcz' => 'beetroot soup',
        'rosół' => 'chicken soup',
        'kopytka' => 'potato dumplings',
        'placki ziemniaczane' => 'potato pancakes',
        'kluski śląskie' => 'silesian dumplings',
        'schabowy' => 'pork cutlet',
        'kotlet mielony' => 'meatball',
        'gulasz' => 'goulash',
        'krokiety' => 'croquettes',
        'naleśniki' => 'pancakes',
        'sernik' => 'cheesecake',
        
        // Herbs & Spices
        'pietruszka' => 'parsley',
        'koperek' => 'dill',
        'bazylia' => 'basil',
        'oregano' => 'oregano',
        'tymianek' => 'thyme',
        'rozmaryn' => 'rosemary',
        'majeranek' => 'marjoram',
        'kminek' => 'caraway',
        'ziele angielskie' => 'allspice',
        'liść laurowy' => 'bay leaf',
        
        // Nuts & Seeds
        'orzechy włoskie' => 'walnuts',
        'migdały' => 'almonds',
        'orzechy laskowe' => 'hazelnuts',
        'pestki dyni' => 'pumpkin seeds',
        'nasiona słonecznika' => 'sunflower seeds',
        'siemię lniane' => 'flaxseed',
        'nasiona chia' => 'chia seeds',
        
        // Other common items
        'olej' => 'oil',
        'oliwa' => 'olive oil',
        'ocet' => 'vinegar',
        'miód' => 'honey',
        'cukier' => 'sugar',
        'sól' => 'salt',
        'pieprz' => 'pepper',
        'mąka' => 'flour',
        'drożdże' => 'yeast',
        'śliwki suszone' => 'prunes',
        'rodzynki' => 'raisins'
    ];

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.key');
        
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::warning('Missing or default Spoonacular API key detected.');
        }
    }

    /**
     * Translate text between languages using local dictionary first, then Spoonacular's translation endpoint
     * 
     * @param string $text Text to translate
     * @param string $from Source language code (e.g., 'pl')
     * @param string $to Target language code (e.g., 'en')
     * @return string|null Translated text or null if translation fails
     */
    public function translate(string $text, string $from = 'pl', string $to = 'en')
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to translate without valid Spoonacular API key');
            return null;
        }

        // Check if the text is in our dictionary (case insensitive)
        $textLower = mb_strtolower(trim($text));
        if (isset($this->foodDictionary[$textLower])) {
            Log::info('Food term found in dictionary', [
                'original' => $text, 
                'translated' => $this->foodDictionary[$textLower]
            ]);
            return $this->foodDictionary[$textLower];
        }
        
        // Check if the text contains multiple words separated by spaces
        $words = explode(' ', $textLower);
        if (count($words) > 1) {
            // Try to translate each word individually using the dictionary
            $translatedWords = [];
            $allWordsTranslated = true;
            
            foreach ($words as $word) {
                if (isset($this->foodDictionary[$word])) {
                    $translatedWords[] = $this->foodDictionary[$word];
                } else {
                    $allWordsTranslated = false;
                    break;
                }
            }
            
            // If all words were found in the dictionary, return the combined translation
            if ($allWordsTranslated) {
                $translation = implode(' ', $translatedWords);
                Log::info('Multi-word food term translated using dictionary', [
                    'original' => $text, 
                    'translated' => $translation
                ]);
                return $translation;
            }
        }

        $cacheKey = 'translation_' . md5($text . $from . $to);
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($text, $from, $to) {
            try {
                $httpClient = Http::withOptions([
                    'verify' => !app()->environment('local')
                ]);
                
                $response = $httpClient->get($this->baseUrl . '/recipes/translate', [
                    'apiKey' => $this->apiKey,
                    'text' => $text,
                    'sourceLanguage' => $from,
                    'targetLanguage' => $to,
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Text translated via API', [
                        'original' => $text, 
                        'translated' => $data['translatedText'] ?? 'null'
                    ]);
                    return $data['translatedText'] ?? null;
                }
                
                Log::error('Translation error: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('Exception when translating: ' . $e->getMessage());
                return null;
            }
        });
    }

    public function searchRecipes(string $query, array $params = [])
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to search recipes without valid Spoonacular API key');
            return [
                'results' => [],
                'error' => 'Valid Spoonacular API key is missing. Administrator should configure it in .env file.'
            ];
        }
        
        // Check if the query contains Polish characters
        $isPolish = preg_match('/[ąćęłńóśźżĄĆĘŁŃÓŚŹŻ]/u', $query);
        $originalQuery = $query;
        
        // If the query is in Polish or might be Polish food, translate it to English
        if ($isPolish || array_key_exists(mb_strtolower(trim($query)), $this->foodDictionary)) {
            $translatedQuery = $this->translate($query, 'pl', 'en');
            if ($translatedQuery) {
                Log::info('Query translated for recipe search', [
                    'original' => $query,
                    'translated' => $translatedQuery
                ]);
                $query = $translatedQuery;
            }
        } else {
            // Check if it's a multi-word query where some words might be in the dictionary
            $words = explode(' ', mb_strtolower(trim($query)));
            $hasPolishFood = false;
            
            foreach ($words as $word) {
                if (array_key_exists($word, $this->foodDictionary)) {
                    $hasPolishFood = true;
                    break;
                }
            }
            
            if ($hasPolishFood || count($words) > 1) {
                $translatedQuery = $this->translate($query, 'pl', 'en');
                if ($translatedQuery && $translatedQuery !== $query) {
                    Log::info('Multi-word query translated for recipe search', [
                        'original' => $query,
                        'translated' => $translatedQuery
                    ]);
                    $query = $translatedQuery;
                }
            }
        }
        
        $cacheKey = 'recipe_search_' . md5($query . serialize($params));
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($query, $params, $originalQuery) {
            try {
                Log::info('Calling Spoonacular API for recipe search', [
                    'original_query' => $originalQuery,
                    'search_query' => $query,
                    'params' => array_diff_key($params, ['apiKey' => ''])
                ]);
                
                $httpClient = Http::withOptions([
                    'verify' => !app()->environment('local')
                ]);
                
                $response = $httpClient->get($this->baseUrl . '/recipes/complexSearch', array_merge([
                    'apiKey' => $this->apiKey,
                    'query' => $query,
                    'number' => 10,
                    'addRecipeNutrition' => true,
                    'fillIngredients' => true,
                    'instructionsRequired' => true,
                    'addRecipeInformation' => true,
                    'instructionsRequired' => true,
                ], $params));
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Received Spoonacular API response', ['count' => count($data['results'] ?? [])]);
                    return $data;
                }
                
                Log::error('Recipe search error: ' . $response->body(), [
                    'status' => $response->status(), 
                    'headers' => $response->headers()
                ]);
                return ['results' => [], 'error' => 'Failed to search recipes. Please try again later. Error code: ' . $response->status()];
            } catch (\Exception $e) {
                Log::error('Exception when searching recipes: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
                return ['results' => [], 'error' => 'An error occurred while searching recipes: ' . $e->getMessage()];
            }
        });
    }

    public function getRecipeInformation(int $recipeId)
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to get recipe information without valid Spoonacular API key');
            return null;
        }
        
        $cacheKey = 'recipe_info_' . $recipeId;
        // Clear the cache to ensure we get fresh data for troubleshooting
        Cache::forget($cacheKey);
        
        try {
            $httpClient = Http::withOptions([
                'verify' => !app()->environment('local')
            ]);
            
            Log::info('Querying recipe information', [
                'recipe_id' => $recipeId,
                'include_nutrition' => true,
                'api_key_length' => strlen($this->apiKey),
                'api_key_starts_with' => substr($this->apiKey, 0, 4)
            ]);
            
            $response = $httpClient->get($this->baseUrl . '/recipes/' . $recipeId . '/information', [
                'apiKey' => $this->apiKey,
                'includeNutrition' => true,
                'instructionsRequired' => true,
                'fillIngredients' => true, 
                'addRecipeInformation' => true,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Full API response for recipe', [
                    'recipe_id' => $recipeId,
                    'status_code' => $response->status(),
                    'response_headers' => $response->headers(),
                    'response_keys' => array_keys($data),
                    'has_nutrition' => isset($data['nutrition']),
                    'has_instructions' => isset($data['instructions']),
                    'has_analyzed_instructions' => isset($data['analyzedInstructions']),
                    'has_extended_ingredients' => isset($data['extendedIngredients']),
                ]);
                
                if (isset($data['nutrition'])) {
                    Log::info('Received nutrition data for recipe', [
                        'recipe_id' => $recipeId,
                        'nutrition_keys' => array_keys($data['nutrition']),
                        'has_nutrients' => isset($data['nutrition']['nutrients']),
                        'nutrients_count' => isset($data['nutrition']['nutrients']) ? count($data['nutrition']['nutrients']) : 0,
                        'nutrients_sample' => isset($data['nutrition']['nutrients']) && count($data['nutrition']['nutrients']) > 0 ? 
                            array_slice($data['nutrition']['nutrients'], 0, min(5, count($data['nutrition']['nutrients']))) : []
                    ]);
                    
                    $nutrients = $data['nutrition']['nutrients'] ?? [];
                    $caloriesInfo = collect($nutrients)->firstWhere('name', 'Calories');
                    $proteinInfo = collect($nutrients)->firstWhere('name', 'Protein');
                    $carbsInfo = collect($nutrients)->firstWhere('name', 'Carbohydrates');
                    $fatInfo = collect($nutrients)->firstWhere('name', 'Fat');
                    
                    $hasCalories = $caloriesInfo !== null;
                    $hasProtein = $proteinInfo !== null;
                    $hasCarbs = $carbsInfo !== null;
                    $hasFat = $fatInfo !== null;
                    
                    Log::info('Main nutritional values status', [
                        'recipe_id' => $recipeId,
                        'has_calories' => $hasCalories,
                        'calories_value' => $hasCalories ? $caloriesInfo['amount'] : null,
                        'has_protein' => $hasProtein,
                        'protein_value' => $hasProtein ? $proteinInfo['amount'] : null,
                        'has_carbs' => $hasCarbs,
                        'carbs_value' => $hasCarbs ? $carbsInfo['amount'] : null,
                        'has_fat' => $hasFat,
                        'fat_value' => $hasFat ? $fatInfo['amount'] : null
                    ]);
                } else {
                    Log::warning('No nutrition data for recipe despite includeNutrition=true parameter', [
                        'recipe_id' => $recipeId
                    ]);
                    
                    // Try to get nutrition data from a separate endpoint as fallback
                    Log::info('Attempting to fetch nutrition data from nutritionWidget endpoint');
                    
                    try {
                        $nutritionResponse = $httpClient->get($this->baseUrl . '/recipes/' . $recipeId . '/nutritionWidget.json', [
                            'apiKey' => $this->apiKey,
                        ]);
                        
                        if ($nutritionResponse->successful()) {
                            $nutritionData = $nutritionResponse->json();
                            Log::info('Received data from nutritionWidget endpoint', [
                                'data_keys' => array_keys($nutritionData),
                                'calories' => $nutritionData['calories'] ?? 'missing',
                                'protein' => $nutritionData['protein'] ?? 'missing',
                                'carbs' => $nutritionData['carbs'] ?? 'missing',
                                'fat' => $nutritionData['fat'] ?? 'missing'
                            ]);
                            
                            // Extract numeric values from strings like "110 kcal"
                            $calories = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['calories'] ?? '0'));
                            $protein = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['protein'] ?? '0'));
                            $carbs = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['carbs'] ?? '0'));
                            $fat = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['fat'] ?? '0'));
                            
                            Log::info('Converted nutrition values', [
                                'calories' => $calories,
                                'protein' => $protein,
                                'carbs' => $carbs,
                                'fat' => $fat
                            ]);
                            
                            $data['nutrition'] = [
                                'nutrients' => [
                                    ['name' => 'Calories', 'amount' => $calories, 'unit' => 'kcal'],
                                    ['name' => 'Protein', 'amount' => $protein, 'unit' => 'g'],
                                    ['name' => 'Carbohydrates', 'amount' => $carbs, 'unit' => 'g'],
                                    ['name' => 'Fat', 'amount' => $fat, 'unit' => 'g']
                                ]
                            ];
                        } else {
                            Log::error('Error fetching nutrition data from nutritionWidget: ' . $nutritionResponse->body());
                        }
                    } catch (\Exception $e) {
                        Log::error('Exception when fetching nutrition data from nutritionWidget: ' . $e->getMessage());
                    }
                }
                
                return $data;
            }
            
            Log::error('Error fetching recipe information: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Exception when fetching recipe information: ' . $e->getMessage());
            return null;
        }
    }

    public function generateMealPlan(int $targetCalories, array $params = [])
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to generate meal plan without valid Spoonacular API key');
            return null;
        }
        
        // Add unique timestamp identifier to cache key to avoid meal repetition
        $cacheKey = 'meal_plan_' . $targetCalories . md5(serialize($params) . now()->format('YmdHi'));
        Cache::forget($cacheKey);
        
        try {
            Log::info('Calling Spoonacular API to generate meal plan', [
                'targetCalories' => $targetCalories,
                'params' => array_diff_key($params, ['apiKey' => ''])
            ]);
            
            $httpClient = Http::withOptions([
                'verify' => !app()->environment('local')
            ]);
            
            $apiParams = array_merge([
                'apiKey' => $this->apiKey,
                'targetCalories' => $targetCalories,
                'timeFrame' => 'day',
            ], $params);
            
            // Add diversity parameters if not already set
            if (!isset($apiParams['sort'])) {
                $apiParams['sort'] = 'random';
            }
            
            if (!isset($apiParams['limitLicense'])) {
                $apiParams['limitLicense'] = false;
            }
            
            $response = $httpClient->get($this->baseUrl . '/mealplanner/generate', $apiParams);
            
            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['meals']) && is_array($data['meals'])) {
                    Log::info('Received meal plan from Spoonacular API', [
                        'meals_count' => count($data['meals']),
                        'meal_titles' => collect($data['meals'])->pluck('title')->toArray()
                    ]);
                } else {
                    Log::warning('Received meal plan from API, but structure is different than expected');
                }
                
                return $data;
            }
            
            Log::error('Error generating meal plan: ' . $response->body(), [
                'status' => $response->status(),
                'headers' => $response->headers()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Exception when generating meal plan: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    public function getFoodInformation(string $query)
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to get food information without valid Spoonacular API key');
            return null;
        }
        
        $cacheKey = 'food_info_' . md5($query);
        
        return Cache::remember($cacheKey, 60 * 24 * 7, function () use ($query) {
            try {
                $httpClient = Http::withOptions([
                    'verify' => !app()->environment('local')
                ]);
                
                $response = $httpClient->get($this->baseUrl . '/food/ingredients/search', [
                    'apiKey' => $this->apiKey,
                    'query' => $query,
                    'number' => 5,
                    'metaInformation' => true,
                ]);
                
                if ($response->successful()) {
                    return $response->json();
                }
                
                Log::error('Error searching food information: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('Exception when searching food information: ' . $e->getMessage());
                return null;
            }
        });
    }
}