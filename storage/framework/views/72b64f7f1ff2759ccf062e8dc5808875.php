<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center"><?php echo e(__('meal_planner.title')); ?></h1>
        
        <!-- Kalendarz tygodniowy -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold mb-4"><?php echo e(__('meal_planner.weekly_calendar')); ?></h2>
            
            <!-- Nawigacja tygodnia -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
                <?php
                    $isCurrentWeek = $currentWeekStart->isSameDay(\Carbon\Carbon::now());
                ?>
                <button 
                    wire:click="previousWeek" 
                    class="flex items-center justify-center w-full sm:w-auto px-4 py-2 rounded-md transition-colors
                        <?php echo e($isCurrentWeek ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 hover:bg-gray-300'); ?>"
                    <?php echo e($isCurrentWeek ? 'disabled' : ''); ?>

                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="hidden sm:inline"><?php echo e(__('meal_planner.previous_week')); ?></span>
                    <span class="sm:hidden"><?php echo e(__('meal_planner.previous')); ?></span>
                </button>
                
                <h3 class="text-base sm:text-lg font-medium text-center">
                    <?php echo e($currentWeekStart->format('d.m.Y')); ?> - <?php echo e($currentWeekStart->copy()->endOfWeek()->format('d.m.Y')); ?>

                </h3>
                
                <button wire:click="nextWeek" class="flex items-center justify-center w-full sm:w-auto px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors">
                    <span class="hidden sm:inline"><?php echo e(__('meal_planner.next_week')); ?></span>
                    <span class="sm:hidden"><?php echo e(__('meal_planner.next')); ?></span>
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Kalendarz dni -->
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-2">
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
                        <div class="text-xs sm:text-sm font-medium text-gray-600 mb-2">
                            <?php echo e(__('meal_planner.days.' . strtolower($date->format('l')))); ?>

                        </div>
                        <button 
                            wire:click="selectDate('<?php echo e($dateString); ?>')"
                            class="w-full p-2 sm:p-3 rounded-lg border-2 transition-all duration-200 relative
                                <?php echo e($isPast ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed' : 'hover:border-blue-300'); ?>

                                <?php echo e($isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200'); ?>

                                <?php echo e($isToday && !$isSelected ? 'border-green-400 bg-green-50' : ''); ?>"
                            <?php echo e($isPast ? 'disabled' : ''); ?>

                        >
                            <div class="text-base sm:text-lg font-semibold"><?php echo e($date->day); ?></div>
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
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 space-y-2 sm:space-y-0">
                    <h2 class="text-xl font-semibold"><?php echo e(__('meal_planner.saved_meals')); ?> - <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d.m.Y')); ?></h2>
                    <button 
                        wire:click="deletePlanFromDate('<?php echo e($selectedDate); ?>')"
                        class="w-full sm:w-auto px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium"
                    >
                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <?php echo e(__('meal_planner.delete_whole_plan')); ?>

                    </button>
                </div>
                
                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $savedPlans[$selectedDate]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $meal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <!--[if BLOCK]><![endif]--><?php if(isset($meal['image'])): ?>
                                    <div class="flex justify-center sm:justify-start">
                                        <img src="<?php echo e($meal['image']); ?>" alt="<?php echo e($meal['title']); ?>" class="w-20 h-20 object-cover rounded-lg">
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="font-semibold text-lg mb-2"><?php echo e($meal['title']); ?></h3>
                                    <!--[if BLOCK]><![endif]--><?php if(isset($meal['nutrition'])): ?>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-sm text-gray-600">
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium"><?php echo e(__('meal_planner.calories')); ?>:</span> <?php echo e(round($meal['nutrition']['calories'])); ?> kcal
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium"><?php echo e(__('meal_planner.protein')); ?>:</span> <?php echo e(round($meal['nutrition']['protein'])); ?>g
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium"><?php echo e(__('meal_planner.carbs')); ?>:</span> <?php echo e(round($meal['nutrition']['carbs'])); ?>g
                                            </div>
                                            <div class="bg-gray-50 p-2 rounded">
                                                <span class="font-medium"><?php echo e(__('meal_planner.fat')); ?>:</span> <?php echo e(round($meal['nutrition']['fat'])); ?>g
                                            </div>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="flex flex-col sm:flex-row justify-center sm:justify-end space-y-2 sm:space-y-0 sm:space-x-3">
                                    <button 
                                        wire:click="viewRecipeDetails(<?php echo e($meal['id']); ?>)"
                                        class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                                        wire:loading.attr="disabled"
                                        wire:target="viewRecipeDetails(<?php echo e($meal['id']); ?>)"
                                    >
                                        <span wire:loading.remove wire:target="viewRecipeDetails(<?php echo e($meal['id']); ?>)"><?php echo e(__('meal_planner.see_details')); ?></span>
                                        <span wire:loading wire:target="viewRecipeDetails(<?php echo e($meal['id']); ?>)"><?php echo e(__('meal_planner.loading')); ?></span>
                                    </button>
                                    <button 
                                        wire:click="removeMealFromPlan('<?php echo e($selectedDate); ?>', <?php echo e($index); ?>)"
                                        class="w-full sm:w-auto px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-medium"
                                        wire:loading.attr="disabled"
                                        wire:target="removeMealFromPlan('<?php echo e($selectedDate); ?>', <?php echo e($index); ?>)"
                                    >
                                        <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span wire:loading.remove wire:target="removeMealFromPlan('<?php echo e($selectedDate); ?>', <?php echo e($index); ?>)"><?php echo e(__('meal_planner.remove')); ?></span>
                                        <span wire:loading wire:target="removeMealFromPlan('<?php echo e($selectedDate); ?>', <?php echo e($index); ?>)"><?php echo e(__('meal_planner.removing')); ?></span>
                                    </button>
                                </div>
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
            
            <!--[if BLOCK]><![endif]--><?php if(auth()->guard()->guest()): ?>
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-blue-800"><?php echo e(__('meal_planner.login_required_info')); ?></span>
                    </div>
                    <div class="mt-3">
                        <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <?php echo e(__('Login')); ?>

                        </a>
                    </div>
                </div>
            <?php else: ?>
                <!--[if BLOCK]><![endif]--><?php if($selectedDate): ?>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600"><?php echo e(__('meal_planner.select_day')); ?>: <span class="font-semibold"><?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d.m.Y')); ?></span></p>
                    </div>
                    
                    <button 
                        wire:click="generateMealPlan" 
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="generateMealPlan"
                    >
                        <span wire:loading.remove wire:target="generateMealPlan"><?php echo e(__('meal_planner.generate')); ?></span>
                        <span wire:loading wire:target="generateMealPlan"><?php echo e(__('meal_planner.generating')); ?></span>
                    </button>
                <?php else: ?>
                    <p class="text-gray-500"><?php echo e(__('meal_planner.select_day')); ?></p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
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
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <!--[if BLOCK]><![endif]--><?php if(isset($meal['image'])): ?>
                                    <div class="flex justify-center sm:justify-start">
                                        <img src="<?php echo e($meal['image']); ?>" alt="<?php echo e($meal['title']); ?>" class="w-20 h-20 object-cover rounded-lg">
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="font-semibold text-lg mb-2"><?php echo e($meal['title']); ?></h3>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <!--[if BLOCK]><![endif]--><?php if(isset($meal['readyInMinutes'])): ?>
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?php echo e($meal['readyInMinutes']); ?> <?php echo e(__('meal_planner.time_min')); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if(isset($meal['servings'])): ?>
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <?php echo e($meal['servings']); ?> <?php echo e(__('meal_planner.servings')); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                <div class="flex justify-center sm:justify-end">
                                    <button 
                                        wire:click="viewRecipeDetails(<?php echo e($meal['id']); ?>)"
                                        class="w-full sm:w-auto px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                                        wire:loading.attr="disabled"
                                        wire:target="viewRecipeDetails(<?php echo e($meal['id']); ?>)"
                                    >
                                        <span wire:loading.remove wire:target="viewRecipeDetails(<?php echo e($meal['id']); ?>)"><?php echo e(__('meal_planner.see_details')); ?></span>
                                        <span wire:loading wire:target="viewRecipeDetails(<?php echo e($meal['id']); ?>)"><?php echo e(__('meal_planner.loading')); ?></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if($selectedDate): ?>
                    <div class="text-center">
                        <button 
                            wire:click="savePlanToDate('<?php echo e($selectedDate); ?>')"
                            class="w-full sm:w-auto px-8 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium"
                            wire:loading.attr="disabled"
                            wire:target="savePlanToDate('<?php echo e($selectedDate); ?>')"
                        >
                            <svg class="w-5 h-5 mr-2 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                            </svg>
                            <span wire:loading.remove wire:target="savePlanToDate('<?php echo e($selectedDate); ?>')"><?php echo e(__('meal_planner.save_on')); ?> <?php echo e(\Carbon\Carbon::parse($selectedDate)->format('d.m.Y')); ?></span>
                            <span wire:loading wire:target="savePlanToDate('<?php echo e($selectedDate); ?>')"><?php echo e(__('meal_planner.saving')); ?></span>
                        </button>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <!-- Szczegóły przepisu -->
        <!--[if BLOCK]><![endif]--><?php if($selectedRecipe): ?>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-4 space-y-3 sm:space-y-0">
                    <h2 class="text-xl font-semibold"><?php echo e($selectedRecipe['title']); ?></h2>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <!--[if BLOCK]><![endif]--><?php if($selectedDate): ?>
                            <button 
                                wire:click="addRecipeToPlan(<?php echo e($selectedRecipe['id']); ?>)"
                                class="w-full sm:w-auto px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium"
                                wire:loading.attr="disabled"
                                wire:target="addRecipeToPlan(<?php echo e($selectedRecipe['id']); ?>)"
                            >
                                <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                <span wire:loading.remove wire:target="addRecipeToPlan(<?php echo e($selectedRecipe['id']); ?>)"><?php echo e(__('meal_planner.add_to_plan')); ?></span>
                                <span wire:loading wire:target="addRecipeToPlan(<?php echo e($selectedRecipe['id']); ?>)"><?php echo e(__('meal_planner.adding')); ?></span>
                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <button 
                            wire:click="$set('selectedRecipe', null)"
                            class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                        >
                            <?php echo e(__('meal_planner.back_to_list')); ?>

                        </button>
                    </div>
                </div>
                
                <!--[if BLOCK]><![endif]--><?php if(!$selectedDate): ?>
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <span class="text-yellow-800"><?php echo e(__('meal_planner.select_date_to_add')); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
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
            
            <form wire:submit.prevent="searchRecipes" class="mb-4">
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
                    <input 
                        type="text" 
                        wire:model="searchQuery"
                        placeholder="<?php echo e(__('meal_planner.search_placeholder')); ?>"
                        class="w-full sm:flex-1 border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <button 
                        type="submit"
                        class="w-full sm:w-auto px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="searchRecipes"
                    >
                        <span wire:loading.remove wire:target="searchRecipes"><?php echo e(__('meal_planner.search')); ?></span>
                        <span wire:loading wire:target="searchRecipes"><?php echo e(__('meal_planner.searching')); ?></span>
                    </button>
                </div>
            </form>
            
            <!--[if BLOCK]><![endif]--><?php if(!empty($searchResults)): ?>
                <!--[if BLOCK]><![endif]--><?php if(!$selectedDate): ?>
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <span class="text-yellow-800"><?php echo e(__('meal_planner.select_date_to_add')); ?></span>
                        </div>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <div class="space-y-4">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                                <!--[if BLOCK]><![endif]--><?php if(isset($recipe['image'])): ?>
                                    <div class="flex justify-center sm:justify-start">
                                        <img src="<?php echo e($recipe['image']); ?>" alt="<?php echo e($recipe['title']); ?>" class="w-24 h-24 object-cover rounded-lg">
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="font-semibold text-lg mb-2"><?php echo e($recipe['title']); ?></h3>
                                    <div class="text-sm text-gray-600 space-y-1">
                                        <!--[if BLOCK]><![endif]--><?php if(isset($recipe['readyInMinutes'])): ?>
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <?php echo e($recipe['readyInMinutes']); ?> <?php echo e(__('meal_planner.time_min')); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <!--[if BLOCK]><![endif]--><?php if(isset($recipe['servings'])): ?>
                                            <div class="flex items-center justify-center sm:justify-start">
                                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                                <?php echo e($recipe['servings']); ?> <?php echo e(__('meal_planner.servings')); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                </div>
                                <div class="flex flex-col sm:flex-row justify-center sm:justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                                    <button 
                                        wire:click="viewRecipeDetails(<?php echo e($recipe['id']); ?>)"
                                        class="w-full sm:w-auto px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium"
                                        wire:loading.attr="disabled"
                                        wire:target="viewRecipeDetails(<?php echo e($recipe['id']); ?>)"
                                    >
                                        <span wire:loading.remove wire:target="viewRecipeDetails(<?php echo e($recipe['id']); ?>)"><?php echo e(__('meal_planner.see_details')); ?></span>
                                        <span wire:loading wire:target="viewRecipeDetails(<?php echo e($recipe['id']); ?>)"><?php echo e(__('meal_planner.loading')); ?></span>
                                    </button>
                                    <!--[if BLOCK]><![endif]--><?php if($selectedDate): ?>
                                        <button 
                                            wire:click="addRecipeToPlan(<?php echo e($recipe['id']); ?>)"
                                            class="w-full sm:w-auto px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors font-medium"
                                            wire:loading.attr="disabled"
                                            wire:target="addRecipeToPlan(<?php echo e($recipe['id']); ?>)"
                                        >
                                            <svg class="w-4 h-4 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                            </svg>
                                            <span wire:loading.remove wire:target="addRecipeToPlan(<?php echo e($recipe['id']); ?>)"><?php echo e(__('meal_planner.add_to_plan')); ?></span>
                                            <span wire:loading wire:target="addRecipeToPlan(<?php echo e($recipe['id']); ?>)"><?php echo e(__('meal_planner.adding')); ?></span>
                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
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