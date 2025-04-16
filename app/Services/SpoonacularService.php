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
            Log::warning('Brak klucza API Spoonacular lub używany jest domyślny przykładowy klucz.');
        }
    }

    /**
     * Wyszukaj przepisy według zapytania i parametrów żywieniowych
     */
    public function searchRecipes(string $query, array $params = [])
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Próba wyszukiwania przepisów bez prawidłowego klucza API Spoonacular');
            return [
                'results' => [],
                'error' => 'Brak prawidłowego klucza API Spoonacular. Administrator powinien skonfigurować klucz w pliku .env.'
            ];
        }
        
        $cacheKey = 'recipe_search_' . md5($query . serialize($params));
        
        return Cache::remember($cacheKey, 60 * 24, function () use ($query, $params) {
            try {
                Log::info('Wywołanie API Spoonacular do wyszukiwania przepisów', [
                    'query' => $query,
                    'params' => array_diff_key($params, ['apiKey' => '']) // Logowanie bez klucza API
                ]);
                
                // Tworzenie żądania HTTP z możliwością wyłączenia weryfikacji SSL w środowisku dev
                $httpClient = Http::withOptions([
                    'verify' => !app()->environment('local') // Wyłącz weryfikację SSL tylko w środowisku lokalnym
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
                    Log::info('Otrzymano odpowiedź z API Spoonacular', ['count' => count($data['results'] ?? [])]);
                    return $data;
                }
                
                Log::error('Błąd wyszukiwania przepisów: ' . $response->body(), [
                    'status' => $response->status(), 
                    'headers' => $response->headers()
                ]);
                return ['results' => [], 'error' => 'Nie udało się wyszukać przepisów. Spróbuj ponownie później. Kod błędu: ' . $response->status()];
            } catch (\Exception $e) {
                Log::error('Wyjątek przy wyszukiwaniu przepisów: ' . $e->getMessage(), [
                    'exception' => get_class($e),
                    'trace' => $e->getTraceAsString()
                ]);
                return ['results' => [], 'error' => 'Wystąpił błąd przy wyszukiwaniu przepisów: ' . $e->getMessage()];
            }
        });
    }

    /**
     * Pobierz informacje o przepisie na podstawie ID
     */
    public function getRecipeInformation(int $recipeId)
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Próba pobierania informacji o przepisie bez prawidłowego klucza API Spoonacular');
            return null;
        }
        
        $cacheKey = 'recipe_info_' . $recipeId;
        
        // Usunięcie wcześniej buforowanych danych, aby zapewnić świeże dane
        Cache::forget($cacheKey);
        
        try {
            // Tworzenie żądania HTTP z możliwością wyłączenia weryfikacji SSL w środowisku dev
            $httpClient = Http::withOptions([
                'verify' => !app()->environment('local') // Wyłącz weryfikację SSL tylko w środowisku lokalnym
            ]);
            
            Log::info('Wykonuję zapytanie o informacje o przepisie', [
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
                
                // Log kompletnego obiektu response dla debugowania
                Log::info('Pełna odpowiedź API dla przepisu', [
                    'recipe_id' => $recipeId,
                    'status_code' => $response->status(),
                    'response_headers' => $response->headers(),
                    'response_keys' => array_keys($data),
                    'has_nutrition' => isset($data['nutrition']),
                ]);
                
                // Dodanie logów sprawdzających dane dotyczące odżywiania
                if (isset($data['nutrition'])) {
                    Log::info('Otrzymano dane odżywcze dla przepisu', [
                        'recipe_id' => $recipeId,
                        'nutrition_keys' => array_keys($data['nutrition']),
                        'has_nutrients' => isset($data['nutrition']['nutrients']),
                        'nutrients_count' => isset($data['nutrition']['nutrients']) ? count($data['nutrition']['nutrients']) : 0,
                        'nutrients_sample' => isset($data['nutrition']['nutrients']) && count($data['nutrition']['nutrients']) > 0 ? 
                            array_slice($data['nutrition']['nutrients'], 0, min(5, count($data['nutrition']['nutrients']))) : []
                    ]);
                    
                    // Sprawdź czy są główne wartości odżywcze
                    $nutrients = $data['nutrition']['nutrients'] ?? [];
                    $caloriesInfo = collect($nutrients)->firstWhere('name', 'Calories');
                    $proteinInfo = collect($nutrients)->firstWhere('name', 'Protein');
                    $carbsInfo = collect($nutrients)->firstWhere('name', 'Carbohydrates');
                    $fatInfo = collect($nutrients)->firstWhere('name', 'Fat');
                    
                    $hasCalories = $caloriesInfo !== null;
                    $hasProtein = $proteinInfo !== null;
                    $hasCarbs = $carbsInfo !== null;
                    $hasFat = $fatInfo !== null;
                    
                    Log::info('Status głównych wartości odżywczych', [
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
                    Log::warning('Brak danych odżywczych dla przepisu pomimo parametru includeNutrition=true', [
                        'recipe_id' => $recipeId
                    ]);
                    
                    // Pobierz szczegółowe informacje o wartościach odżywczych z osobnego endpointu
                    Log::info('Spróbujmy pobrać dane odżywcze z endpointu nutritionWidget');
                    
                    try {
                        $nutritionResponse = $httpClient->get($this->baseUrl . '/recipes/' . $recipeId . '/nutritionWidget.json', [
                            'apiKey' => $this->apiKey,
                        ]);
                        
                        if ($nutritionResponse->successful()) {
                            $nutritionData = $nutritionResponse->json();
                            Log::info('Otrzymano dane z endpointu nutritionWidget', [
                                'data_keys' => array_keys($nutritionData),
                                'calories' => $nutritionData['calories'] ?? 'brak',
                                'protein' => $nutritionData['protein'] ?? 'brak',
                                'carbs' => $nutritionData['carbs'] ?? 'brak',
                                'fat' => $nutritionData['fat'] ?? 'brak'
                            ]);
                            
                            // Przekształć wartości odżywcze na liczby
                            $calories = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['calories'] ?? '0'));
                            $protein = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['protein'] ?? '0'));
                            $carbs = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['carbs'] ?? '0'));
                            $fat = floatval(preg_replace('/[^0-9.]/', '', $nutritionData['fat'] ?? '0'));
                            
                            Log::info('Przekształcone wartości odżywcze', [
                                'calories' => $calories,
                                'protein' => $protein,
                                'carbs' => $carbs,
                                'fat' => $fat
                            ]);
                            
                            // Dodaj dane odżywcze do głównych danych
                            $data['nutrition'] = [
                                'nutrients' => [
                                    ['name' => 'Calories', 'amount' => $calories, 'unit' => 'kcal'],
                                    ['name' => 'Protein', 'amount' => $protein, 'unit' => 'g'],
                                    ['name' => 'Carbohydrates', 'amount' => $carbs, 'unit' => 'g'],
                                    ['name' => 'Fat', 'amount' => $fat, 'unit' => 'g']
                                ]
                            ];
                        } else {
                            Log::error('Błąd pobierania danych odżywczych z nutritionWidget: ' . $nutritionResponse->body());
                        }
                    } catch (\Exception $e) {
                        Log::error('Wyjątek przy pobieraniu danych odżywczych z nutritionWidget: ' . $e->getMessage());
                    }
                }
                
                return $data;
            }
            
            Log::error('Błąd pobierania informacji o przepisie: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Wyjątek przy pobieraniu informacji o przepisie: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Wygeneruj plan posiłków na podstawie celów żywieniowych
     */
    public function generateMealPlan(int $targetCalories, array $params = [])
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Próba generowania planu posiłków bez prawidłowego klucza API Spoonacular');
            return null;
        }
        
        // Dodaj unikalny identyfikator czasowy do klucza cache, aby uniknąć powtarzania posiłków
        $cacheKey = 'meal_plan_' . $targetCalories . md5(serialize($params) . now()->format('YmdHi'));
        
        // Usuń stary cache, aby zawsze otrzymać świeże dane
        Cache::forget($cacheKey);
        
        try {
            Log::info('Wywołanie API Spoonacular do generowania planu posiłków', [
                'targetCalories' => $targetCalories,
                'params' => array_diff_key($params, ['apiKey' => ''])
            ]);
            
            // Tworzenie żądania HTTP z możliwością wyłączenia weryfikacji SSL w środowisku dev
            $httpClient = Http::withOptions([
                'verify' => !app()->environment('local') // Wyłącz weryfikację SSL tylko w środowisku lokalnym
            ]);
            
            // Dodaj parametr zapewniający różnorodność posiłków
            $apiParams = array_merge([
                'apiKey' => $this->apiKey,
                'targetCalories' => $targetCalories,
                'timeFrame' => 'day',
            ], $params);
            
            // Dodaj dodatkowe parametry dla różnorodności, jeśli nie zostały wcześniej określone
            if (!isset($apiParams['sort'])) {
                $apiParams['sort'] = 'random';
            }
            
            if (!isset($apiParams['limitLicense'])) {
                $apiParams['limitLicense'] = false;
            }
            
            $response = $httpClient->get($this->baseUrl . '/mealplanner/generate', $apiParams);
            
            if ($response->successful()) {
                $data = $response->json();
                
                // Logowanie otrzymanych danych dla debugowania
                if (isset($data['meals']) && is_array($data['meals'])) {
                    Log::info('Otrzymano plan posiłków z API Spoonacular', [
                        'meals_count' => count($data['meals']),
                        'meal_titles' => collect($data['meals'])->pluck('title')->toArray()
                    ]);
                } else {
                    Log::warning('Otrzymano plan posiłków z API, ale struktura jest inna niż oczekiwano');
                }
                
                return $data;
            }
            
            Log::error('Błąd generowania planu posiłków: ' . $response->body(), [
                'status' => $response->status(),
                'headers' => $response->headers()
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Wyjątek przy generowaniu planu posiłków: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Uzyskaj informacje o wartościach odżywczych produktu
     */
    public function getFoodInformation(string $query)
    {
        if (empty($this->apiKey) || $this->apiKey === 'your_spoonacular_api_key_here') {
            Log::error('Próba pobierania informacji o produkcie bez prawidłowego klucza API Spoonacular');
            return null;
        }
        
        $cacheKey = 'food_info_' . md5($query);
        
        return Cache::remember($cacheKey, 60 * 24 * 7, function () use ($query) {
            try {
                // Tworzenie żądania HTTP z możliwością wyłączenia weryfikacji SSL w środowisku dev
                $httpClient = Http::withOptions([
                    'verify' => !app()->environment('local') // Wyłącz weryfikację SSL tylko w środowisku lokalnym
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
                
                Log::error('Błąd wyszukiwania informacji o produkcie: ' . $response->body());
                return null;
            } catch (\Exception $e) {
                Log::error('Wyjątek przy wyszukiwaniu informacji o produkcie: ' . $e->getMessage());
                return null;
            }
        });
    }
} 