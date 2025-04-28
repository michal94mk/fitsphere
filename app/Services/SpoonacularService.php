<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class SpoonacularService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.spoonacular.com';

    public function __construct()
    {
        $this->apiKey = config('services.spoonacular.key');
        
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::warning('Missing or default Spoonacular API key detected.');
        }
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
        
        $cacheKey = 'recipe_search_' . md5($query . serialize($params));
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($query, $params) {
            try {
                Log::info('Calling Spoonacular API for recipe search', [
                    'query' => $query,
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
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Full API response for recipe', [
                    'recipe_id' => $recipeId,
                    'status_code' => $response->status(),
                    'response_headers' => $response->headers(),
                    'response_keys' => array_keys($data),
                    'has_nutrition' => isset($data['nutrition']),
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