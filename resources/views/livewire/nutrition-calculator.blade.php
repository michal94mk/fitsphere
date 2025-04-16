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
                <h2 class="text-2xl font-semibold mb-6">Kalkulator Diety i Makroskładników</h2>
                
                @if (session()->has('message'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('message') }}
                    </div>
                @endif
                
                @if (session()->has('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @error('profile')
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                        {{ $message }}
                    </div>
                @enderror
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Formularz profilu -->
                    <div>
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold mb-4">Twój profil</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="age" class="block text-sm font-medium text-gray-700 mb-1">Wiek</label>
                                    <input type="number" wire:model="age" id="age" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" max="120">
                                </div>
                                
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Płeć</label>
                                    <select wire:model="gender" id="gender" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Wybierz...</option>
                                        <option value="male">Mężczyzna</option>
                                        <option value="female">Kobieta</option>
                                        <option value="other">Inna</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">Waga (kg)</label>
                                    <input type="number" wire:model="weight" id="weight" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" step="0.1">
                                </div>
                                
                                <div>
                                    <label for="height" class="block text-sm font-medium text-gray-700 mb-1">Wzrost (cm)</label>
                                    <input type="number" wire:model="height" id="height" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" min="1" step="0.1">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="activityLevel" class="block text-sm font-medium text-gray-700 mb-1">Poziom aktywności</label>
                                <select wire:model="activityLevel" id="activityLevel" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Wybierz...</option>
                                    <option value="sedentary">Siedzący tryb życia (mało lub brak ćwiczeń)</option>
                                    <option value="light">Lekka aktywność (ćwiczenia 1-3 dni w tygodniu)</option>
                                    <option value="moderate">Umiarkowana aktywność (ćwiczenia 3-5 dni w tygodniu)</option>
                                    <option value="active">Duża aktywność (ćwiczenia 6-7 dni w tygodniu)</option>
                                    <option value="very_active">Bardzo duża aktywność (ciężkie ćwiczenia/praca fizyczna)</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="goal" class="block text-sm font-medium text-gray-700 mb-1">Cel</label>
                                <select wire:model="goal" id="goal" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Wybierz...</option>
                                    <option value="lose">Chcę schudnąć</option>
                                    <option value="maintain">Chcę utrzymać wagę</option>
                                    <option value="gain">Chcę przytyć/zbudować masę mięśniową</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ograniczenia dietetyczne</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="dietaryRestrictions" value="gluten" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Bezglutenowa</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="dietaryRestrictions" value="dairy" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Bez nabiału</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="dietaryRestrictions" value="peanut" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Bez orzeszków</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="dietaryRestrictions" value="vegetarian" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Wegetariańska</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="dietaryRestrictions" value="vegan" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Wegańska</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="dietaryRestrictions" value="sugar" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2">Bez cukru</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="flex space-x-4">
                                <button wire:click="calculateNutrition" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Oblicz zapotrzebowanie
                                </button>
                                
                                <button wire:click="saveProfile" class="px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Zapisz profil
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Wyniki i wyszukiwarka -->
                    <div>
                        @if ($showDietaryInfo)
                            <div class="bg-gray-50 p-6 rounded-lg shadow-sm mb-6">
                                <h3 class="text-xl font-semibold mb-4">Twoje zapotrzebowanie</h3>
                                
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="text-sm text-gray-500">BMI</div>
                                        <div class="text-2xl font-bold">{{ $bmi ?? 'N/A' }}</div>
                                    </div>
                                    
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="text-sm text-gray-500">Kalorie dziennie</div>
                                        <div class="text-2xl font-bold">{{ $dailyCalories ?? 'N/A' }} kcal</div>
                                    </div>
                                </div>
                                
                                <h4 class="text-lg font-semibold mb-2">Makroskładniki</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="text-sm text-gray-500">Białko</div>
                                        <div class="text-xl font-bold">{{ $protein ?? 'N/A' }} g</div>
                                    </div>
                                    
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="text-sm text-gray-500">Węglowodany</div>
                                        <div class="text-xl font-bold">{{ $carbs ?? 'N/A' }} g</div>
                                    </div>
                                    
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="text-sm text-gray-500">Tłuszcze</div>
                                        <div class="text-xl font-bold">{{ $fat ?? 'N/A' }} g</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold mb-4">Wyszukiwarka przepisów</h3>
                            
                            <div class="mb-4">
                                <label for="searchQuery" class="block text-sm font-medium text-gray-700 mb-1">Wyszukaj przepisy</label>
                                <div class="flex">
                                    <input type="text" wire:model="searchQuery" id="searchQuery" placeholder="np. kurczak, pierogi, sałatka..." class="flex-1 rounded-l-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <button wire:click="searchRecipes" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Szukaj
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="dietFilters" class="block text-sm font-medium text-gray-700 mb-1">Dieta (opcjonalnie)</label>
                                <select wire:model="dietFilters" id="dietFilters" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">Dowolna</option>
                                    <option value="vegetarian">Wegetariańska</option>
                                    <option value="vegan">Wegańska</option>
                                    <option value="gluten-free">Bezglutenowa</option>
                                    <option value="ketogenic">Ketogeniczna</option>
                                    <option value="paleo">Paleolityczna</option>
                                    <option value="low-carb">Niskowęglowodanowa</option>
                                    <option value="whole30">Whole30</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="maxCalories" class="block text-sm font-medium text-gray-700 mb-1">Maksymalna liczba kalorii na porcję</label>
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
                                    <h4 class="text-lg font-semibold mb-2">Wyniki wyszukiwania</h4>
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
                                                    <a href="{{ route('meal-planner') }}?recipeId={{ $recipe['id'] }}" class="mt-3 inline-block px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all">
                                                        Dodaj do planu
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
