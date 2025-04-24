<div>
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 py-8 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-extrabold mb-4">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                        {{ __('nutrition_calculator.title') }}
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ __('nutrition_calculator.subtitle') }}
                </p>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informacja o konfiguracji klucza API -->
            @if (empty(config('services.spoonacular.key')) || config('services.spoonacular.key') === 'your_spoonacular_api_key_here')
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Uwaga: Brak skonfigurowanego klucza API</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Funkcje wyszukiwania przepisów i planowania posiłków wymagają klucza API Spoonacular. Aby je aktywować:</p>
                                <ol class="mt-1 ml-4 list-decimal">
                                    <li>Utwórz darmowe konto na <a href="https://spoonacular.com/food-api" target="_blank" class="underline">spoonacular.com/food-api</a></li>
                                    <li>Wygeneruj klucz API w panelu deweloperskim</li>
                                    <li>Dodaj klucz API do zmiennej SPOONACULAR_API_KEY w pliku .env</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-6">{{ __('nutrition_calculator.title') }}</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Formularz profilu -->
                        <div>
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-xl font-semibold mb-4">{{ __('nutrition_calculator.personal_info') }}</h3>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="age" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.age') }}</label>
                                        <input type="number" wire:model="age" id="age" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" max="120">
                                    </div>
                                    
                                    <div>
                                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.gender') }}</label>
                                        <select wire:model="gender" id="gender" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <option value="">{{ __('nutrition_calculator.select') }}...</option>
                                            <option value="male">{{ __('nutrition_calculator.male') }}</option>
                                            <option value="female">{{ __('nutrition_calculator.female') }}</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.weight') }}</label>
                                        <input type="number" wire:model="weight" id="weight" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" step="0.1">
                                    </div>
                                    
                                    <div>
                                        <label for="height" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.height') }}</label>
                                        <input type="number" wire:model="height" id="height" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" step="0.1">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="activityLevel" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.activity_level') }}</label>
                                    <select wire:model="activityLevel" id="activityLevel" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">{{ __('nutrition_calculator.select') }}...</option>
                                        <option value="sedentary">{{ __('nutrition_calculator.activity_level_1') }}</option>
                                        <option value="light">{{ __('nutrition_calculator.activity_level_2') }}</option>
                                        <option value="moderate">{{ __('nutrition_calculator.activity_level_3') }}</option>
                                        <option value="active">{{ __('nutrition_calculator.activity_level_4') }}</option>
                                        <option value="very_active">{{ __('nutrition_calculator.activity_level_5') }}</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="goal" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.goal') }}</label>
                                    <select wire:model="goal" id="goal" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">{{ __('nutrition_calculator.select') }}...</option>
                                        <option value="lose">{{ __('nutrition_calculator.goal_lose') }}</option>
                                        <option value="maintain">{{ __('nutrition_calculator.goal_maintain') }}</option>
                                        <option value="gain">{{ __('nutrition_calculator.goal_gain') }}</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.allergies') }}</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietaryRestrictions" value="gluten" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('nutrition_calculator.gluten_free') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietaryRestrictions" value="dairy" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('nutrition_calculator.dairy_free') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietaryRestrictions" value="peanut" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('nutrition_calculator.peanut_free') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietaryRestrictions" value="vegetarian" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('nutrition_calculator.vegetarian') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietaryRestrictions" value="vegan" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('nutrition_calculator.vegan') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietaryRestrictions" value="sugar" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('nutrition_calculator.sugar_free') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="flex space-x-4">
                                    <button wire:click="calculateNutrition" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        {{ __('nutrition_calculator.calculate') }}
                                    </button>
                                    
                                    <button wire:click="saveProfile" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        {{ __('nutrition_calculator.save_profile') }}
                                    </button>
                                </div>
                                
                                <!-- Profile messages -->
                                @if (session()->has('message'))
                                    <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4">
                                        {{ session('message') }}
                                    </div>
                                @endif
                                
                                @if (session()->has('error'))
                                    <div class="mt-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Wyniki i wyszukiwarka -->
                        <div>
                            @if ($showDietaryInfo)
                                <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-6">
                                    <h3 class="text-xl font-semibold mb-4">{{ __('nutrition_calculator.results') }}</h3>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <div class="text-sm text-gray-500">{{ __('nutrition_calculator.bmr') }}</div>
                                            <div class="text-2xl font-bold">{{ $bmi ?? 'N/A' }}</div>
                                        </div>
                                        
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <div class="text-sm text-gray-500">{{ __('nutrition_calculator.calories') }}</div>
                                            <div class="text-2xl font-bold">{{ $dailyCalories ?? 'N/A' }} {{ __('nutrition_calculator.calories_per_day') }}</div>
                                        </div>
                                    </div>
                                    
                                    <h4 class="text-lg font-semibold mb-2">{{ __('nutrition_calculator.macros') }}</h4>
                                    <div class="grid grid-cols-3 gap-4">
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <div class="text-sm text-gray-500">{{ __('nutrition_calculator.protein') }}</div>
                                            <div class="text-xl font-bold">{{ $protein ?? 'N/A' }} {{ __('nutrition_calculator.grams_per_day') }}</div>
                                        </div>
                                        
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <div class="text-sm text-gray-500">{{ __('nutrition_calculator.carbs') }}</div>
                                            <div class="text-xl font-bold">{{ $carbs ?? 'N/A' }} {{ __('nutrition_calculator.grams_per_day') }}</div>
                                        </div>
                                        
                                        <div class="bg-white p-3 rounded-lg shadow-sm">
                                            <div class="text-sm text-gray-500">{{ __('nutrition_calculator.fat') }}</div>
                                            <div class="text-xl font-bold">{{ $fat ?? 'N/A' }} {{ __('nutrition_calculator.grams_per_day') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                <h3 class="text-xl font-semibold mb-4">{{ __('nutrition_calculator.recipe_search') }}</h3>
                                
                                <div class="mb-4">
                                    <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.search_recipes') }}</label>
                                    <div class="flex">
                                        <input type="text" wire:model="searchQuery" id="searchQuery" placeholder="{{ __('nutrition_calculator.search_placeholder') }}" class="flex-1 rounded-l-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <button wire:click="searchRecipes" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-r-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            {{ __('nutrition_calculator.search_button') }}
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Search messages -->
                                @if (session()->has('success'))
                                    <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                
                                @if (session()->has('search_error'))
                                    <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm">
                                        {{ session('search_error') }}
                                    </div>
                                @endif
                                
                                <div class="mb-4">
                                    <label for="dietFilters" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.diet_optional') }}</label>
                                    <select wire:model="dietFilters" id="dietFilters" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">{{ __('nutrition_calculator.any_diet') }}</option>
                                        <option value="vegetarian">{{ __('nutrition_calculator.vegetarian') }}</option>
                                        <option value="vegan">{{ __('nutrition_calculator.vegan') }}</option>
                                        <option value="gluten-free">{{ __('nutrition_calculator.gluten_free') }}</option>
                                        <option value="ketogenic">{{ __('meal_planner.keto') }}</option>
                                        <option value="paleo">{{ __('meal_planner.paleo') }}</option>
                                        <option value="low-carb">{{ __('meal_planner.low_carb') }}</option>
                                        <option value="whole30">Whole30</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="maxCalories" class="block text-sm font-medium text-gray-700 mb-1">{{ __('nutrition_calculator.max_calories') }}</label>
                                    <input type="number" wire:model="maxCalories" id="maxCalories" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" step="10">
                                </div>
                                
                                @if ($loading)
                                    <div class="flex justify-center my-4">
                                        <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                @if (!empty($searchResults) && isset($searchResults['results']) && count($searchResults['results']) > 0)
                                    <div class="mt-4">
                                        <h4 class="text-lg font-semibold mb-2">{{ __('nutrition_calculator.search_results') }}</h4>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            @foreach ($searchResults['results'] as $recipe)
                                                <div class="bg-white rounded-lg shadow overflow-hidden">
                                                    @if ($recipe['image'])
                                                        <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="w-full h-48 object-cover">
                                                    @endif
                                                    <div class="p-4">
                                                        <h5 class="font-bold">{{ $recipe['title'] }}</h5>
                                                        <div class="flex justify-between items-center mt-2 text-sm text-gray-600">
                                                            @if (isset($recipe['nutrition']['nutrients'][0]['amount']))
                                                                <span>{{ round($recipe['nutrition']['nutrients'][0]['amount']) }} kcal</span>
                                                            @endif
                                                            @if (isset($recipe['readyInMinutes']))
                                                                <span>{{ $recipe['readyInMinutes'] }} min</span>
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('meal-planner') }}?recipeId={{ $recipe['id'] }}" class="mt-3 inline-block px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                            {{ __('nutrition_calculator.add_to_plan') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @elseif (!empty($searchResults) && isset($searchResults['results']) && count($searchResults['results']) == 0)
                                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-yellow-700">
                                                    Nie znaleziono przepisów pasujących do podanych kryteriów.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                @if (isset($searchResults['error']))
                                    <div class="bg-red-50 border-l-4 border-red-400 p-4 mt-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-red-700">
                                                    {{ $searchResults['error'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4">
        @if(!empty(config('services.spoonacular.key')))
            <!-- Login required modal -->
            <div 
                x-data="{ open: false, message: '' }"
                x-show="open"
                x-cloak
                @login-required.window="open = true; message = $event.detail.message"
                class="fixed inset-0 z-50 overflow-y-auto"
                style="display: none;"
            >
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div 
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full" 
                        role="dialog" 
                        aria-modal="true" 
                        aria-labelledby="modal-headline"
                    >
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                                        {{ __('nutrition_calculator.login_required_title') }}
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" x-text="message || '{{ __('nutrition_calculator.login_required') }}'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <a href="{{ route('login') }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('nutrition_calculator.login') }}
                            </a>
                            <button 
                                type="button" 
                                @click="open = false" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {{ __('nutrition_calculator.cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
