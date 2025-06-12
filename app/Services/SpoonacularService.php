<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Exceptions\ApiException;
use Throwable;

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
     */
    public function translate(string $text, string $from = 'pl', string $to = 'en')
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to translate without valid Spoonacular API key');
            throw ApiException::spoonacular(
                '/translate', 
                'Missing or invalid Spoonacular API key', 
                401
            );
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

    /**
     * Search for recipes using Spoonacular API
     *
     * @param string $query Search query
     * @param array $params Additional parameters
     * @return array|null Search results or null if search fails
     * @throws ApiException If API request fails
     */
    public function searchRecipes(string $query, array $params = [])
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to search recipes without valid Spoonacular API key');
            throw ApiException::spoonacular(
                '/recipes/complexSearch', 
                'Missing or invalid Spoonacular API key', 
                401
            );
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
        
        $endpoint = '/recipes/complexSearch';
        $cacheKey = 'spoonacular_search_' . md5($query . json_encode($params));
        
        try {
            return Cache::remember($cacheKey, 3600, function () use ($query, $params, $endpoint, $originalQuery) {
                Log::info('Calling Spoonacular API for recipe search', [
                    'original_query' => $originalQuery,
                    'search_query' => $query,
                    'params' => array_diff_key($params, ['apiKey' => ''])
                ]);
                
                $requestParams = array_merge([
                    'apiKey' => $this->apiKey,
                    'query' => $query,
                    'number' => 10,
                    'addRecipeInformation' => true,
                    'instructionsRequired' => true,
                    'fillIngredients' => true,
                ], $params);
                
                $httpClient = Http::timeout(10);
                
                // Wyłącz weryfikację SSL w środowisku lokalnym
                if (app()->environment('local')) {
                    $httpClient = $httpClient->withOptions(['verify' => false]);
                }
                
                $response = $httpClient->get($this->baseUrl . $endpoint, $requestParams);
                
                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Received Spoonacular API response', ['count' => count($data['results'] ?? [])]);
                    return $data;
                } else {
                    Log::error('Spoonacular API error', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    
                    throw ApiException::spoonacular(
                        $endpoint,
                        'Error searching recipes: ' . $response->body(),
                        $response->status()
                    );
                }
            });
        } catch (Throwable $e) {
            if ($e instanceof ApiException) {
                throw $e;
            }
            
            Log::error('Exception during recipe search', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw ApiException::spoonacular(
                $endpoint,
                'Failed to search recipes: ' . $e->getMessage()
            );
        }
    }

    /**
     * Get detailed recipe information from Spoonacular API
     */
    public function getRecipeInformation(int $recipeId)
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to get recipe information without valid Spoonacular API key');
            throw ApiException::spoonacular(
                "/recipes/{$recipeId}/information", 
                'Missing or invalid Spoonacular API key', 
                401
            );
        }
        
        $endpoint = "/recipes/{$recipeId}/information";
        $cacheKey = "spoonacular_recipe_{$recipeId}";
        
        try {
            return Cache::remember($cacheKey, 86400, function () use ($recipeId, $endpoint) {
                $httpClient = Http::timeout(10);
                
                // Wyłącz weryfikację SSL w środowisku lokalnym
                if (app()->environment('local')) {
                    $httpClient = $httpClient->withOptions(['verify' => false]);
                }
                
                $response = $httpClient->get($this->baseUrl . $endpoint, [
                    'apiKey' => $this->apiKey,
                    'includeNutrition' => true,
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
                            $httpClient = Http::timeout(10);
                            
                            // Turn 
                            if (app()->environment('local')) {
                                $httpClient = $httpClient->withOptions(['verify' => false]);
                            }
                            
                            $nutritionResponse = $httpClient->get($this->baseUrl . "/recipes/{$recipeId}/nutritionWidget.json", [
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
                    
                    // Format image URL if present
                    if (isset($data['image']) && !empty($data['image'])) {
                        $data['image'] = $this->formatImageUrl($data['image']);
                    }
                    
                    return $data;
                } else {
                    Log::error('Spoonacular API error', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    
                    throw ApiException::spoonacular(
                        $endpoint,
                        'Error retrieving recipe information: ' . $response->body(),
                        $response->status()
                    );
                }
            });
        } catch (Throwable $e) {
            if ($e instanceof ApiException) {
                throw $e;
            }
            
            Log::error('Exception during recipe information retrieval', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw ApiException::spoonacular(
                $endpoint,
                'Failed to retrieve recipe information: ' . $e->getMessage()
            );
        }
    }

    /**
     * Generate image URL from recipe ID
     * Fallback method when image URL is not provided by API
     */
    public function generateImageUrlFromId(int $recipeId, string $size = '312x231'): string
    {
        return "https://spoonacular.com/recipeImages/{$recipeId}-{$size}.jpg";
    }

    /**
     * Format image URL for Spoonacular recipes
     * Spoonacular images may need specific sizing parameters
     */
    public function formatImageUrl(string $imageUrl, string $size = '312x231'): string
    {
        // If it's already a full URL, return as is
        if (str_starts_with($imageUrl, 'http')) {
            return $imageUrl;
        }
        
        // If it's a Spoonacular image filename (like "123456-312x231.jpg")
        if (preg_match('/^\d+(-\d+x\d+)?\.(jpg|jpeg|png|webp)$/i', $imageUrl)) {
            return "https://spoonacular.com/recipeImages/{$imageUrl}";
        }
        
        // If it's just a recipe ID number, construct the URL with size
        if (is_numeric($imageUrl)) {
            return "https://spoonacular.com/recipeImages/{$imageUrl}-{$size}.jpg";
        }
        
        // If it's a relative path, construct the full URL
        if (str_starts_with($imageUrl, '/')) {
            return "https://spoonacular.com/recipeImages" . $imageUrl;
        }
        
        // Default case - assume it's a filename and construct the URL
        return "https://spoonacular.com/recipeImages/{$imageUrl}";
    }

    /**
     * Generate a meal plan from Spoonacular API
     */
    public function generateMealPlan(int $targetCalories, array $params = [])
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to generate meal plan without valid Spoonacular API key');
            throw ApiException::spoonacular(
                '/mealplanner/generate', 
                'Missing or invalid Spoonacular API key', 
                401
            );
        }
        
        $endpoint = '/mealplanner/generate';
        $cacheKey = 'spoonacular_mealplan_' . md5($targetCalories . json_encode($params));
        
        try {
            return Cache::remember($cacheKey, 3600, function () use ($targetCalories, $params, $endpoint) {
                $requestParams = array_merge([
                    'apiKey' => $this->apiKey,
                    'timeFrame' => 'day',
                    'targetCalories' => $targetCalories,
                    'addRecipeInformation' => true,
                    'fillIngredients' => true,
                ], $params);
                
                $httpClient = Http::timeout(15);
                
                // Tu
                if (app()->environment('local')) {
                    $httpClient = $httpClient->withOptions(['verify' => false]);
                }
                
                $response = $httpClient->get($this->baseUrl . $endpoint, $requestParams);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Format image URLs for meals
                    if (isset($data['meals']) && is_array($data['meals'])) {
                        foreach ($data['meals'] as &$meal) {
                            // If meal doesn't have image, try to get it from recipe information
                            if (!isset($meal['image']) || empty($meal['image'])) {
                                if (isset($meal['id'])) {
                                    try {
                                        // Get full recipe information to get the image
                                        $recipeInfo = $this->getRecipeInformation($meal['id']);
                                        if (isset($recipeInfo['image']) && !empty($recipeInfo['image'])) {
                                            $meal['image'] = $recipeInfo['image'];
                                        } else {
                                            // Fallback: generate image URL from recipe ID
                                            $meal['image'] = $this->generateImageUrlFromId($meal['id']);
                                        }
                                    } catch (\Exception $e) {
                                        Log::warning('Could not fetch recipe image for meal', [
                                            'meal_id' => $meal['id'],
                                            'error' => $e->getMessage()
                                        ]);
                                        // Fallback: generate image URL from recipe ID
                                        $meal['image'] = $this->generateImageUrlFromId($meal['id']);
                                    }
                                }
                            } else {
                                // Format existing image URL
                                $meal['image'] = $this->formatImageUrl($meal['image']);
                            }
                        }
                        
                        Log::info('Received meal plan from Spoonacular API', [
                            'meals_count' => count($data['meals']),
                            'meal_titles' => collect($data['meals'])->pluck('title')->toArray(),
                            'meal_images' => collect($data['meals'])->pluck('image')->toArray()
                        ]);
                    } else {
                        Log::warning('Received meal plan from API, but structure is different than expected');
                    }
                    
                    return $data;
                } else {
                    Log::error('Spoonacular API error', [
                        'endpoint' => $endpoint,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    
                    throw ApiException::spoonacular(
                        $endpoint,
                        'Error generating meal plan: ' . $response->body(),
                        $response->status()
                    );
                }
            });
        } catch (Throwable $e) {
            if ($e instanceof ApiException) {
                throw $e;
            }
            
            Log::error('Exception during meal plan generation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            throw ApiException::spoonacular(
                $endpoint,
                'Failed to generate meal plan: ' . $e->getMessage()
            );
        }
    }

    /**
     * Get food information from Spoonacular API
     */
    public function getFoodInformation(string $query)
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Attempt to get food information without valid Spoonacular API key');
            throw ApiException::spoonacular(
                '/food/ingredients/search', 
                'Missing or invalid Spoonacular API key', 
                401
            );
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