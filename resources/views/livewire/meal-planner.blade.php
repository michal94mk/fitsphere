<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div>
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 py-8 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-extrabold mb-4">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                        {{ __('meal_planner.title') }}
                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ __('meal_planner.subtitle') }}
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
                            <h3 class="text-sm font-medium text-yellow-800">{{ __('nutrition_calculator.api_key_missing_title') }}</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ __('meal_planner.api_key_missing_message') }}</p>
                                <ol class="mt-1 ml-4 list-decimal">
                                    <li>{{ __('nutrition_calculator.api_key_step1') }} <a href="https://spoonacular.com/food-api" target="_blank" class="underline">spoonacular.com/food-api</a></li>
                                    <li>{{ __('nutrition_calculator.api_key_step2') }}</li>
                                    <li>{{ __('nutrition_calculator.api_key_step3') }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-6">{{ __('meal_planner.title') }}</h2>
                    
                    <!-- Kalendarz i wybór daty -->
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <button wire:click="prevWeek" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                {{ __('meal_planner.prev_week') }}
                            </button>
                            
                            <div class="text-center">
                                <h3 class="text-lg font-semibold">{{ \Carbon\Carbon::parse($startDate)->format('d.m.Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d.m.Y') }}</h3>
                            </div>
                            
                            <button wire:click="nextWeek" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md">
                                {{ __('meal_planner.next_week') }}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1">
                            @for ($i = 0; $i < 7; $i++)
                                @php
                                    $currentDate = \Carbon\Carbon::parse($startDate)->addDays($i);
                                    $isToday = $currentDate->isToday();
                                    $isSelected = $currentDate->format('Y-m-d') === $date;
                                @endphp
                                
                                <button 
                                    wire:click="updateDate('{{ $currentDate->format('Y-m-d') }}')"
                                    class="p-3 rounded-md text-center transition-colors {{ $isToday ? 'bg-indigo-100 border border-indigo-300' : 'bg-white' }} {{ $isSelected ? 'bg-indigo-200 border border-indigo-400' : '' }} hover:bg-indigo-100"
                                >
                                    <div class="text-xs text-gray-500">{{ $currentDate->locale('pl')->dayName }}</div>
                                    <div class="text-lg font-semibold">{{ $currentDate->day }}</div>
                                    
                                    @if (isset($dailyTotals[$currentDate->format('Y-m-d')]))
                                        <div class="mt-1 text-xs text-gray-500">{{ round($dailyTotals[$currentDate->format('Y-m-d')]['calories']) }} kcal</div>
                                    @endif
                                </button>
                            @endfor
                        </div>
                    </div>
                    
                    <!-- Generator planu posiłków -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold mb-4">{{ __('meal_planner.generate_plan') }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('meal_planner.dietary_preferences') }}</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="vegetarian" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('meal_planner.vegetarian') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="vegan" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('meal_planner.vegan') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="gluten-free" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('meal_planner.gluten_free') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="ketogenic" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2">{{ __('meal_planner.keto') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="excludeIngredients" class="block text-sm font-medium text-gray-700 mb-1">{{ __('meal_planner.excluded_ingredients') }}</label>
                                    <input type="text" wire:model="excludeIngredients" id="excludeIngredients" placeholder="{{ __('meal_planner.excluded_ingredients_placeholder') }}" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <p class="mt-1 text-xs text-gray-500">{{ __('meal_planner.comma_separated') }}</p>
                                </div>
                                
                                <div>
                                    <button wire:click="generateMealPlan" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        {{ __('meal_planner.generate_plan') }}
                                    </button>
                                    
                                    <!-- Message alerts for generate meal plan -->
                                    @if (session()->has('message'))
                                        <div class="mt-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm" wire:key="meal-success-{{ time() }}">
                                            {{ session('message') }}
                                        </div>
                                    @endif
                                    
                                    @if (session()->has('success'))
                                        <div class="mt-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm" wire:key="meal-success-2-{{ time() }}">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    
                                    @if (session()->has('error'))
                                        <div class="mt-3 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm" wire:key="meal-error-{{ time() }}">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                @if ($loading)
                                    <div class="flex justify-center items-center h-full">
                                        <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                @elseif ($generatedPlan && isset($generatedPlan['meals']))
                                    <div class="bg-white p-4 rounded-md shadow">
                                        <h4 class="text-lg font-semibold mb-3">Wygenerowany plan</h4>
                                        <p class="mb-2 text-sm text-gray-500">Kliknij przepis, aby dodać go do dnia {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}</p>
                                        
                                        <div class="space-y-3">
                                            @foreach ($generatedPlan['meals'] as $index => $meal)
                                                <div class="border rounded-md p-3 hover:bg-gray-50 cursor-pointer" wire:click="selectRecipe({{ $meal['id'] }}, '{{ $meal['title'] }}')">
                                                    <div class="flex items-center">
                                                        @if (isset($meal['image']))
                                                            <img src="{{ $meal['image'] }}" alt="{{ $meal['title'] }}" class="w-16 h-16 object-cover rounded mr-3">
                                                        @endif
                                                        <div>
                                                            <h5 class="font-medium">{{ $meal['title'] }}</h5>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                <span class="mr-2">{{ $meal['readyInMinutes'] ?? '?' }} min</span>
                                                                @if (isset($meal['servings']))
                                                                    <span>{{ $meal['servings'] }} porcji</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if (isset($generatedPlan['nutrients']))
                                            <div class="mt-4 pt-3 border-t">
                                                <h5 class="font-medium mb-2">Wartości odżywcze (dziennie)</h5>
                                                <div class="grid grid-cols-2 gap-2 text-sm">
                                                    <div>Kalorie: <span class="font-semibold">{{ round($generatedPlan['nutrients']['calories']) }} kcal</span></div>
                                                    <div>Białko: <span class="font-semibold">{{ round($generatedPlan['nutrients']['protein']) }} g</span></div>
                                                    <div>Węglowodany: <span class="font-semibold">{{ round($generatedPlan['nutrients']['carbohydrates']) }} g</span></div>
                                                    <div>Tłuszcze: <span class="font-semibold">{{ round($generatedPlan['nutrients']['fat']) }} g</span></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sekcja wyszukiwania przepisów -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                        <div class="mb-4">
                            <h3 class="text-xl font-semibold">{{ __('meal_planner.recipe_search') }}</h3>
                        </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="md:col-span-2">
                                    <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-1">{{ __('meal_planner.search_recipes') }}</label>
                                    <div class="flex">
                                        <input 
                                            type="text" 
                                            wire:model="searchQuery" 
                                            id="searchQuery" 
                                            placeholder="{{ __('meal_planner.search_placeholder') }}" 
                                            class="flex-1 rounded-l-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            wire:keydown.enter="searchRecipes"
                                        >
                                        <button 
                                            wire:click="searchRecipes" 
                                            class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600 transition duration-300"
                                            @if($searchLoading) disabled @endif
                                        >
                                            @if($searchLoading)
                                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            @else
                                                {{ __('meal_planner.search_button') }}
                                            @endif
                                        </button>
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="maxCalories" class="block text-sm font-medium text-gray-700 mb-1">{{ __('meal_planner.max_calories_per_serving') }}</label>
                                    <input 
                                        type="number" 
                                        wire:model="maxCalories" 
                                        id="maxCalories" 
                                        placeholder="np. 500" 
                                        class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                </div>
                            </div>
                            
                            <!-- Search results -->
                            @if($searchLoading)
                                <div class="flex justify-center items-center h-32">
                                    <div class="text-center">
                                        <svg class="animate-spin h-10 w-10 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <p class="text-gray-600">{{ __('meal_planner.search_loading') }}</p>
                                    </div>
                                </div>
                            @elseif(!empty($searchResults) && isset($searchResults['results']))
                                <div class="bg-white p-4 rounded-md shadow">
                                    <h4 class="text-lg font-semibold mb-3">
                                        {{ __('meal_planner.search_results') }}
                                        @if(!empty($searchQuery))
                                            <span class="text-gray-500 font-normal text-sm">{{ __('meal_planner.search_results_for') }} "{{ $searchQuery }}"</span>
                                        @endif
                                    </h4>
                                    
                                    @if(count($searchResults['results']) > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            @foreach($searchResults['results'] as $recipe)
                                                <div class="border rounded-lg overflow-hidden hover:shadow-md cursor-pointer transition duration-200 flex flex-col h-full" wire:click="selectRecipe({{ $recipe['id'] }}, '{{ $recipe['title'] }}')">
                                                    <!-- Image section - fixed height -->
                                                    <div class="relative">
                                                        @if(isset($recipe['image']))
                                                            <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="w-full h-40 object-cover">
                                                        @else
                                                            <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Floating dietary tags on image -->
                                                        @if((isset($recipe['vegetarian']) && $recipe['vegetarian']) || (isset($recipe['vegan']) && $recipe['vegan']) || (isset($recipe['glutenFree']) && $recipe['glutenFree']))
                                                            <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                                                                @if(isset($recipe['vegan']) && $recipe['vegan'])
                                                                    <span class="px-2 py-1 bg-green-600 text-white text-xs font-medium rounded-full shadow-sm">V</span>
                                                                @elseif(isset($recipe['vegetarian']) && $recipe['vegetarian'])
                                                                    <span class="px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-full shadow-sm">VEG</span>
                                                                @endif
                                                                @if(isset($recipe['glutenFree']) && $recipe['glutenFree'])
                                                                    <span class="px-2 py-1 bg-yellow-500 text-white text-xs font-medium rounded-full shadow-sm">GF</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Content section - flexible height -->
                                                    <div class="p-4 flex flex-col flex-grow">
                                                        <!-- Title - fixed height area -->
                                                        <h5 class="font-medium text-sm mb-3 line-clamp-2 min-h-[2.5rem]">{{ $recipe['title'] }}</h5>
                                                        
                                                        <!-- Info section - fixed height area -->
                                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                                            <div class="flex items-center">
                                                                @if(isset($recipe['readyInMinutes']))
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                    </svg>
                                                                    <span>{{ $recipe['readyInMinutes'] }} min</span>
                                                                @endif
                                                            </div>
                                                            <div class="flex items-center">
                                                                @if(isset($recipe['servings']))
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                                    </svg>
                                                                    <span>{{ $recipe['servings'] }} {{ __('meal_planner.servings') }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Hover effect overlay -->
                                                        <div class="mt-auto">
                                                            <div class="text-xs text-blue-600 font-medium">{{ __('meal_planner.click_to_see_details') }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic">{{ __('meal_planner.no_recipes_found') }}</p>
                                    @endif
                                </div>
                            @elseif(!empty($searchQuery))
                                <div class="bg-white p-4 rounded-md shadow text-center">
                                    <p class="text-gray-500">{{ __('meal_planner.no_recipes_found') }}</p>
                                </div>
                            @endif
                            
                            <!-- Search messages -->
                            @if(session()->has('search_translated'))
                                @php
                                    $translated = session('search_translated');
                                @endphp
                                <div class="mt-3 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-3 text-sm">
                                    <p>{{ __('nutrition_calculator.translation_detail', ['original' => $translated['original'], 'translated' => $translated['translated']]) }}</p>
                                </div>
                            @endif
                            
                            @if(session()->has('info'))
                                <div class="mt-3 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-3 text-sm">
                                    {{ session('info') }}
                                </div>
                            @endif
                            
                            @if(session()->has('error'))
                                <div class="mt-3 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm">
                                    {{ session('error') }}
                                </div>
                            @endif
                    </div>

                    <!-- Panel szczegółów przepisu z tłumaczeniem -->
                    @if ($selectedRecipe)
                        <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                            <!-- Header with title and translation toggle -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="relative flex-1">
                                    <!-- Recipe title -->
                                    <h3 class="text-2xl font-bold text-gray-900" 
                                        x-data="{ titleLoading: @entangle('titleLoading') }"
                                        x-show="!titleLoading">
                                        @if($translateRecipe && $translatedTitle)
                                            {{ $translatedTitle }}
                                        @else
                                            {{ $selectedRecipe['name'] ?? $selectedRecipe['title'] }}
                                        @endif
                                    </h3>
                                    
                                    <!-- Title loading animation -->
                                    <div 
                                        x-data="{ titleLoading: @entangle('titleLoading') }"
                                        x-show="titleLoading" 
                                        class="flex items-center space-x-2 py-2"
                                        style="display: none;"
                                    >
                                        <div class="h-6 w-6">
                                            <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                        <div class="h-8 bg-gray-200 rounded w-64 animate-pulse"></div>
                                    </div>
                                </div>
                                
                                <!-- Translation toggle button for Polish users -->
                                @if(App::getLocale() === 'pl')
                                    <div class="ml-4">
                                        <button 
                                            wire:click="toggleRecipeTranslation" 
                                            class="px-3 py-2 bg-blue-500 text-white rounded-md text-sm hover:bg-blue-600 transition duration-200 flex items-center space-x-2"
                                            @if($loadingRecipeDetails || $titleLoading || $instructionsLoading || $ingredientsLoading) disabled @endif
                                        >
                                            @if($translateRecipe)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 716.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                                </svg>
                                                <span>{{ __('meal_planner.show_original') }}</span>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 716.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                                </svg>
                                                <span>{{ __('meal_planner.translate_to_polish') }}</span>
                                            @endif
                                        </button>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Recipe details column -->
                                <div class="lg:col-span-2">
                                    @if (isset($selectedRecipe['image']))
                                        <img src="{{ $selectedRecipe['image'] }}" alt="{{ $selectedRecipe['name'] ?? $selectedRecipe['title'] }}" class="w-full h-64 object-cover rounded-md mb-4">
                                    @endif
                                    
                                    <!-- Recipe tags and basic info -->
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if (isset($selectedRecipe['vegetarian']) && $selectedRecipe['vegetarian'])
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">{{ __('meal_planner.vegetarian') }}</span>
                                        @endif
                                        @if (isset($selectedRecipe['vegan']) && $selectedRecipe['vegan'])
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">{{ __('meal_planner.vegan') }}</span>
                                        @endif
                                        @if (isset($selectedRecipe['glutenFree']) && $selectedRecipe['glutenFree'])
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">{{ __('meal_planner.gluten_free') }}</span>
                                        @endif
                                        @if (isset($selectedRecipe['dairyFree']) && $selectedRecipe['dairyFree'])
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">{{ __('meal_planner.dairy_free') }}</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Recipe info grid -->
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div class="bg-white p-3 rounded-md">
                                            <span class="text-sm text-gray-500">{{ __('meal_planner.preparation_time') }}:</span>
                                            <div class="font-semibold">{{ $selectedRecipe['readyInMinutes'] ?? '?' }} min</div>
                                        </div>
                                        <div class="bg-white p-3 rounded-md">
                                            <span class="text-sm text-gray-500">{{ __('meal_planner.servings') }}:</span>
                                            <div class="font-semibold">{{ $selectedRecipe['servings'] ?? '1' }}</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Nutrition information -->
                                    <div class="bg-white p-4 rounded-md mb-6">
                                        <h5 class="font-medium mb-3">{{ __('meal_planner.nutritional_info') }}</h5>
                                        <div class="grid grid-cols-2 gap-3">
                                            @if (isset($selectedRecipe['nutrition']['nutrients']))
                                                @php
                                                    $calories = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Calories');
                                                    $protein = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Protein');
                                                    $carbs = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Carbohydrates');
                                                    $fat = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Fat');
                                                @endphp
                                                
                                                <div class="text-sm">
                                                    <span class="text-gray-500">{{ __('meal_planner.calories') }}:</span>
                                                    <span class="font-semibold ml-1">{{ $calories ? round($calories['amount']) : '?' }} kcal</span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="text-gray-500">{{ __('meal_planner.protein') }}:</span>
                                                    <span class="font-semibold ml-1">{{ $protein ? round($protein['amount']) : '?' }} g</span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="text-gray-500">{{ __('meal_planner.carbs') }}:</span>
                                                    <span class="font-semibold ml-1">{{ $carbs ? round($carbs['amount']) : '?' }} g</span>
                                                </div>
                                                <div class="text-sm">
                                                    <span class="text-gray-500">{{ __('meal_planner.fat') }}:</span>
                                                    <span class="font-semibold ml-1">{{ $fat ? round($fat['amount']) : '?' }} g</span>
                                                </div>
                                                
                                                <div class="col-span-2 text-xs text-gray-500 mt-2">
                                                    <span>{{ __('meal_planner.nutrition_note') }}</span>
                                                </div>
                                            @else
                                                <div class="col-span-2 text-gray-500 italic">{{ __('meal_planner.no_nutrition_info') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Ingredients section -->
                                    <div class="bg-white p-4 rounded-md mb-6">
                                        <div class="flex items-center justify-between mb-3">
                                            <h5 class="font-medium">{{ __('meal_planner.ingredients') }}</h5>
                                            <div x-data="{ ingredientsLoading: @entangle('ingredientsLoading') }"
                                                 x-show="ingredientsLoading && @js(App::getLocale() === 'pl') && @entangle('translateRecipe')" 
                                                 class="flex items-center space-x-2"
                                                 style="display: none;">
                                                <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-sm text-blue-600">{{ __('meal_planner.translating_ingredients') }}</span>
                                            </div>
                                        </div>
                                        
                                        @if (isset($selectedRecipe['extendedIngredients']) && is_array($selectedRecipe['extendedIngredients']) && count($selectedRecipe['extendedIngredients']) > 0)
                                            <ul class="space-y-2 text-sm" 
                                                x-data="{ ingredientsLoading: @entangle('ingredientsLoading') }"
                                                x-show="!ingredientsLoading">
                                                @foreach ($selectedRecipe['extendedIngredients'] as $index => $ingredient)
                                                    <li class="text-gray-700 flex items-start">
                                                        <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                                        <span>
                                                            @if($translateRecipe && isset($translatedIngredients[$index]))
                                                                {{ $translatedIngredients[$index] }}
                                                            @else
                                                                {{ $ingredient['original'] ?? ($ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['name']) }}
                                                            @endif
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-gray-500 italic text-sm">{{ __('meal_planner.no_ingredients') }}</p>
                                        @endif
                                    </div>
                                    
                                    <!-- Instructions section -->
                                    <div class="bg-white p-4 rounded-md mb-6">
                                        <div class="flex items-center justify-between mb-3">
                                            <h5 class="font-medium">{{ __('meal_planner.instructions') }}</h5>
                                            <div x-data="{ instructionsLoading: @entangle('instructionsLoading') }"
                                                 x-show="instructionsLoading && @js(App::getLocale() === 'pl') && @entangle('translateRecipe')" 
                                                 class="flex items-center space-x-2"
                                                 style="display: none;">
                                                <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 818-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="text-sm text-blue-600">{{ __('meal_planner.translating_instructions') }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Instructions content -->
                                        <div x-data="{ instructionsLoading: @entangle('instructionsLoading') }"
                                             x-show="!instructionsLoading">
                                            @if($translateRecipe && $translatedInstructions)
                                                <div class="text-gray-700 text-sm">
                                                    {!! $translatedInstructions !!}
                                                </div>
                                            @elseif(isset($selectedRecipe['analyzedInstructions']) && is_array($selectedRecipe['analyzedInstructions']) && count($selectedRecipe['analyzedInstructions']) > 0 && isset($selectedRecipe['analyzedInstructions'][0]['steps']))
                                                <ol class="list-decimal pl-5 space-y-2 text-sm">
                                                    @foreach($selectedRecipe['analyzedInstructions'][0]['steps'] as $step)
                                                        <li class="text-gray-700">{{ $step['step'] }}</li>
                                                    @endforeach
                                                </ol>
                                            @elseif(isset($selectedRecipe['instructions']) && !empty($selectedRecipe['instructions']))
                                                <div class="text-gray-700 text-sm">
                                                    {!! nl2br(e($selectedRecipe['instructions'])) !!}
                                                </div>
                                            @else
                                                <p class="text-gray-500 italic text-sm">{{ __('meal_planner.no_instructions') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        @if (isset($selectedRecipe['vegetarian']) && $selectedRecipe['vegetarian'])
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Wegetariański</span>
                                        @endif
                                        
                                        @if (isset($selectedRecipe['vegan']) && $selectedRecipe['vegan'])
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Wegański</span>
                                        @endif
                                        
                                        @if (isset($selectedRecipe['glutenFree']) && $selectedRecipe['glutenFree'])
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Bezglutenowy</span>
                                        @endif
                                        
                                        @if (isset($selectedRecipe['dairyFree']) && $selectedRecipe['dairyFree'])
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Bez nabiału</span>
                                        @endif
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <span class="text-sm text-gray-500">Czas przygotowania:</span>
                                            <div class="font-semibold">{{ $selectedRecipe['readyInMinutes'] ?? '?' }} min</div>
                                        </div>
                                        
                                        <div>
                                            <span class="text-sm text-gray-500">Porcje:</span>
                                            <div class="font-semibold">{{ $selectedRecipe['servings'] ?? '1' }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5 class="font-medium mb-2">Wartości odżywcze (na porcję)</h5>
                                        <div class="grid grid-cols-2 gap-2">
                                            @if (isset($selectedRecipe['nutrition']['nutrients']))
                                                @php
                                                    $calories = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Calories');
                                                    $protein = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Protein');
                                                    $carbs = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Carbohydrates');
                                                    $fat = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Fat');
                                                @endphp
                                                
                                                <div>Kalorie: <span class="font-semibold">{{ $calories ? round($calories['amount']) : '?' }} kcal</span></div>
                                                <div>Białko: <span class="font-semibold">{{ $protein ? round($protein['amount']) : '?' }} g</span></div>
                                                <div>Węglowodany: <span class="font-semibold">{{ $carbs ? round($carbs['amount']) : '?' }} g</span></div>
                                                <div>Tłuszcze: <span class="font-semibold">{{ $fat ? round($fat['amount']) : '?' }} g</span></div>
                                                
                                                <div class="col-span-2 text-xs text-gray-500 mt-1">
                                                    <span>Uwaga: Wartości odżywcze podane przez API są już w przeliczeniu na porcję.</span>
                                                </div>
                                            @else
                                                <div class="col-span-2 text-gray-500 italic">Brak informacji o wartościach odżywczych</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="bg-white p-4 rounded-md shadow">
                                        <h4 class="text-lg font-semibold mb-3">{{ __('meal_planner.add_to_plan') }}</h4>
                                        
                                        <div class="mb-4">
                                            <label for="mealType" class="block text-sm font-medium text-gray-700 mb-1">{{ __('meal_planner.meal_type') }}</label>
                                            <select wire:model="mealType" id="mealType" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="breakfast">{{ __('meal_planner.breakfast') }}</option>
                                                <option value="lunch">{{ __('meal_planner.lunch') }}</option>
                                                <option value="dinner">{{ __('meal_planner.dinner') }}</option>
                                                <option value="snack">{{ __('meal_planner.snacks') }}</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="servingSize" class="block text-sm font-medium text-gray-700 mb-1">{{ __('meal_planner.serving_size_adjustment') }}</label>
                                            <div class="flex items-center space-x-3">
                                                <input wire:model="servingSize" id="servingSize" type="number" min="0.5" max="10" step="0.5" class="w-20 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                                <span class="text-sm text-gray-600">
                                                    {{ __('meal_planner.servings_default') }}: {{ $selectedRecipe['servings'] ?? '1' }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ __('meal_planner.serving_size_note') }}</p>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">{{ __('meal_planner.notes') }}</label>
                                            <textarea wire:model="notes" id="notes" rows="3" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                        </div>
                                        
                                        <div class="flex space-x-4">
                                            <button wire:click="saveMealToPlan" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                {{ __('meal_planner.add_to_plan') }}
                                            </button>
                                            
                                            <button wire:click="$set('selectedRecipe', null)" class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                {{ __('meal_planner.cancel') }}
                                            </button>
                                        </div>
                                        
                                        <!-- Add to plan messages -->
                                        @if (session()->has('meal_added'))
                                            <div class="mt-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm" wire:key="meal-added-{{ time() }}">
                                                {{ session('meal_added') }}
                                            </div>
                                        @endif
                                        
                                        @if (session()->has('meal_error'))
                                            <div class="mt-3 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm" wire:key="meal-add-error-{{ time() }}">
                                                {{ session('meal_error') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Lista posiłków na wybrany dzień -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">{{ __('meal_planner.meals_for') }} {{ \Carbon\Carbon::parse($date)->locale(App::getLocale())->isoFormat('D MMMM YYYY') }}</h3>
                            
                            @if (isset($dailyTotals[$date]))
                                <div class="text-sm">
                                    <span class="font-semibold">{{ __('meal_planner.total_calories') }}:</span> {{ round($dailyTotals[$date]['calories']) }} {{ __('meal_planner.kcal') }}
                                </div>
                            @endif
                        </div>
                        
                        @if (count($mealPlans) > 0)
                            <div class="space-y-4">
                                @foreach ($mealPlans as $meal)
                                    <div class="bg-white rounded-md shadow relative">
                                        <!-- Clickable recipe area if recipe data exists -->
                                        @if(isset($meal->recipe_data) && is_array($meal->recipe_data))
                                            <div 
                                                wire:click="viewSavedMealDetails({{ $meal->id }})"
                                                class="p-4 cursor-pointer hover:bg-gray-50 transition duration-200 rounded-md"
                                            >
                                                <div class="flex justify-between">
                                                    <h4 class="font-semibold">
                                                        <span class="inline-block mr-2">
                                                            @switch($meal->meal_type)
                                                                @case('breakfast')
                                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">{{ __('meal_planner.breakfast') }}</span>
                                                                    @break
                                                                
                                                                @case('lunch')
                                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">{{ __('meal_planner.lunch') }}</span>
                                                                    @break
                                                                
                                                                @case('dinner')
                                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ __('meal_planner.dinner') }}</span>
                                                                    @break
                                                                
                                                                @case('snack')
                                                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">{{ __('meal_planner.snacks') }}</span>
                                                                    @break
                                                            @endswitch
                                                        </span>
                                                        {{ $meal->name }}
                                                    </h4>
                                                    
                                                    <div class="text-xs text-blue-500 font-medium">
                                                        {{ __('meal_planner.click_to_see_details') }}
                                                    </div>
                                                </div>
                                                
                                                <!-- Recipe content area -->
                                                <div class="flex flex-col sm:flex-row gap-4 mt-4">
                                                    <!-- Image section -->
                                                    <div class="flex-shrink-0">
                                                        @if (isset($meal->recipe_data['image']))
                                                            <img src="{{ $meal->recipe_data['image'] }}" alt="{{ $meal->name }}" class="w-full sm:w-24 h-20 object-cover rounded-lg">
                                                        @else
                                                            <div class="w-full sm:w-24 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <!-- Content section -->
                                                    <div class="flex-grow min-w-0">
                                                        <!-- Nutrition info in compact grid -->
                                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
                                                            <div class="bg-gray-50 px-3 py-2 rounded-md text-center">
                                                                <div class="text-lg font-semibold text-orange-600">{{ !is_null($meal->calories) && $meal->calories > 0 ? round($meal->calories) : '---' }}</div>
                                                                <div class="text-xs text-gray-500">{{ __('meal_planner.kcal') }}</div>
                                                            </div>
                                                            <div class="bg-gray-50 px-3 py-2 rounded-md text-center">
                                                                <div class="text-lg font-semibold text-blue-600">{{ !is_null($meal->protein) && $meal->protein > 0 ? round($meal->protein) : '---' }}</div>
                                                                <div class="text-xs text-gray-500">{{ __('meal_planner.protein') }} (g)</div>
                                                            </div>
                                                            <div class="bg-gray-50 px-3 py-2 rounded-md text-center">
                                                                <div class="text-lg font-semibold text-green-600">{{ !is_null($meal->carbs) && $meal->carbs > 0 ? round($meal->carbs) : '---' }}</div>
                                                                <div class="text-xs text-gray-500">{{ __('meal_planner.carbs') }} (g)</div>
                                                            </div>
                                                            <div class="bg-gray-50 px-3 py-2 rounded-md text-center">
                                                                <div class="text-lg font-semibold text-purple-600">{{ !is_null($meal->fat) && $meal->fat > 0 ? round($meal->fat) : '---' }}</div>
                                                                <div class="text-xs text-gray-500">{{ __('meal_planner.fat') }} (g)</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Additional info -->
                                                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                                            @if(isset($meal->recipe_data['servings']) && $meal->recipe_data['servings'] > 0)
                                                                <div class="flex items-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                                    </svg>
                                                                    <span>{{ $meal->recipe_data['servings'] }} {{ __('meal_planner.servings') }}</span>
                                                                </div>
                                                            @endif
                                                            
                                                            @if(isset($meal->serving_size) && $meal->serving_size != 1)
                                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-medium">
                                                                    {{ __('meal_planner.actual_serving') }}: {{ $meal->serving_size }}x
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        @if ($meal->notes)
                                                            <div class="mt-3 p-2 bg-yellow-50 rounded-md border-l-3 border-yellow-200">
                                                                <div class="text-sm text-gray-700">
                                                                    <span class="font-medium text-yellow-800">{{ __('meal_planner.notes') }}:</span> {{ $meal->notes }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete button - positioned absolutely to avoid click interference -->
                                            <button 
                                                x-data
                                                @click.stop="$dispatch('open-delete-modal', { mealId: {{ $meal->id }}, mealName: '{{ $meal->name }}' })"
                                                class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition duration-200 p-1 bg-white rounded-full shadow-sm border" 
                                                title="{{ __('meal_planner.delete_meal') }}"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @else
                                            <!-- Non-clickable area for meals without recipe data -->
                                            <div class="p-4">
                                                <div class="flex justify-between">
                                                    <h4 class="font-semibold">
                                                        <span class="inline-block mr-2">
                                                            @switch($meal->meal_type)
                                                                @case('breakfast')
                                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">{{ __('meal_planner.breakfast') }}</span>
                                                                    @break
                                                                
                                                                @case('lunch')
                                                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">{{ __('meal_planner.lunch') }}</span>
                                                                    @break
                                                                
                                                                @case('dinner')
                                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">{{ __('meal_planner.dinner') }}</span>
                                                                    @break
                                                                
                                                                @case('snack')
                                                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">{{ __('meal_planner.snacks') }}</span>
                                                                    @break
                                                            @endswitch
                                                        </span>
                                                        {{ $meal->name }}
                                                    </h4>
                                                    
                                                    <!-- Delete button -->
                                                    <button 
                                                        x-data
                                                        @click="$dispatch('open-delete-modal', { mealId: {{ $meal->id }}, mealName: '{{ $meal->name }}' })"
                                                        class="text-gray-400 hover:text-red-500 transition duration-200" 
                                                        title="{{ __('meal_planner.delete_meal') }}"
                                                    >
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white rounded-md shadow p-6 text-center">
                                <p class="text-gray-500">{{ __('meal_planner.no_meals_planned') }}</p>
                                <p class="mt-2 text-sm text-gray-400">{{ __('meal_planner.use_generator') }}</p>
                            </div>
                        @endif
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
                                        {{ __('meal_planner.login_required_title') }}
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" x-text="message || '{{ __('meal_planner.login_required') }}'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <a href="{{ route('login') }}" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                {{ __('meal_planner.login') }}
                            </a>
                            <button 
                                type="button" 
                                @click="open = false" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                {{ __('meal_planner.cancel') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Meal Modal -->
    <div 
        x-data="{ 
            showDeleteModal: false, 
            mealIdToDelete: null, 
            mealNameToDelete: '' 
        }"
        @open-delete-modal.window="showDeleteModal = true; mealIdToDelete = $event.detail.mealId; mealNameToDelete = $event.detail.mealName"
        x-show="showDeleteModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;"
    >
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div
                x-show="showDeleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"
            ></div>

            <!-- Modal panel -->
            <div
                x-show="showDeleteModal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            >
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ __('meal_planner.delete_meal_title') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('meal_planner.delete_meal_description') }}
                                </p>
                                
                                <div class="mt-4 bg-gray-50 p-4 rounded-md">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="font-medium">{{ __('meal_planner.recipe') }}:</span>
                                        <span class="ml-2" x-text="mealNameToDelete"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button 
                        @click="$wire.deleteMealPlan(mealIdToDelete); showDeleteModal = false"
                        type="button" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        {{ __('meal_planner.delete_meal') }}
                    </button>
                    <button 
                        @click="showDeleteModal = false"
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        {{ __('meal_planner.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
