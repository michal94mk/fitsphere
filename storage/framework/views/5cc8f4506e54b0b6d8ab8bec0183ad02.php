<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6"><?php echo e(__('trainers.reservation_with')); ?>: <?php echo e($trainer->name); ?></h1>
    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm flex items-start" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900"><?php echo e(__('trainers.trainer_info')); ?></h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500"><?php echo e(__('trainers.trainer_details')); ?></p>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($trainer->image): ?>
                    <div class="mt-3 sm:mt-0">
                        <img src="<?php echo e(asset('storage/' . $trainer->image)); ?>" alt="<?php echo e($trainer->name); ?>" class="h-20 w-20 rounded-full object-cover shadow-sm border border-gray-200">
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('trainers.specialization')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($trainer->specialization); ?></dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('trainers.experience')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($trainer->experience); ?> <?php echo e(__('trainers.years')); ?></dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('trainers.about_trainer')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($trainer->description); ?></dd>
                </div>
            </dl>
        </div>
    </div>
    
    <form wire:submit.prevent="createReservation" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900"><?php echo e(__('trainers.reservation_form')); ?></h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500"><?php echo e(__('trainers.select_date_time')); ?></p>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <!-- Calendar and time selection section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Calendar section -->
                <div class="col-span-1 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('trainers.choose_date')); ?></label>
                    <div class="relative">
                        <div class="flex items-center mb-3">
                            <button type="button" wire:click="previousMonth" class="p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span class="text-sm font-medium text-gray-700 mx-2">
                                <?php echo e(\Carbon\Carbon::parse($date)->format('F Y')); ?>

                            </span>
                            <button type="button" wire:click="nextMonth" class="p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium text-gray-500 mb-1">
                            <div>N</div>
                            <div>P</div>
                            <div>W</div>
                            <div>Ś</div>
                            <div>C</div>
                            <div>P</div>
                            <div>S</div>
                        </div>
                        
                        <div class="grid grid-cols-7 gap-1">
                            <?php
                                $startOfMonth = \Carbon\Carbon::parse($date)->startOfMonth();
                                $endOfMonth = \Carbon\Carbon::parse($date)->endOfMonth();
                                $currentDate = \Carbon\Carbon::parse($date);
                                $today = \Carbon\Carbon::today();
                                $firstDayOfWeek = $startOfMonth->copy()->startOfWeek(0); // 0 = Sunday
                                $lastDayOfWeek = $endOfMonth->copy()->endOfWeek(0);
                            ?>
                            
                            <!--[if BLOCK]><![endif]--><?php for($day = $firstDayOfWeek; $day <= $lastDayOfWeek; $day->addDay()): ?>
                                <?php
                                    $isCurrentMonth = $day->month === $startOfMonth->month;
                                    $isToday = $day->isSameDay($today);
                                    $isSelected = $day->isSameDay($currentDate);
                                    $isPast = $day->lt($today);
                                    $isSelectable = $isCurrentMonth && !$isPast;
                                    
                                    $classes = "p-2 text-sm rounded-md flex items-center justify-center cursor-pointer ";
                                    
                                    if (!$isCurrentMonth) {
                                        $classes .= "text-gray-300 hover:bg-gray-50 ";
                                    } elseif ($isSelected) {
                                        $classes .= "bg-blue-600 text-white border border-blue-700 shadow ";
                                    } elseif ($isToday) {
                                        $classes .= "bg-blue-100 text-blue-700 border border-blue-400 ";
                                    } elseif ($isPast) {
                                        $classes .= "text-gray-400 line-through cursor-not-allowed ";
                                    } else {
                                        $classes .= "text-gray-700 hover:bg-blue-50 ";
                                    }
                                ?>
                                
                                <div wire:click="<?php echo e($isSelectable ? 'updateDate(\'' . $day->format('Y-m-d') . '\')' : ''); ?>" 
                                    class="<?php echo e($classes); ?>">
                                    <?php echo e($day->day); ?>

                                </div>
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        
                        <input type="hidden" name="date" id="date" wire:model="date">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- Time slots section -->
                <div class="col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-medium text-gray-700"><?php echo e(__('trainers.choose_hours')); ?></label>
                        <!--[if BLOCK]><![endif]--><?php if($startTime): ?>
                            <button type="button" wire:click="resetTimeSelection" class="inline-flex items-center py-1 px-2.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <?php echo e(__('trainers.clear_selection')); ?>

                            </button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <!-- Past date warning -->
                    <!--[if BLOCK]><![endif]--><?php if($this->isPastDate): ?>
                        <div class="mb-4">
                            <div class="p-4 rounded-md bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm">
                                            <?php echo e(__('trainers.past_date_warning')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif(count($timeSlotGrid) === 0): ?>
                        <div class="mb-4">
                            <div class="p-4 rounded-md bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm">
                                            <?php echo e(__('trainers.no_available_slots')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Selected time info -->
                        <!--[if BLOCK]><![endif]--><?php if($startTime || $endTime): ?>
                            <div class="mb-4 p-3 rounded-md <?php echo e(($startTime && $endTime) ? 'bg-green-50 border-l-4 border-green-500 text-green-700' : 'bg-blue-50 border-l-4 border-blue-400 text-blue-700'); ?>">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 <?php echo e(($startTime && $endTime) ? 'text-green-500' : 'text-blue-400'); ?>" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">
                                            <!--[if BLOCK]><![endif]--><?php if($startTime && $endTime): ?>
                                                <?php echo e(__('trainers.time_selected')); ?>: <span class="font-bold"><?php echo e($startTime); ?> - <?php echo e($endTime); ?></span>
                                                <?php
                                                    $start = \Carbon\Carbon::parse($startTime);
                                                    $end = \Carbon\Carbon::parse($endTime);
                                                    $duration = $start->diffInMinutes($end);
                                                    $hours = floor($duration / 60);
                                                    $minutes = $duration % 60;
                                                ?>
                                                <span class="block text-xs mt-1">
                                                    <?php echo e(__('trainers.time_duration')); ?>: 
                                                    <!--[if BLOCK]><![endif]--><?php if($hours > 0): ?>
                                                        <?php echo e($hours); ?> <?php echo e($hours == 1 ? __('trainers.hour') : __('trainers.hours')); ?>

                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($minutes > 0): ?>
                                                        <!--[if BLOCK]><![endif]--><?php if($hours > 0): ?> i <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        <?php echo e($minutes); ?> <?php echo e($minutes == 1 ? __('trainers.minute') : __('trainers.minutes')); ?>

                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </span>
                                            <?php elseif($startTime): ?>
                                                <?php echo e(__('trainers.start_time_selected')); ?>: <span class="font-bold"><?php echo e($startTime); ?></span>
                                                <span class="block text-xs mt-1"><?php echo e(__('trainers.end_time_now')); ?></span>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!-- Time grid -->
                        <div class="overflow-auto max-h-80 pr-1 custom-scrollbar">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $timeSlotGrid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hourGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="mb-2">
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700"><?php echo e($hourGroup['hour']); ?></span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $hourGroup['slots']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button
                                                    type="button"
                                                    wire:click="<?php echo e($slot['available'] ? 'selectTimeSlot(\'' . $slot['time'] . '\')' : ''); ?>"
                                                    class="py-2 px-2 text-sm text-center rounded-md transition-all duration-150 flex items-center justify-center
                                                        <?php echo e(!$slot['available'] ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : ''); ?>

                                                        <?php echo e($slot['available'] && (!isset($slot['selected']) || !$slot['selected']) && $slot['time'] !== $this->endTime ? 'bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 hover:shadow-sm' : ''); ?>

                                                        <?php echo e($slot['time'] === $this->startTime ? 'bg-blue-600 text-white border border-blue-700 shadow' : ''); ?>

                                                        <?php echo e($slot['time'] === $this->endTime ? 'bg-blue-500 text-white border border-blue-600 shadow' : ''); ?>

                                                        <?php echo e(isset($slot['selected']) && $slot['selected'] && $slot['time'] !== $this->startTime && $slot['time'] !== $this->endTime ? 'bg-blue-100 text-blue-800 border border-blue-300' : ''); ?>"
                                                    <?php echo e(!$slot['available'] ? 'disabled title="' . ($slot['reason'] ?? 'Niedostępny') . '"' : ''); ?>

                                                >
                                                    <?php echo e($slot['time']); ?>

                                                    <!--[if BLOCK]><![endif]--><?php if($slot['time'] === $this->startTime): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                                        </svg>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($slot['time'] === $this->endTime): ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                                                        </svg>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" wire:model="startTime">
                    <input type="hidden" wire:model="endTime">
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['startTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['endTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2"><?php echo e(__('trainers.notes')); ?></label>
                <textarea id="notes" name="notes" rows="3" wire:model="notes" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"
                    placeholder="<?php echo e(__('trainers.notes_placeholder')); ?>"></textarea>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
            <button type="submit" 
                <?php echo e($this->isPastDate || !$startTime || !$endTime ? 'disabled' : ''); ?> 
                class="inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white 
                <?php echo e($this->isPastDate || !$startTime || !$endTime ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <!--[if BLOCK]><![endif]--><?php if(app()->getLocale() == 'pl'): ?>
                    Zarezerwuj termin
                <?php else: ?>
                    Reserve time
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </button>
        </div>
    </form>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }
    </style>
</div>
<?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/create-reservation.blade.php ENDPATH**/ ?>