<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">{{ __('meal_planner.title') }}</h1>
        
        <!-- Kalendarz tygodniowy -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('meal_planner.weekly_calendar') }}</h2>
            
            <!-- Nawigacja tygodnia -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                <button wire:click="previousWeek" class="flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="hidden sm:inline">{{ __('meal_planner.previous_week') }}</span>
                    <span class="sm:hidden">{{ __('meal_planner.previous') }}</span>
                </button>
                
                <h3 class="text-base sm:text-lg font-medium text-center">
                    {{ $currentWeekStart->format('d.m.Y') }} - {{ $currentWeekStart->copy()->endOfWeek()->format('d.m.Y') }}
                </h3>
                
                <button wire:click="nextWeek" class="flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors">
                    <span class="hidden sm:inline">{{ __('meal_planner.next_week') }}</span>
                    <span class="sm:hidden">{{ __('meal_planner.next') }}</span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Kalendarz dni -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
                @for ($i = 0; $i < 7; $i++)
                    @php
                        $date = $currentWeekStart->copy()->addDays($i);
                        $dateString = $date->format('Y-m-d');
                        $isToday = $date->isToday();
                        $isPast = $date->isPast() && !$date->isToday();
                        $isSelected = $dateString === $selectedDate;
                        $hasSavedPlan = isset($savedPlans[$dateString]) && count($savedPlans[$dateString]) > 0;
                    @endphp
                    
                    <div class="text-center">
                        <div class="text-xs sm:text-sm font-medium text-gray-600 mb-2">
                            {{ __('meal_planner.days.' . strtolower($date->format('l'))) }}
                        </div>
                        <button 
                            wire:click="selectDate('{{ $dateString }}')"
                            class="w-full p-2 sm:p-3 rounded-lg border-2 transition-all duration-200 relative
                                {{ $isPast ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'hover:border-blue-300' }}
                                {{ $isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}
                                {{ $isToday && !$isSelected ? 'border-green-400 bg-green-50' : '' }}"
                            {{ $isPast ? 'disabled' : '' }}
                        >
                            <div class="text-base sm:text-lg font-semibold">{{ $date->day }}</div>
                            @if ($hasSavedPlan)
                                <div class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ count($savedPlans[$dateString]) }} {{ __('meal_planner.meals_count') }}
                                </div>
                            @endif
                        </button>
                    </div>
                @endfor
            </div>
            
            <!-- Wybór daty przez input -->
            <div class="mt-4">
                <label for="dateInput" class="block text-sm font-medium text-gray-700 mb-2">{{ __('meal_planner.selected_day') }}:</label>
                <input 
                    type="date" 
                    id="dateInput"
                    wire:model.live="selectedDate"
                    min="{{ now()->format('Y-m-d') }}"
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>

        <!-- Zapisane posiłki na wybrany dzień -->
        @if ($selectedDate && isset($savedPlans[$selectedDate]) && count($savedPlans[$selectedDate]) > 0)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">{{ __('meal_planner.saved_meals') }} - {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}</h2>
                
                <div class="space-y-4">
                    @foreach ($savedPlans[$selectedDate] as $meal)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">{{ $meal['title'] }}</h3>
                                    @if (isset($meal['nutrition']))
                                        <div class="grid grid-cols-4 gap-4 mt-2 text-sm text-gray-600">
                                            <div>{{ __('meal_planner.calories') }}: {{ round($meal['nutrition']['calories']) }} kcal</div>
                                            <div>{{ __('meal_planner.protein') }}: {{ round($meal['nutrition']['protein']) }}g</div>
                                            <div>{{ __('meal_planner.carbs') }}: {{ round($meal['nutrition']['carbs']) }}g</div>
                                            <div>{{ __('meal_planner.fat') }}: {{ round($meal['nutrition']['fat']) }}g</div>
                                        </div>
                                    @endif
                                </div>
                                <button 
                                    wire:click="deletePlanFromDate('{{ $selectedDate }}')"
                                    class="ml-4 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors"
                                >
                                    {{ __('meal_planner.delete') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif ($selectedDate)
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">{{ __('meal_planner.saved_meals') }} - {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}</h2>
                <p class="text-gray-500">{{ __('meal_planner.no_saved_meals') }}</p>
            </div>
        @endif

        <!-- Generator planu posiłków -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('meal_planner.generate_plan') }}</h2>
            
            @if ($selectedDate)
                <div class="mb-4">
                    <p class="text-sm text-gray-600">{{ __('meal_planner.select_day') }}: <span class="font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}</span></p>
                </div>
                
                <button 
                    wire:click="generateMealPlan" 
                    class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>{{ __('meal_planner.generate') }}</span>
                    <span wire:loading>{{ __('meal_planner.generating') }}</span>
                </button>
            @else
                <p class="text-gray-500">{{ __('meal_planner.select_day') }}</p>
            @endif
            
            <!-- Komunikaty -->
            @if (session()->has('success'))
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session()->has('error'))
                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Wygenerowany plan -->
        @if (!empty($generatedMeals))
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">{{ __('meal_planner.generated_plan') }}</h2>
                
                <div class="space-y-4 mb-6">
                    @foreach ($generatedMeals as $meal)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-4">
                                @if (isset($meal['image']))
                                    <img src="{{ $meal['image'] }}" alt="{{ $meal['title'] }}" class="w-16 h-16 object-cover rounded">
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $meal['title'] }}</h3>
                                    <div class="text-sm text-gray-600 mt-1">
                                        @if (isset($meal['readyInMinutes']))
                                            <span class="mr-4">{{ $meal['readyInMinutes'] }} {{ __('meal_planner.time_min') }}</span>
                                        @endif
                                        @if (isset($meal['servings']))
                                            <span>{{ $meal['servings'] }} {{ __('meal_planner.servings') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <button 
                                    wire:click="viewRecipeDetails({{ $meal['id'] }})"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
                                >
                                    {{ __('meal_planner.see_details') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if ($selectedDate)
                    <button 
                        wire:click="savePlanToDate('{{ $selectedDate }}')"
                        class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                    >
                        {{ __('meal_planner.save_on') }} {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}
                    </button>
                @endif
            </div>
        @endif

        <!-- Szczegóły przepisu -->
        @if ($selectedRecipe)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-semibold">{{ $selectedRecipe['title'] }}</h2>
                    <button 
                        wire:click="$set('selectedRecipe', null)"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
                    >
                        {{ __('meal_planner.back_to_list') }}
                    </button>
                </div>
                
                @if (isset($selectedRecipe['image']))
                    <img src="{{ $selectedRecipe['image'] }}" alt="{{ $selectedRecipe['title'] }}" class="w-full max-w-md mx-auto rounded-lg mb-6">
                @endif
                
                <!-- Informacje podstawowe -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    @if (isset($selectedRecipe['readyInMinutes']))
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <div class="text-2xl font-bold text-blue-600">{{ $selectedRecipe['readyInMinutes'] }}</div>
                            <div class="text-sm text-gray-600">{{ __('meal_planner.time_min') }}</div>
                        </div>
                    @endif
                    @if (isset($selectedRecipe['servings']))
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <div class="text-2xl font-bold text-green-600">{{ $selectedRecipe['servings'] }}</div>
                            <div class="text-sm text-gray-600">{{ __('meal_planner.servings') }}</div>
                        </div>
                    @endif
                    @if (isset($selectedRecipe['nutrition']['calories']))
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <div class="text-2xl font-bold text-orange-600">{{ round($selectedRecipe['nutrition']['calories']) }}</div>
                            <div class="text-sm text-gray-600">{{ __('meal_planner.calories') }}</div>
                        </div>
                    @endif
                </div>
                
                <!-- Wartości odżywcze -->
                @if (isset($selectedRecipe['nutrition']))
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3">{{ __('meal_planner.basic_info') }}</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if (isset($selectedRecipe['nutrition']['protein']))
                                <div class="text-center p-3 bg-blue-50 rounded">
                                    <div class="font-bold text-blue-600">{{ round($selectedRecipe['nutrition']['protein']) }}g</div>
                                    <div class="text-sm text-gray-600">{{ __('meal_planner.protein') }}</div>
                                </div>
                            @endif
                            @if (isset($selectedRecipe['nutrition']['carbs']))
                                <div class="text-center p-3 bg-green-50 rounded">
                                    <div class="font-bold text-green-600">{{ round($selectedRecipe['nutrition']['carbs']) }}g</div>
                                    <div class="text-sm text-gray-600">{{ __('meal_planner.carbs') }}</div>
                                </div>
                            @endif
                            @if (isset($selectedRecipe['nutrition']['fat']))
                                <div class="text-center p-3 bg-yellow-50 rounded">
                                    <div class="font-bold text-yellow-600">{{ round($selectedRecipe['nutrition']['fat']) }}g</div>
                                    <div class="text-sm text-gray-600">{{ __('meal_planner.fat') }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Składniki -->
                @if (isset($selectedRecipe['extendedIngredients']) && count($selectedRecipe['extendedIngredients']) > 0)
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold">{{ __('meal_planner.ingredients') }}</h3>
                            @if (app()->getLocale() === 'pl' && !$translatedIngredients)
                                <button 
                                    wire:click="translateIngredients"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors"
                                    wire:loading.attr="disabled"
                                >
                                    <span wire:loading.remove wire:target="translateIngredients">{{ __('meal_planner.translate_to_polish') }}</span>
                                    <span wire:loading wire:target="translateIngredients">{{ __('meal_planner.translating') }}</span>
                                </button>
                            @endif
                        </div>
                        <ul class="space-y-2">
                            @if ($translatedIngredients && app()->getLocale() === 'pl')
                                @foreach ($translatedIngredients as $ingredient)
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                        {{ $ingredient }}
                                    </li>
                                @endforeach
                            @else
                                @foreach ($selectedRecipe['extendedIngredients'] as $ingredient)
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                        {{ $ingredient['original'] }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                @endif
                
                <!-- Instrukcje -->
                @if (isset($selectedRecipe['instructions']) && !empty($selectedRecipe['instructions']))
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold">{{ __('meal_planner.instructions') }}</h3>
                            @if (app()->getLocale() === 'pl' && !$translatedInstructions)
                                <button 
                                    wire:click="translateInstructions"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors"
                                    wire:loading.attr="disabled"
                                >
                                    <span wire:loading.remove wire:target="translateInstructions">{{ __('meal_planner.translate_to_polish') }}</span>
                                    <span wire:loading wire:target="translateInstructions">{{ __('meal_planner.translating') }}</span>
                                </button>
                            @endif
                        </div>
                        <div class="prose max-w-none">
                            @if ($translatedInstructions && app()->getLocale() === 'pl')
                                {!! nl2br(e($translatedInstructions)) !!}
                            @else
                                {!! $selectedRecipe['instructions'] !!}
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Wyszukiwanie przepisów -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4">{{ __('meal_planner.search_recipes') }}</h2>
            
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4 mb-4">
                <input 
                    type="text" 
                    wire:model="searchQuery"
                    placeholder="{{ __('meal_planner.search_placeholder') }}"
                    class="w-full sm:flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button 
                    wire:click="searchRecipes"
                    class="w-full sm:w-auto px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>{{ __('meal_planner.search') }}</span>
                    <span wire:loading>{{ __('meal_planner.searching') }}</span>
                </button>
            </div>
            
            @if (!empty($searchResults))
                <div class="space-y-4">
                    @foreach ($searchResults as $recipe)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-4">
                                @if (isset($recipe['image']))
                                    <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="w-16 h-16 object-cover rounded">
                                @endif
                                <div class="flex-1">
                                    <h3 class="font-semibold">{{ $recipe['title'] }}</h3>
                                    <div class="text-sm text-gray-600 mt-1">
                                        @if (isset($recipe['readyInMinutes']))
                                            <span class="mr-4">{{ $recipe['readyInMinutes'] }} {{ __('meal_planner.time_min') }}</span>
                                        @endif
                                        @if (isset($recipe['servings']))
                                            <span>{{ $recipe['servings'] }} {{ __('meal_planner.servings') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <button 
                                    wire:click="viewRecipeDetails({{ $recipe['id'] }})"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
                                >
                                    {{ __('meal_planner.see_details') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif ($searchQuery && empty($searchResults) && !$searchLoading)
                <p class="text-gray-500">{{ __('meal_planner.no_recipes_found') }}</p>
            @endif
        </div>
    </div>
</div>
