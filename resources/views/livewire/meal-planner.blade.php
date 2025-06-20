<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">{{ __('meal_planner.title') }}</h1>
        
        <!-- Kalendarz tygodniowy -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4">{{ __('meal_planner.weekly_calendar') }}</h2>
            
            <!-- Nawigacja tygodnia -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                @php
                    $isCurrentWeek = $currentWeekStart->isSameWeek(\Carbon\Carbon::now());
                @endphp
                <button 
                    wire:click="previousWeek" 
                    class="flex items-center justify-center w-full sm:w-auto px-4 py-2 rounded-md transition-colors
                        {{ $isCurrentWeek ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 hover:bg-gray-300' }}"
                    {{ $isCurrentWeek ? 'disabled' : '' }}
                >
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
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <h2 class="text-xl font-semibold">{{ __('meal_planner.saved_meals') }} - {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}</h2>
                    <button 
                        wire:click="deletePlanFromDate('{{ $selectedDate }}')"
                        class="w-full sm:w-auto px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium"
                    >
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('meal_planner.delete_whole_plan') }}
                    </button>
                </div>
                
                <div class="space-y-4">
                    @foreach ($savedPlans[$selectedDate] as $meal)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                @if (isset($meal['image']))
                                    <div class="flex justify-center sm:justify-start">
                                        <img src="{{ $meal['image'] }}" alt="{{ $meal['title'] }}" class="w-20 h-20 object-cover rounded-lg">
                                    </div>
                                @endif
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="font-semibold text-lg mb-2">{{ $meal['title'] }}</h3>
                                    @if (isset($meal['nutrition']))
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm text-gray-600">
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium">{{ __('meal_planner.calories') }}:</span> {{ round($meal['nutrition']['calories']) }} kcal
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium">{{ __('meal_planner.protein') }}:</span> {{ round($meal['nutrition']['protein']) }}g
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium">{{ __('meal_planner.carbs') }}:</span> {{ round($meal['nutrition']['carbs']) }}g
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium">{{ __('meal_planner.fat') }}:</span> {{ round($meal['nutrition']['fat']) }}g
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex justify-center sm:justify-end">
                                    <button 
                                        wire:click="viewRecipeDetails({{ $meal['id'] }})"
                                        class="w-full sm:w-auto px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                                    >
                                        {{ __('meal_planner.see_details') }}
                                    </button>
                                </div>
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
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                @if (isset($meal['image']))
                                    <div class="flex justify-center sm:justify-start">
                                        <img src="{{ $meal['image'] }}" alt="{{ $meal['title'] }}" class="w-20 h-20 object-cover rounded-lg">
                                    </div>
                                @endif
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="font-semibold text-lg mb-2">{{ $meal['title'] }}</h3>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        @if (isset($meal['readyInMinutes']))
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $meal['readyInMinutes'] }} {{ __('meal_planner.time_min') }}
                                            </div>
                                        @endif
                                        @if (isset($meal['servings']))
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                {{ $meal['servings'] }} {{ __('meal_planner.servings') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-center sm:justify-end">
                                    <button 
                                        wire:click="viewRecipeDetails({{ $meal['id'] }})"
                                        class="w-full sm:w-auto px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                                    >
                                        {{ __('meal_planner.see_details') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                @if ($selectedDate)
                    <div class="text-center">
                        <button 
                            wire:click="savePlanToDate('{{ $selectedDate }}')"
                            class="w-full sm:w-auto px-8 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium"
                        >
                            <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            {{ __('meal_planner.save_on') }} {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}
                        </button>
                    </div>
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
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                @if (isset($recipe['image']))
                                    <div class="flex justify-center sm:justify-start">
                                        <img src="{{ $recipe['image'] }}" alt="{{ $recipe['title'] }}" class="w-24 h-24 object-cover rounded-lg">
                                    </div>
                                @endif
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="font-semibold text-lg mb-2">{{ $recipe['title'] }}</h3>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        @if (isset($recipe['readyInMinutes']))
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $recipe['readyInMinutes'] }} {{ __('meal_planner.time_min') }}
                                            </div>
                                        @endif
                                        @if (isset($recipe['servings']))
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                {{ $recipe['servings'] }} {{ __('meal_planner.servings') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-center sm:justify-end">
                                    <button 
                                        wire:click="viewRecipeDetails({{ $recipe['id'] }})"
                                        class="w-full sm:w-auto px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                                    >
                                        {{ __('meal_planner.see_details') }}
                                    </button>
                                </div>
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
