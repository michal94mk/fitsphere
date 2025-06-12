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
                            <h3 class="text-sm font-medium text-yellow-800">{{ __('nutrition_calculator.api_key_missing_title') }}</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>{{ __('nutrition_calculator.api_key_missing_message') }}</p>
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
            
            <!-- Informacja o konfiguracji DeepL -->
            @if(empty(config('services.deepl.key')) && App::getLocale() === 'pl')
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">{{ __('nutrition_calculator.translation_info_title') }}</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>{{ __('nutrition_calculator.translation_info_message') }}</p>
                                <ul class="mt-1 ml-4 list-disc">
                                    <li>{{ __('nutrition_calculator.translation_info_point1') }}</li>
                                    <li>{{ __('nutrition_calculator.translation_info_point2') }}</li>
                                    <li>{{ __('nutrition_calculator.translation_info_point3') }}</li>
                                    <li>{{ __('nutrition_calculator.translation_info_point4') }}</li>
                                </ul>
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
                                
                                @if (session()->has('success'))
                                    <div class="mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" wire:key="profile-success-{{ time() }}">
                                        {{ session('success') }}
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
                                            <div class="text-2xl font-bold">{{ $bmr ? round($bmr) : 'N/A' }}</div>
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
                                <h3 class="text-xl font-semibold mb-4">{{ __('nutrition_calculator.meal_planning') }}</h3>
                                
                                <p class="text-gray-600 mb-4">
                                    {{ __('nutrition_calculator.meal_planning_description') }}
                                </p>
                                
                                <a href="{{ route('meal-planner') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-sm uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    {{ __('nutrition_calculator.go_to_meal_planner') }}
                                </a>
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

    <!-- Recipe Details Modal -->
    @if($showRecipeModal && $selectedRecipe)
    <div class="fixed inset-0 z-50 overflow-y-auto" 
        x-data="{ 
            titleLoading: false,
            instructionsLoading: false,
            ingredientsLoading: false,
            modalReady: false,
            translating: false,
            translationComplete: true
        }" 
        x-init="
            // Oznacz, że modal jest gotowy po pełnym załadowaniu
            setTimeout(() => {
                modalReady = true;
                
                // Jeśli mamy tłumaczyć przepis, rozpocznij tłumaczenie po załadowaniu modala
                if ($wire.translateRecipe) {
                    translating = true;
                    translationComplete = false;
                    $wire.startSequentialTranslation();
                }
            }, 300);
            
            window.addEventListener('translationStarted', () => {
                translating = true;
                translationComplete = false;
            });
            
            window.addEventListener('translatingTitle', () => {
                titleLoading = true;
            });
            
            window.addEventListener('titleTranslated', () => {
                titleLoading = false;
            });
            
            window.addEventListener('translatingInstructions', () => {
                instructionsLoading = true;
            });
            
            window.addEventListener('instructionsTranslated', () => {
                instructionsLoading = false;
            });
            
            window.addEventListener('translatingIngredients', () => {
                ingredientsLoading = true;
            });
            
            window.addEventListener('ingredientsTranslated', () => {
                ingredientsLoading = false;
            });
            
            window.addEventListener('translationComplete', () => {
                // Ukryj animacje tłumaczenia po zakończeniu
                titleLoading = false;
                instructionsLoading = false;
                ingredientsLoading = false;
                setTimeout(() => {
                    translating = false;
                    translationComplete = true;
                }, 300);
            });
            
            window.addEventListener('switchingToOriginal', () => {
                // Pokaż animację przejścia
                translating = true;
                translationComplete = false;
                
                // Natychmiastowo ukryj wszystkie animacje ładowania
                titleLoading = false;
                instructionsLoading = false;
                ingredientsLoading = false;
            });
        "
        x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">
                <div class="bg-white p-6">
                    <!-- Efekt globalnego tłumaczenia - nakładka z animacją -->
                    <div 
                        x-show="translating" 
                        class="absolute inset-0 bg-white bg-opacity-80 z-10 flex flex-col items-center justify-center"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100" 
                        x-transition:leave-end="opacity-0"
                    >
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="text-2xl font-bold" :class="{'text-blue-600': !$wire.translateRecipe, 'text-gray-400': $wire.translateRecipe}">EN</span>
                            
                            <div class="relative w-16 h-16">
                                <svg class="animate-spin h-16 w-16 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                            </div>
                            
                            <span class="text-2xl font-bold" :class="{'text-blue-600': $wire.translateRecipe, 'text-gray-400': !$wire.translateRecipe}">PL</span>
                        </div>
                        
                        <p class="text-xl font-bold text-blue-600 mb-1">{{ __('nutrition_calculator.translating') }}</p>
                        <p class="text-gray-600">{{ __('nutrition_calculator.please_wait') }}</p>
                        
                        <!-- Indykator postępu tłumaczenia -->
                        <div class="w-64 h-3 bg-gray-200 rounded-full mt-4 overflow-hidden">
                            <div 
                                class="h-full bg-blue-500 rounded-full" 
                                :class="{
                                    'w-1/3': titleLoading,
                                    'w-2/3': !titleLoading && (instructionsLoading || ingredientsLoading),
                                    'w-full': !titleLoading && !instructionsLoading && !ingredientsLoading && translating
                                }"
                                x-transition:enter="transition-all ease-out duration-1000"
                                x-transition:enter-start="w-0"
                                x-transition:enter-end="w-full"
                            ></div>
                        </div>
                    </div>
                    
                    <!-- Zawartość modala z tłumaczonymi elementami -->
                    <div 
                        x-show="translationComplete" 
                        x-transition:enter="transition-opacity ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        <div class="flex justify-between items-start">
                            <div class="relative">
                                <!-- Tytuł przepisu -->
                                <h3 class="text-2xl font-bold text-gray-900" x-show="!titleLoading">
                                    @if($translateRecipe && $translatedTitle)
                                        {{ $translatedTitle }}
                                    @else
                                        {{ $selectedRecipe['title'] }}
                                    @endif
                                </h3>
                                
                                <!-- Animacja ładowania tytułu -->
                                <div 
                                    x-show="titleLoading" 
                                    class="flex items-center space-x-2 py-2"
                                >
                                    <div class="h-6 w-6">
                                        <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    <div class="h-8 bg-gray-200 rounded w-64 animate-pulse"></div>
                                </div>
                            </div>
                            
                            <button wire:click="closeRecipeModal" class="text-gray-400 hover:text-gray-500 focus:outline-none z-20">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex justify-end mt-2">
                            @if(App::getLocale() === 'pl' || $translateRecipe)
                            <button 
                                wire:click="toggleRecipeTranslation" 
                                class="inline-flex items-center text-sm font-medium {{ $translateRecipe ? 'text-blue-600' : 'text-gray-600' }} hover:text-blue-500 focus:outline-none"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                </svg>
                                <span>
                                    {{ $translateRecipe ? __('nutrition_calculator.show_original') : __('nutrition_calculator.translate_to_polish') }}
                                </span>
                            </button>
                            @endif
                        </div>
                        


                        <div class="mt-4 grid grid-cols-1 md:grid-cols-5 gap-6">
                            <!-- Left side: Image and nutrition -->
                            <div class="md:col-span-2">
                                @if(isset($selectedRecipe['image']))
                                    <img src="{{ $selectedRecipe['image'] }}" alt="{{ $selectedRecipe['title'] }}" class="w-full h-auto rounded-lg shadow-md">
                                @endif

                                <div class="mt-4 bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-semibold text-lg mb-2">{{ __('nutrition_calculator.nutrition_info') }}</h4>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        @if(isset($selectedRecipe['nutrition']['nutrients']))
                                            @foreach(collect($selectedRecipe['nutrition']['nutrients'])->take(8) as $nutrient)
                                                <div class="flex justify-between">
                                                    <span class="text-sm">{{ $nutrient['name'] }}:</span>
                                                    <span class="text-sm font-medium">{{ round($nutrient['amount'], 1) }} {{ $nutrient['unit'] }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    
                                    <div class="mt-3 pt-3 border-t border-blue-200">
                                        <div class="flex justify-between">
                                            <span>{{ __('nutrition_calculator.servings') }}:</span>
                                            <span class="font-medium">{{ $selectedRecipe['servings'] ?? 1 }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>{{ __('nutrition_calculator.preparation_time') }}:</span>
                                            <span class="font-medium">{{ $selectedRecipe['readyInMinutes'] ?? '?' }} min</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Right side: Ingredients and instructions -->
                            <div class="md:col-span-3">
                                <!-- Ingredients section -->
                                <div class="mb-6">
                                    <h4 class="font-semibold text-lg mb-2">{{ __('nutrition_calculator.ingredients') }}</h4>
                                    
                                    <!-- Animacja ładowania składników -->
                                    <div x-show="ingredientsLoading" class="py-4">
                                        <div class="flex items-center mb-2">
                                            <svg class="animate-spin h-5 w-5 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ __('nutrition_calculator.translating_ingredients') }}</span>
                                        </div>
                                        
                                        <ul class="list-disc pl-5 space-y-1">
                                            @for($i = 0; $i < 5; $i++)
                                                <li class="h-5 bg-gray-200 rounded w-full animate-pulse mb-2"></li>
                                            @endfor
                                        </ul>
                                    </div>
                                    
                                    <!-- Lista składników -->
                                    <ul class="list-disc pl-5 space-y-1" x-show="!ingredientsLoading">
                                        @if(isset($selectedRecipe['extendedIngredients']) && is_array($selectedRecipe['extendedIngredients']) && count($selectedRecipe['extendedIngredients']) > 0)
                                            @foreach($selectedRecipe['extendedIngredients'] as $index => $ingredient)
                                                <li class="text-gray-700">
                                                    @if($translateRecipe && isset($translatedIngredients[$index]))
                                                        {{ $translatedIngredients[$index] }}
                                                    @elseif(isset($ingredient['original']))
                                                        {{ $ingredient['original'] }}
                                                    @else
                                                        {{ $ingredient['amount'] ?? '' }} {{ $ingredient['unit'] ?? '' }} {{ $ingredient['name'] ?? '' }}
                                                    @endif
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="text-gray-500 italic">{{ __('nutrition_calculator.no_ingredients') }}</li>
                                        @endif
                                    </ul>
                                </div>
                                
                                <!-- Instructions section -->
                                <div>
                                    <h4 class="font-semibold text-lg mb-2">{{ __('nutrition_calculator.instructions') }}</h4>
                                    
                                    <!-- Animacja ładowania instrukcji -->
                                    <div x-show="instructionsLoading" class="py-4">
                                        <div class="flex items-center mb-2">
                                            <svg class="animate-spin h-5 w-5 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-sm text-gray-600">{{ __('nutrition_calculator.translating_instructions') }}</span>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @for($i = 0; $i < 4; $i++)
                                                <div class="h-20 bg-gray-200 rounded w-full animate-pulse"></div>
                                            @endfor
                                        </div>
                                    </div>
                                    
                                    <!-- Instrukcje przepisu -->
                                    <div x-show="!instructionsLoading">
                                        @if($translateRecipe && $translatedInstructions)
                                            <div class="text-gray-700">
                                                {!! $translatedInstructions !!}
                                            </div>
                                        @elseif(isset($selectedRecipe['analyzedInstructions']) && is_array($selectedRecipe['analyzedInstructions']) && count($selectedRecipe['analyzedInstructions']) > 0 && isset($selectedRecipe['analyzedInstructions'][0]['steps']))
                                            <ol class="list-decimal pl-5 space-y-2">
                                                @foreach($selectedRecipe['analyzedInstructions'][0]['steps'] as $step)
                                                    <li class="text-gray-700">{{ $step['step'] }}</li>
                                                @endforeach
                                            </ol>
                                        @elseif(isset($selectedRecipe['instructions']) && !empty($selectedRecipe['instructions']))
                                            <div class="text-gray-700">
                                                {!! nl2br(e($selectedRecipe['instructions'])) !!}
                                            </div>
                                        @else
                                            <p class="text-gray-500 italic">{{ __('nutrition_calculator.no_instructions') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button wire:click="closeRecipeModal" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md font-medium text-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition-colors duration-300 z-20">
                        {{ __('nutrition_calculator.close') }}
                    </button>
                </div>
                
                @if(config('app.debug'))
                <div class="border-t border-gray-200 px-6 py-4">
                    <p class="text-xs text-gray-500 mb-2">Debug information (only visible in development mode):</p>
                    <div class="bg-gray-800 text-gray-200 p-3 rounded text-xs overflow-auto max-h-40">
                        <strong>Recipe data keys:</strong> {{ implode(', ', array_keys($selectedRecipe)) }}<br>
                        <strong>Has instructions:</strong> {{ isset($selectedRecipe['instructions']) ? 'Yes' : 'No' }}<br>
                        <strong>Has analyzedInstructions:</strong> {{ isset($selectedRecipe['analyzedInstructions']) ? 'Yes' : 'No' }}<br>
                        <strong>Has extendedIngredients:</strong> {{ isset($selectedRecipe['extendedIngredients']) ? 'Yes' : 'No' }}<br>
                        <strong>Data structure:</strong> <pre>{{ json_encode($selectedRecipe, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

</div>
