<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center"><?php echo e(__('meal_planner.title')); ?></h1>
        
        <!-- Kalendarz tygodniowy -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.weekly_calendar')); ?></h2>
            
            <!-- Nawigacja tygodnia -->
            <div class="flex justify-between items-center mb-6">
                <button wire:click="previousWeek" class="flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <?php echo e(__('meal_planner.previous_week')); ?>

                </button>
                
                <h3 class="text-lg font-medium">
                    <?php echo e($currentWeekStart->format('d.m.Y')); ?> - <?php echo e($currentWeekStart->copy()->endOfWeek()->format('d.m.Y')); ?>

                </h3>
                
                <button wire:click="nextWeek" class="flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors">
                    <?php echo e(__('meal_planner.next_week')); ?>

                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Kalendarz dni -->
            <div class="grid grid-cols-7 gap-2">
                <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < 7; $i++): ?>
                    <?php
                        $date = $currentWeekStart->copy()->addDays($i);
                        $dateString = $date->format('Y-m-d');
                        $isToday = $date->isToday();
                        $isPast = $date->isPast() && !$date->isToday();
                        $isSelected = $dateString === $selectedDate;
                        $hasSavedPlan = isset($savedPlans[$dateString]) && count($savedPlans[$dateString]) > 0;
                    ?>
                    
                    <div class="text-center">
                        <div class="text-sm font-medium text-gray-600 mb-2">
                            <?php echo e(__('meal_planner.days.' . strtolower($date->format('l')))); ?>

                        </div>
                        <button 
                            wire:click="selectDate('<?php echo e($dateString); ?>')"
                            class="w-full p-3 rounded-lg border-2 transition-all duration-200 relative
                                <?php echo e($isPast ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'hover:border-blue-300'); ?>

                                <?php echo e($isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200'); ?>

                                <?php echo e($isToday && !$isSelected ? 'border-green-400 bg-green-50' : ''); ?>"
                            <?php echo e($isPast ? 'disabled' : ''); ?>

                        >
                            <div class="text-lg font-semibold"><?php echo e($date->day); ?></div>
                            <!--[if BLOCK]><![endif]--><?php if($hasSavedPlan): ?>
                                <div class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></div>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php echo e(count($savedPlans[$dateString])); ?> <?php echo e(__('meal_planner.meals_count')); ?>

                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </button>
                    </div>
                <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Wybór daty przez input -->
            <div class="mt-4">
                <label for="dateInput" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('meal_planner.selected_day')); ?>:</label>
                <input 
                    type="date" 
                    id="dateInput"
                    wire:model.live="selectedDate"
                    min="<?php echo e(now()->format('Y-m-d')); ?>"
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>
        </div>

        <!-- Zapisane posiłki na wybrany dzień -->
        <!--[if BLOCK]><![endif]--><?php if($selectedDate && isset($savedPlans[$selectedDate]) && count($savedPlans[$selectedDate]) > 0): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.saved_meals')); ?> - <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d.m.Y')); ?></h2>
                
                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $savedPlans[$selectedDate]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg"><?php echo e($meal['title']); ?></h3>
                                    <!--[if BLOCK]><![endif]--><?php if(isset($meal['nutrition'])): ?>
                                        <div class="grid grid-cols-4 gap-4 mt-2 text-sm text-gray-600">
                                            <div><?php echo e(__('meal_planner.calories')); ?>: <?php echo e(round($meal['nutrition']['calories'])); ?> kcal</div>
                                            <div><?php echo e(__('meal_planner.protein')); ?>: <?php echo e(round($meal['nutrition']['protein'])); ?>g</div>
                                            <div><?php echo e(__('meal_planner.carbs')); ?>: <?php echo e(round($meal['nutrition']['carbs'])); ?>g</div>
                                            <div><?php echo e(__('meal_planner.fat')); ?>: <?php echo e(round($meal['nutrition']['fat'])); ?>g</div>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <button 
                                    wire:click="deletePlanFromDate('<?php echo e($selectedDate); ?>')"
                                    class="ml-4 px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors"
                                >
                                    <?php echo e(__('meal_planner.delete')); ?>

                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        <?php elseif($selectedDate): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.saved_meals')); ?> - <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d.m.Y')); ?></h2>
                <p class="text-gray-500"><?php echo e(__('meal_planner.no_saved_meals')); ?></p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Generator planu posiłków -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.generate_plan')); ?></h2>
            
            <!--[if BLOCK]><![endif]--><?php if($selectedDate): ?>
                <div class="mb-4">
                    <p class="text-sm text-gray-600"><?php echo e(__('meal_planner.select_day')); ?>: <span class="font-semibold"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d.m.Y')); ?></span></p>
                </div>
                
                <button 
                    wire:click="generateMealPlan" 
                    class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove><?php echo e(__('meal_planner.generate')); ?></span>
                    <span wire:loading><?php echo e(__('meal_planner.generating')); ?></span>
                </button>
            <?php else: ?>
                <p class="text-gray-500"><?php echo e(__('meal_planner.select_day')); ?></p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <!-- Komunikaty -->
            <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
                <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <?php if(session()->has('error')): ?>
                <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <!-- Wygenerowany plan -->
        <!--[if BLOCK]><![endif]--><?php if(!empty($generatedMeals)): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.generated_plan')); ?></h2>
                
                <div class="space-y-4 mb-6">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $generatedMeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-4">
                                <!--[if BLOCK]><![endif]--><?php if(isset($meal['image'])): ?>
                                    <img src="<?php echo e($meal['image']); ?>" alt="<?php echo e($meal['title']); ?>" class="w-16 h-16 object-cover rounded">
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="flex-1">
                                    <h3 class="font-semibold"><?php echo e($meal['title']); ?></h3>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <!--[if BLOCK]><![endif]--><?php if(isset($meal['readyInMinutes'])): ?>
                                            <span class="mr-4"><?php echo e($meal['readyInMinutes']); ?> <?php echo e(__('meal_planner.time_min')); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if(isset($meal['servings'])): ?>
                                            <span><?php echo e($meal['servings']); ?> <?php echo e(__('meal_planner.servings')); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                <button 
                                    wire:click="viewRecipeDetails(<?php echo e($meal['id']); ?>)"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
                                >
                                    <?php echo e(__('meal_planner.see_details')); ?>

                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if($selectedDate): ?>
                    <button 
                        wire:click="savePlanToDate('<?php echo e($selectedDate); ?>')"
                        class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors"
                    >
                        <?php echo e(__('meal_planner.save_on')); ?> <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d.m.Y')); ?>

                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Szczegóły przepisu -->
        <!--[if BLOCK]><![endif]--><?php if($selectedRecipe): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-semibold"><?php echo e($selectedRecipe['title']); ?></h2>
                    <button 
                        wire:click="$set('selectedRecipe', null)"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
                    >
                        <?php echo e(__('meal_planner.back_to_list')); ?>

                    </button>
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['image'])): ?>
                    <img src="<?php echo e($selectedRecipe['image']); ?>" alt="<?php echo e($selectedRecipe['title']); ?>" class="w-full max-w-md mx-auto rounded-lg mb-6">
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!-- Informacje podstawowe -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['readyInMinutes'])): ?>
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($selectedRecipe['readyInMinutes']); ?></div>
                            <div class="text-sm text-gray-600"><?php echo e(__('meal_planner.time_min')); ?></div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['servings'])): ?>
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <div class="text-2xl font-bold text-green-600"><?php echo e($selectedRecipe['servings']); ?></div>
                            <div class="text-sm text-gray-600"><?php echo e(__('meal_planner.servings')); ?></div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['nutrition']['calories'])): ?>
                        <div class="text-center p-4 bg-gray-50 rounded">
                            <div class="text-2xl font-bold text-orange-600"><?php echo e(round($selectedRecipe['nutrition']['calories'])); ?></div>
                            <div class="text-sm text-gray-600"><?php echo e(__('meal_planner.calories')); ?></div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!-- Wartości odżywcze -->
                <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['nutrition'])): ?>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-3"><?php echo e(__('meal_planner.basic_info')); ?></h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['nutrition']['protein'])): ?>
                                <div class="text-center p-3 bg-blue-50 rounded">
                                    <div class="font-bold text-blue-600"><?php echo e(round($selectedRecipe['nutrition']['protein'])); ?>g</div>
                                    <div class="text-sm text-gray-600"><?php echo e(__('meal_planner.protein')); ?></div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['nutrition']['carbs'])): ?>
                                <div class="text-center p-3 bg-green-50 rounded">
                                    <div class="font-bold text-green-600"><?php echo e(round($selectedRecipe['nutrition']['carbs'])); ?>g</div>
                                    <div class="text-sm text-gray-600"><?php echo e(__('meal_planner.carbs')); ?></div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['nutrition']['fat'])): ?>
                                <div class="text-center p-3 bg-yellow-50 rounded">
                                    <div class="font-bold text-yellow-600"><?php echo e(round($selectedRecipe['nutrition']['fat'])); ?>g</div>
                                    <div class="text-sm text-gray-600"><?php echo e(__('meal_planner.fat')); ?></div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!-- Składniki -->
                <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['extendedIngredients']) && count($selectedRecipe['extendedIngredients']) > 0): ?>
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold"><?php echo e(__('meal_planner.ingredients')); ?></h3>
                            <!--[if BLOCK]><![endif]--><?php if(app()->getLocale() === 'pl' && !$translatedIngredients): ?>
                                <button 
                                    wire:click="translateIngredients"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors"
                                    wire:loading.attr="disabled"
                                >
                                    <span wire:loading.remove wire:target="translateIngredients"><?php echo e(__('meal_planner.translate_to_polish')); ?></span>
                                    <span wire:loading wire:target="translateIngredients"><?php echo e(__('meal_planner.translating')); ?></span>
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <ul class="space-y-2">
                            <!--[if BLOCK]><![endif]--><?php if($translatedIngredients && app()->getLocale() === 'pl'): ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $translatedIngredients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                        <?php echo e($ingredient); ?>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $selectedRecipe['extendedIngredients']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ingredient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="flex items-center">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3"></span>
                                        <?php echo e($ingredient['original']); ?>

                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </ul>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!-- Instrukcje -->
                <!--[if BLOCK]><![endif]--><?php if(isset($selectedRecipe['instructions']) && !empty($selectedRecipe['instructions'])): ?>
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold"><?php echo e(__('meal_planner.instructions')); ?></h3>
                            <?php if(app()->getLocale() === 'pl' && !$translatedInstructions): ?>
                                <button 
                                    wire:click="translateInstructions"
                                    class="px-3 py-1 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 transition-colors"
                                    wire:loading.attr="disabled"
                                >
                                    <span wire:loading.remove wire:target="translateInstructions"><?php echo e(__('meal_planner.translate_to_polish')); ?></span>
                                    <span wire:loading wire:target="translateInstructions"><?php echo e(__('meal_planner.translating')); ?></span>
                                </button>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <div class="prose max-w-none">
                            <!--[if BLOCK]><![endif]--><?php if($translatedInstructions && app()->getLocale() === 'pl'): ?>
                                <?php echo nl2br(e($translatedInstructions)); ?>

                            <?php else: ?>
                                <?php echo $selectedRecipe['instructions']; ?>

                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Wyszukiwanie przepisów -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.search_recipes')); ?></h2>
            
            <div class="flex space-x-4 mb-4">
                <input 
                    type="text" 
                    wire:model="searchQuery"
                    placeholder="<?php echo e(__('meal_planner.search_placeholder')); ?>"
                    class="flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <button 
                    wire:click="searchRecipes"
                    class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove><?php echo e(__('meal_planner.search')); ?></span>
                    <span wire:loading><?php echo e(__('meal_planner.searching')); ?></span>
                </button>
            </div>
            
            <!--[if BLOCK]><![endif]--><?php if(!empty($searchResults)): ?>
                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center space-x-4">
                                <!--[if BLOCK]><![endif]--><?php if(isset($recipe['image'])): ?>
                                    <img src="<?php echo e($recipe['image']); ?>" alt="<?php echo e($recipe['title']); ?>" class="w-16 h-16 object-cover rounded">
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="flex-1">
                                    <h3 class="font-semibold"><?php echo e($recipe['title']); ?></h3>
                                    <div class="text-sm text-gray-600 mt-1">
                                        <!--[if BLOCK]><![endif]--><?php if(isset($recipe['readyInMinutes'])): ?>
                                            <span class="mr-4"><?php echo e($recipe['readyInMinutes']); ?> <?php echo e(__('meal_planner.time_min')); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if(isset($recipe['servings'])): ?>
                                            <span><?php echo e($recipe['servings']); ?> <?php echo e(__('meal_planner.servings')); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                <button 
                                    wire:click="viewRecipeDetails(<?php echo e($recipe['id']); ?>)"
                                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition-colors"
                                >
                                    <?php echo e(__('meal_planner.see_details')); ?>

                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php elseif($searchQuery && empty($searchResults) && !$searchLoading): ?>
                <p class="text-gray-500"><?php echo e(__('meal_planner.no_recipes_found')); ?></p>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/meal-planner.blade.php ENDPATH**/ ?>