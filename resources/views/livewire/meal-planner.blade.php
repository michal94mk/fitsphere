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
                            <h3 class="text-sm font-medium text-yellow-800">Uwaga: Brak skonfigurowanego klucza API</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Funkcje generowania planów posiłków i przepisów wymagają klucza API Spoonacular. Aby je aktywować:</p>
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

                    <!-- Panel dodawania przepisu do planu (druga część w kolejnym edicie) -->
                    @if ($selectedRecipe)
                        <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold mb-4">Dodaj przepis do planu</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    @if (isset($selectedRecipe['image']))
                                        <img src="{{ $selectedRecipe['image'] }}" alt="{{ $selectedRecipe['name'] }}" class="w-full h-48 object-cover rounded-md mb-4">
                                    @endif
                                    
                                    <h4 class="text-lg font-semibold mb-2">{{ $selectedRecipe['name'] }}</h4>
                                    
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
                                        <h4 class="text-lg font-semibold mb-3">Zapisz do planu</h4>
                                        
                                        <div class="mb-4">
                                            <label for="mealType" class="block text-sm font-medium text-gray-700 mb-1">Typ posiłku</label>
                                            <select wire:model="mealType" id="mealType" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="breakfast">Śniadanie</option>
                                                <option value="lunch">Obiad</option>
                                                <option value="dinner">Kolacja</option>
                                                <option value="snack">Przekąska</option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notatki</label>
                                            <textarea wire:model="notes" id="notes" rows="3" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                        </div>
                                        
                                        <div class="flex space-x-4">
                                            <button wire:click="saveMealToPlan" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                Dodaj do planu
                                            </button>
                                            
                                            <button wire:click="$set('selectedRecipe', null)" class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                Anuluj
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
                                    <div class="bg-white rounded-md shadow p-4">
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
                                            
                                            <div class="flex space-x-2">
                                                <button wire:click="toggleFavorite({{ $meal->id }})" class="text-gray-400 hover:text-yellow-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $meal->is_favorite ? 'text-yellow-500' : '' }}" fill="{{ $meal->is_favorite ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                    </svg>
                                                </button>
                                                
                                                <button 
                                                    class="text-gray-400 hover:text-red-500" 
                                                    x-on:click="confirm('{{ __('meal_planner.confirm_delete') }}') ? $wire.deleteMealPlan({{ $meal->id }}) : null"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-3">
                                            <div class="sm:col-span-1">
                                                @if (isset($meal->recipe_data['image']))
                                                    <img src="{{ $meal->recipe_data['image'] }}" alt="{{ $meal->name }}" class="w-full h-24 object-cover rounded">
                                                @endif
                                            </div>
                                            
                                            <div class="sm:col-span-3">
                                                <div class="flex flex-wrap gap-4 mb-2 text-sm">
                                                    <div><span class="font-semibold">{{ __('meal_planner.calories') }}:</span> {{ !is_null($meal->calories) && $meal->calories > 0 ? round($meal->calories) : __('meal_planner.na') }} {{ __('meal_planner.kcal') }}</div>
                                                    <div><span class="font-semibold">{{ __('meal_planner.protein') }}:</span> {{ !is_null($meal->protein) && $meal->protein > 0 ? round($meal->protein) : __('meal_planner.na') }} {{ __('meal_planner.g') }}</div>
                                                    <div><span class="font-semibold">{{ __('meal_planner.carbs') }}:</span> {{ !is_null($meal->carbs) && $meal->carbs > 0 ? round($meal->carbs) : __('meal_planner.na') }} {{ __('meal_planner.g') }}</div>
                                                    <div><span class="font-semibold">{{ __('meal_planner.fat') }}:</span> {{ !is_null($meal->fat) && $meal->fat > 0 ? round($meal->fat) : __('meal_planner.na') }} {{ __('meal_planner.g') }}</div>
                                                </div>
                                                
                                                <div class="text-xs text-gray-500 mt-1">
                                                    @if(isset($meal->recipe_data['servings']) && $meal->recipe_data['servings'] > 0)
                                                        <span>{{ __('meal_planner.nutrition_per_serving') }} {{ __('meal_planner.servings') }}: {{ $meal->recipe_data['servings'] }}</span>
                                                    @else
                                                        <span>{{ __('meal_planner.nutrition_per_serving') }}</span>
                                                    @endif
                                                </div>
                                                
                                                @if ($meal->notes)
                                                    <div class="mt-2 text-sm text-gray-600">
                                                        <span class="font-semibold">{{ __('meal_planner.notes') }}:</span> {{ $meal->notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
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
</div>
