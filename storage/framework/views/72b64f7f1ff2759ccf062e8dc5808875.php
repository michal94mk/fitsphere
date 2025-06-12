<div>
    <div class="bg-gradient-to-br from-gray-50 to-gray-100 py-8 md:py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-5xl font-extrabold mb-4">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                        <?php echo e(__('meal_planner.title')); ?>

                    </span>
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    <?php echo e(__('meal_planner.subtitle')); ?>

                </p>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informacja o konfiguracji klucza API -->
            <!--[if BLOCK]><![endif]--><?php if(empty(config('services.spoonacular.key')) || config('services.spoonacular.key') === 'your_spoonacular_api_key_here'): ?>
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800"><?php echo e(__('nutrition_calculator.api_key_missing_title')); ?></h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p><?php echo e(__('meal_planner.api_key_missing_message')); ?></p>
                                <ol class="mt-1 ml-4 list-decimal">
                                    <li><?php echo e(__('nutrition_calculator.api_key_step1')); ?> <a href="https://spoonacular.com/food-api" target="_blank" class="underline">spoonacular.com/food-api</a></li>
                                    <li><?php echo e(__('nutrition_calculator.api_key_step2')); ?></li>
                                    <li><?php echo e(__('nutrition_calculator.api_key_step3')); ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-6"><?php echo e(__('meal_planner.title')); ?></h2>
                    
                    <!-- Kalendarz i wybór daty -->
                    <div class="mb-8 bg-gray-50 p-4 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <button wire:click="prevWeek" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                <?php echo e(__('meal_planner.prev_week')); ?>

                            </button>
                            
                            <div class="text-center">
                                <h3 class="text-lg font-semibold"><?php echo e(\Carbon\Carbon::parse($startDate)->format('d.m.Y')); ?> - <?php echo e(\Carbon\Carbon::parse($endDate)->format('d.m.Y')); ?></h3>
                            </div>
                            
                            <button wire:click="nextWeek" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md">
                                <?php echo e(__('meal_planner.next_week')); ?>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1">
                            <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < 7; $i++): ?>
                                <?php
                                    $currentDate = \Carbon\Carbon::parse($startDate)->addDays($i);
                                    $isToday = $currentDate->isToday();
                                    $isSelected = $currentDate->format('Y-m-d') === $date;
                                ?>
                                
                                <button 
                                    wire:click="updateDate('<?php echo e($currentDate->format('Y-m-d')); ?>')"
                                    class="p-3 rounded-md text-center transition-colors <?php echo e($isToday ? 'bg-indigo-100 border border-indigo-300' : 'bg-white'); ?> <?php echo e($isSelected ? 'bg-indigo-200 border border-indigo-400' : ''); ?> hover:bg-indigo-100"
                                >
                                    <div class="text-xs text-gray-500"><?php echo e($currentDate->locale('pl')->dayName); ?></div>
                                    <div class="text-lg font-semibold"><?php echo e($currentDate->day); ?></div>
                                    
                                    <!--[if BLOCK]><![endif]--><?php if(isset($dailyTotals[$currentDate->format('Y-m-d')])): ?>
                                        <div class="mt-1 text-xs text-gray-500"><?php echo e(round($dailyTotals[$currentDate->format('Y-m-d')]['calories'])); ?> kcal</div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </button>
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    
                    <!-- Generator planu posiłków -->
                    <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.generate_plan')); ?></h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('meal_planner.dietary_preferences')); ?></label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="vegetarian" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2"><?php echo e(__('meal_planner.vegetarian')); ?></span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="vegan" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2"><?php echo e(__('meal_planner.vegan')); ?></span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="gluten-free" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2"><?php echo e(__('meal_planner.gluten_free')); ?></span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" wire:model="dietary" value="ketogenic" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2"><?php echo e(__('meal_planner.keto')); ?></span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="excludeIngredients" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('meal_planner.excluded_ingredients')); ?></label>
                                    <input type="text" wire:model="excludeIngredients" id="excludeIngredients" placeholder="<?php echo e(__('meal_planner.excluded_ingredients_placeholder')); ?>" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <p class="mt-1 text-xs text-gray-500"><?php echo e(__('meal_planner.comma_separated')); ?></p>
                                </div>
                                
                                <div>
                                    <button wire:click="generateMealPlan" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <?php echo e(__('meal_planner.generate_plan')); ?>

                                    </button>
                                    
                                    <!-- Message alerts for generate meal plan -->
                                    <!--[if BLOCK]><![endif]--><?php if(session()->has('message')): ?>
                                        <div class="mt-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm" wire:key="meal-success-<?php echo e(time()); ?>">
                                            <?php echo e(session('message')); ?>

                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    
                                    <?php if(session()->has('success')): ?>
                                        <div class="mt-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm" wire:key="meal-success-2-<?php echo e(time()); ?>">
                                            <?php echo e(session('success')); ?>

                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    
                                    <?php if(session()->has('error')): ?>
                                        <div class="mt-3 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm" wire:key="meal-error-<?php echo e(time()); ?>">
                                            <?php echo e(session('error')); ?>

                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                            
                            <div>
                                <!--[if BLOCK]><![endif]--><?php if($loading): ?>
                                    <div class="flex justify-center items-center h-full">
                                        <svg class="animate-spin h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                <?php elseif($generatedPlan && isset($generatedPlan['meals'])): ?>
                                    <div class="bg-white p-4 rounded-md shadow">
                                        <h4 class="text-lg font-semibold mb-3">Wygenerowany plan</h4>
                                        <p class="mb-2 text-sm text-gray-500">Kliknij przepis, aby dodać go do dnia <?php echo e(\Carbon\Carbon::parse($date)->format('d.m.Y')); ?></p>
                                        
                                        <div class="space-y-3">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $generatedPlan['meals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="border rounded-md p-3 hover:bg-gray-50 cursor-pointer" wire:click="selectRecipe(<?php echo e($meal['id']); ?>, '<?php echo e($meal['title']); ?>')">
                                                    <div class="flex items-center">
                                                        <!--[if BLOCK]><![endif]--><?php if(isset($meal['image'])): ?>
                                                            <img src="<?php echo e($meal['image']); ?>" alt="<?php echo e($meal['title']); ?>" class="w-16 h-16 object-cover rounded mr-3">
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        <div>
                                                            <h5 class="font-medium"><?php echo e($meal['title']); ?></h5>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                <span class="mr-2"><?php echo e($meal['readyInMinutes'] ?? '?'); ?> min</span>
                                                                <!--[if BLOCK]><![endif]--><?php if(isset($meal['servings'])): ?>
                                                                    <span><?php echo e($meal['servings']); ?> porcji</span>
                                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                        
                                        <!--[if BLOCK]><![endif]--><?php if(isset($generatedPlan['nutrients'])): ?>
                                            <div class="mt-4 pt-3 border-t">
                                                <h5 class="font-medium mb-2">Wartości odżywcze (dziennie)</h5>
                                                <div class="grid grid-cols-2 gap-2 text-sm">
                                                    <div>Kalorie: <span class="font-semibold"><?php echo e(round($generatedPlan['nutrients']['calories'])); ?> kcal</span></div>
                                                    <div>Białko: <span class="font-semibold"><?php echo e(round($generatedPlan['nutrients']['protein'])); ?> g</span></div>
                                                    <div>Węglowodany: <span class="font-semibold"><?php echo e(round($generatedPlan['nutrients']['carbohydrates'])); ?> g</span></div>
                                                    <div>Tłuszcze: <span class="font-semibold"><?php echo e(round($generatedPlan['nutrients']['fat'])); ?> g</span></div>
                                                </div>
                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>

                    <!-- Panel dodawania przepisu do planu (druga część w kolejnym edicie) -->
                    <!--[if BLOCK]><![endif]--><?php if($selectedRecipe): ?>
                        <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow-sm">
                            <h3 class="text-xl font-semibold mb-4">Dodaj przepis do planu</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['image'])): ?>
                                        <img src="<?php echo e($selectedRecipe['image']); ?>" alt="<?php echo e($selectedRecipe['name']); ?>" class="w-full h-48 object-cover rounded-md mb-4">
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    
                                    <h4 class="text-lg font-semibold mb-2"><?php echo e($selectedRecipe['name']); ?></h4>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['vegetarian']) && $selectedRecipe['vegetarian']): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Wegetariański</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        
                                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['vegan']) && $selectedRecipe['vegan']): ?>
                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Wegański</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        
                                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['glutenFree']) && $selectedRecipe['glutenFree']): ?>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Bezglutenowy</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        
                                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['dairyFree']) && $selectedRecipe['dairyFree']): ?>
                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Bez nabiału</span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <span class="text-sm text-gray-500">Czas przygotowania:</span>
                                            <div class="font-semibold"><?php echo e($selectedRecipe['readyInMinutes'] ?? '?'); ?> min</div>
                                        </div>
                                        
                                        <div>
                                            <span class="text-sm text-gray-500">Porcje:</span>
                                            <div class="font-semibold"><?php echo e($selectedRecipe['servings'] ?? '1'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5 class="font-medium mb-2">Wartości odżywcze (na porcję)</h5>
                                        <div class="grid grid-cols-2 gap-2">
                                            <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['nutrition']['nutrients'])): ?>
                                                <?php
                                                    $calories = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Calories');
                                                    $protein = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Protein');
                                                    $carbs = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Carbohydrates');
                                                    $fat = collect($selectedRecipe['nutrition']['nutrients'])->firstWhere('name', 'Fat');
                                                ?>
                                                
                                                <div>Kalorie: <span class="font-semibold"><?php echo e($calories ? round($calories['amount']) : '?'); ?> kcal</span></div>
                                                <div>Białko: <span class="font-semibold"><?php echo e($protein ? round($protein['amount']) : '?'); ?> g</span></div>
                                                <div>Węglowodany: <span class="font-semibold"><?php echo e($carbs ? round($carbs['amount']) : '?'); ?> g</span></div>
                                                <div>Tłuszcze: <span class="font-semibold"><?php echo e($fat ? round($fat['amount']) : '?'); ?> g</span></div>
                                                
                                                <div class="col-span-2 text-xs text-gray-500 mt-1">
                                                    <span>Uwaga: Wartości odżywcze podane przez API są już w przeliczeniu na porcję.</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="col-span-2 text-gray-500 italic">Brak informacji o wartościach odżywczych</div>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <h5 class="font-medium mb-3"><?php echo e(__('meal_planner.ingredients')); ?></h5>
                                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['extendedIngredients']) && is_array($selectedRecipe['extendedIngredients']) && count($selectedRecipe['extendedIngredients']) > 0): ?>
                                            <ul class="space-y-1 text-sm">
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedRecipe['extendedIngredients']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="text-gray-700 flex items-start">
                                                        <span class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                                        <span><?php echo e($ingredient['original'] ?? ($ingredient['amount'] . ' ' . $ingredient['unit'] . ' ' . $ingredient['name'])); ?></span>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </ul>
                                        <?php else: ?>
                                            <p class="text-gray-500 italic text-sm"><?php echo e(__('meal_planner.no_ingredients')); ?></p>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    
                                    <!-- Instructions section -->
                                    <div class="mb-4">
                                        <h5 class="font-medium mb-3"><?php echo e(__('meal_planner.instructions')); ?></h5>
                                        <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['instructions']) && !empty($selectedRecipe['instructions'])): ?>
                                            <div class="text-sm text-gray-700"><?php echo $selectedRecipe['instructions']; ?></div>
                                        <?php elseif(isset($selectedRecipe['analyzedInstructions']) && is_array($selectedRecipe['analyzedInstructions']) && count($selectedRecipe['analyzedInstructions']) > 0 && isset($selectedRecipe['analyzedInstructions'][0]['steps'])): ?>
                                            <ol class="list-decimal pl-5 space-y-2 text-sm">
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedRecipe['analyzedInstructions'][0]['steps']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="text-gray-700"><?php echo e($step['step'] ?? 'No step description'); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </ol>
                                        <?php else: ?>
                                            <p class="text-gray-500 italic text-sm"><?php echo e(__('meal_planner.no_instructions')); ?></p>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="bg-white p-4 rounded-md shadow">
                                        <h4 class="text-lg font-semibold mb-3"><?php echo e(__('meal_planner.add_to_plan')); ?></h4>
                                        
                                        <div class="mb-4">
                                            <label for="mealType" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('meal_planner.meal_type')); ?></label>
                                            <select wire:model="mealType" id="mealType" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="breakfast"><?php echo e(__('meal_planner.breakfast')); ?></option>
                                                <option value="lunch"><?php echo e(__('meal_planner.lunch')); ?></option>
                                                <option value="dinner"><?php echo e(__('meal_planner.dinner')); ?></option>
                                                <option value="snack"><?php echo e(__('meal_planner.snacks')); ?></option>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="servingSize" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('meal_planner.serving_size_adjustment')); ?></label>
                                            <div class="flex items-center space-x-3">
                                                <input wire:model="servingSize" id="servingSize" type="number" min="0.5" max="10" step="0.5" class="w-20 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                                                <span class="text-sm text-gray-600">
                                                    <?php echo e(__('meal_planner.servings_default')); ?>: <?php echo e($selectedRecipe['servings'] ?? '1'); ?>

                                                </span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1"><?php echo e(__('meal_planner.serving_size_note')); ?></p>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('meal_planner.notes')); ?></label>
                                            <textarea wire:model="notes" id="notes" rows="3" class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                        </div>
                                        
                                        <div class="flex space-x-4">
                                            <button wire:click="saveMealToPlan" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                                <?php echo e(__('meal_planner.add_to_plan')); ?>

                                            </button>
                                            
                                            <button wire:click="$set('selectedRecipe', null)" class="px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                <?php echo e(__('meal_planner.cancel')); ?>

                                            </button>
                                        </div>
                                        
                                        <!-- Add to plan messages -->
                                        <?php if(session()->has('meal_added')): ?>
                                            <div class="mt-3 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 text-sm" wire:key="meal-added-<?php echo e(time()); ?>">
                                                <?php echo e(session('meal_added')); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        
                                        <?php if(session()->has('meal_error')): ?>
                                            <div class="mt-3 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 text-sm" wire:key="meal-add-error-<?php echo e(time()); ?>">
                                                <?php echo e(session('meal_error')); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    
                    <!-- Lista posiłków na wybrany dzień -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold"><?php echo e(__('meal_planner.meals_for')); ?> <?php echo e(\Carbon\Carbon::parse($date)->locale(App::getLocale())->isoFormat('D MMMM YYYY')); ?></h3>
                            
                            <!--[if BLOCK]><![endif]--><?php if(isset($dailyTotals[$date])): ?>
                                <div class="text-sm">
                                    <span class="font-semibold"><?php echo e(__('meal_planner.total_calories')); ?>:</span> <?php echo e(round($dailyTotals[$date]['calories'])); ?> <?php echo e(__('meal_planner.kcal')); ?>

                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <!--[if BLOCK]><![endif]--><?php if(count($mealPlans) > 0): ?>
                            <div class="space-y-4">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $mealPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-white rounded-md shadow p-4">
                                        <div class="flex justify-between">
                                            <h4 class="font-semibold">
                                                <span class="inline-block mr-2">
                                                    <!--[if BLOCK]><![endif]--><?php switch($meal->meal_type):
                                                        case ('breakfast'): ?>
                                                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full"><?php echo e(__('meal_planner.breakfast')); ?></span>
                                                            <?php break; ?>
                                                        
                                                        <?php case ('lunch'): ?>
                                                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full"><?php echo e(__('meal_planner.lunch')); ?></span>
                                                            <?php break; ?>
                                                        
                                                        <?php case ('dinner'): ?>
                                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full"><?php echo e(__('meal_planner.dinner')); ?></span>
                                                            <?php break; ?>
                                                        
                                                        <?php case ('snack'): ?>
                                                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full"><?php echo e(__('meal_planner.snacks')); ?></span>
                                                            <?php break; ?>
                                                    <?php endswitch; ?><!--[if ENDBLOCK]><![endif]-->
                                                </span>
                                                <?php echo e($meal->name); ?>

                                            </h4>
                                            
                                            <div class="flex space-x-2">
                                                <button 
                                                    x-data
                                                    @click="$dispatch('open-delete-modal', { mealId: <?php echo e($meal->id); ?>, mealName: '<?php echo e($meal->name); ?>' })"
                                                    class="text-gray-400 hover:text-red-500" 
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-3">
                                            <div class="sm:col-span-1">
                                                <!--[if BLOCK]><![endif]--><?php if(isset($meal->recipe_data['image'])): ?>
                                                    <img src="<?php echo e($meal->recipe_data['image']); ?>" alt="<?php echo e($meal->name); ?>" class="w-full h-24 object-cover rounded">
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                            
                                            <div class="sm:col-span-3">
                                                <div class="flex flex-wrap gap-4 mb-2 text-sm">
                                                    <div><span class="font-semibold"><?php echo e(__('meal_planner.calories')); ?>:</span> <?php echo e(!is_null($meal->calories) && $meal->calories > 0 ? round($meal->calories) : __('meal_planner.na')); ?> <?php echo e(__('meal_planner.kcal')); ?></div>
                                                    <div><span class="font-semibold"><?php echo e(__('meal_planner.protein')); ?>:</span> <?php echo e(!is_null($meal->protein) && $meal->protein > 0 ? round($meal->protein) : __('meal_planner.na')); ?> <?php echo e(__('meal_planner.g')); ?></div>
                                                    <div><span class="font-semibold"><?php echo e(__('meal_planner.carbs')); ?>:</span> <?php echo e(!is_null($meal->carbs) && $meal->carbs > 0 ? round($meal->carbs) : __('meal_planner.na')); ?> <?php echo e(__('meal_planner.g')); ?></div>
                                                    <div><span class="font-semibold"><?php echo e(__('meal_planner.fat')); ?>:</span> <?php echo e(!is_null($meal->fat) && $meal->fat > 0 ? round($meal->fat) : __('meal_planner.na')); ?> <?php echo e(__('meal_planner.g')); ?></div>
                                                </div>
                                                
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <!--[if BLOCK]><![endif]--><?php if(isset($meal->recipe_data['servings']) && $meal->recipe_data['servings'] > 0): ?>
                                                        <span><?php echo e(__('meal_planner.nutrition_per_serving')); ?> <?php echo e(__('meal_planner.servings')); ?>: <?php echo e($meal->recipe_data['servings']); ?></span>
                                                        <!--[if BLOCK]><![endif]--><?php if(isset($meal->serving_size) && $meal->serving_size != 1): ?>
                                                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                                                <?php echo e(__('meal_planner.actual_serving')); ?>: <?php echo e($meal->serving_size); ?>x
                                                            </span>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <?php else: ?>
                                                        <span><?php echo e(__('meal_planner.nutrition_per_serving')); ?></span>
                                                        <!--[if BLOCK]><![endif]--><?php if(isset($meal->serving_size) && $meal->serving_size != 1): ?>
                                                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">
                                                                <?php echo e(__('meal_planner.actual_serving')); ?>: <?php echo e($meal->serving_size); ?>x
                                                            </span>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                                
                                                <!--[if BLOCK]><![endif]--><?php if($meal->notes): ?>
                                                    <div class="mt-2 text-sm text-gray-600">
                                                        <span class="font-semibold"><?php echo e(__('meal_planner.notes')); ?>:</span> <?php echo e($meal->notes); ?>

                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php else: ?>
                            <div class="bg-white rounded-md shadow p-6 text-center">
                                <p class="text-gray-500"><?php echo e(__('meal_planner.no_meals_planned')); ?></p>
                                <p class="mt-2 text-sm text-gray-400"><?php echo e(__('meal_planner.use_generator')); ?></p>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4">
        <!--[if BLOCK]><![endif]--><?php if(!empty(config('services.spoonacular.key'))): ?>
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
                                        <?php echo e(__('meal_planner.login_required_title')); ?>

                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500" x-text="message || '<?php echo e(__('meal_planner.login_required')); ?>'"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <a href="<?php echo e(route('login')); ?>" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <?php echo e(__('meal_planner.login')); ?>

                            </a>
                            <button 
                                type="button" 
                                @click="open = false" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <?php echo e(__('meal_planner.cancel')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
                                <?php echo e(__('meal_planner.delete_meal_title')); ?>

                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    <?php echo e(__('meal_planner.delete_meal_description')); ?>

                                </p>
                                
                                <div class="mt-4 bg-gray-50 p-4 rounded-md">
                                    <div class="flex items-center text-sm text-gray-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="font-medium"><?php echo e(__('meal_planner.recipe')); ?>:</span>
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
                        <?php echo e(__('meal_planner.delete_meal')); ?>

                    </button>
                    <button 
                        @click="showDeleteModal = false"
                        type="button" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                    >
                        <?php echo e(__('meal_planner.cancel')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/meal-planner.blade.php ENDPATH**/ ?>