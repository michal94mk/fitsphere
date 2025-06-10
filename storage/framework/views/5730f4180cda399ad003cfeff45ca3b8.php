<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6"><?php echo e(__('trainers.my_reservations')); ?></h1>
    
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm flex items-start" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <?php if(session()->has('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm flex items-start" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Filter Controls -->
        <div class="bg-gray-50 p-4 border-b border-gray-200">
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                <div class="w-full md:w-1/3">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('trainers.filter_status')); ?></label>
                    <select wire:model="statusFilter" id="status-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value=""><?php echo e(__('trainers.all_statuses')); ?></option>
                        <option value="pending"><?php echo e(__('trainers.pending')); ?></option>
                        <option value="confirmed"><?php echo e(__('trainers.confirmed')); ?></option>
                        <option value="completed"><?php echo e(__('trainers.completed')); ?></option>
                        <option value="cancelled"><?php echo e(__('trainers.cancelled')); ?></option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label for="date-filter" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('trainers.filter_date')); ?></label>
                    <select wire:model="dateFilter" id="date-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value=""><?php echo e(__('trainers.all_dates')); ?></option>
                        <option value="today"><?php echo e(__('trainers.today')); ?></option>
                        <option value="tomorrow"><?php echo e(__('trainers.tomorrow')); ?></option>
                        <option value="this_week"><?php echo e(__('trainers.this_week')); ?></option>
                        <option value="next_week"><?php echo e(__('trainers.next_week')); ?></option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label for="trainer-search" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('trainers.filter_trainer')); ?></label>
                    <div class="relative rounded-md shadow-sm">
                        <input wire:model.debounce.300ms="search" type="text" id="trainer-search" class="block w-full rounded-md border-gray-300 pr-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="<?php echo e(__('trainers.filter_trainer')); ?>">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!--[if BLOCK]><![endif]--><?php if($reservations->isEmpty()): ?>
            <div class="px-4 py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900"><?php echo e(__('trainers.no_reservations')); ?></h3>
                <p class="mt-1 text-sm text-gray-500"><?php echo e(__('trainers.no_reservations_desc')); ?></p>
                <div class="mt-6">
                    <a href="<?php echo e(route('trainers.list')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <?php echo e(__('trainers.browse_trainers')); ?>

                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden border-b border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('trainers.trainer_column')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('trainers.date_column')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('trainers.time_column')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('trainers.status_column')); ?>

                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?php echo e(__('trainers.actions_column')); ?>

                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-medium">
                                                        <?php echo e(strtoupper(substr($reservation->trainer->name, 0, 2))); ?>

                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo e($reservation->trainer->name); ?>

                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            <?php echo e($reservation->trainer->specialization); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 font-medium"><?php echo e($reservation->date->format('d.m.Y')); ?></div>
                                                <div class="text-xs text-gray-500">
                                                    <?php
                                                        $today = \Carbon\Carbon::today();
                                                        $resDate = \Carbon\Carbon::parse($reservation->date);
                                                        $diff = $today->diffInDays($resDate, false);
                                                    ?>
                                                    
                                                    <!--[if BLOCK]><![endif]--><?php if($diff === 0): ?>
                                                        <span class="text-green-600"><?php echo e(__('trainers.today')); ?></span>
                                                    <?php elseif($diff === 1): ?>
                                                        <span class="text-blue-600"><?php echo e(__('trainers.tomorrow')); ?></span>
                                                    <?php elseif($diff > 1 && $diff <= 7): ?>
                                                        <span class="text-indigo-600"><?php echo e(__('trainers.days_from_now', ['days' => $diff])); ?></span>
                                                    <?php elseif($diff < 0): ?>
                                                        <span class="text-gray-600"><?php echo e(abs($diff)); ?> <?php echo e(__('trainers.days_ago')); ?></span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center space-x-1 text-sm text-gray-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>
                                                        <?php echo e(\Carbon\Carbon::parse($reservation->start_time)->format('H:i')); ?> - 
                                                        <?php echo e(\Carbon\Carbon::parse($reservation->end_time)->format('H:i')); ?>

                                                    </span>
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <?php
                                                        $start = \Carbon\Carbon::parse($reservation->start_time);
                                                        $end = \Carbon\Carbon::parse($reservation->end_time);
                                                        $duration = $start->diffInMinutes($end);
                                                        $hours = floor($duration / 60);
                                                        $minutes = $duration % 60;
                                                    ?>
                                                    <?php echo e(__('trainers.time_duration')); ?>: 
                                                    <!--[if BLOCK]><![endif]--><?php if($hours > 0): ?>
                                                        <?php echo e($hours); ?> <?php echo e($hours == 1 ? __('trainers.hour') : __('trainers.hours')); ?>

                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($minutes > 0): ?>
                                                        <!--[if BLOCK]><![endif]--><?php if($hours > 0): ?> i <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        <?php echo e($minutes); ?> <?php echo e($minutes == 1 ? __('trainers.minute') : __('trainers.minutes')); ?>

                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php if($reservation->status == 'confirmed'): ?> bg-green-100 text-green-800 
                                                    <?php elseif($reservation->status == 'pending'): ?> bg-yellow-100 text-yellow-800 
                                                    <?php elseif($reservation->status == 'cancelled'): ?> bg-red-100 text-red-800 
                                                    <?php elseif($reservation->status == 'completed'): ?> bg-blue-100 text-blue-800
                                                    <?php else: ?> bg-gray-100 text-gray-800 <?php endif; ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 inline" 
                                                        <?php if($reservation->status == 'confirmed'): ?> fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        <?php elseif($reservation->status == 'pending'): ?> fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        <?php elseif($reservation->status == 'cancelled'): ?> fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        <?php elseif($reservation->status == 'completed'): ?> fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </svg>
                                                    <!--[if BLOCK]><![endif]--><?php if($reservation->status == 'confirmed'): ?> <?php echo e(__('trainers.confirmed')); ?>

                                                    <?php elseif($reservation->status == 'pending'): ?> <?php echo e(__('trainers.pending')); ?>

                                                    <?php elseif($reservation->status == 'cancelled'): ?> <?php echo e(__('trainers.cancelled')); ?>

                                                    <?php elseif($reservation->status == 'completed'): ?> <?php echo e(__('trainers.completed')); ?>

                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <!--[if BLOCK]><![endif]--><?php if($reservation->status != 'cancelled' && $reservation->status != 'completed'): ?>
                                                    <button wire:click="openCancelModal(<?php echo e($reservation->id); ?>)" class="inline-flex items-center px-3 py-1.5 border border-red-300 text-sm leading-5 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 hover:border-red-400 transition focus:outline-none focus:border-red-400 focus:shadow-outline-red">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <?php echo e(__('trainers.cancel_reservation')); ?>

                                                    </button>
                                                <?php else: ?>
                                                    <a href="<?php echo e(route('trainer.show', $reservation->trainer_id)); ?>" class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-sm leading-5 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 hover:border-blue-400 transition focus:outline-none focus:border-blue-400 focus:shadow-outline-blue">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        <?php echo e(__('trainers.trainer_profile')); ?>

                                                    </a>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                <?php echo e($reservations->links()); ?>

            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Cancel Reservation Modal -->
    <div x-data="{ show: <?php if ((object) ('showCancelModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showCancelModal'->value()); ?>')<?php echo e('showCancelModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showCancelModal'); ?>')<?php endif; ?> }">
        <div
            x-show="show"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div
                    x-show="show"
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
                    x-show="show"
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
                                    <?php echo e(__('trainers.cancel_reservation_title')); ?>

                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        <?php echo e(__('trainers.cancel_reservation_description')); ?>

                                    </p>
                                    
                                    <!--[if BLOCK]><![endif]--><?php if($reservationToCancel): ?>
                                    <div class="mt-4 bg-gray-50 p-4 rounded-md">
                                        <div class="flex items-center text-sm text-gray-700 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span class="font-medium"><?php echo e(__('trainers.trainer')); ?>:</span>
                                            <span class="ml-2"><?php echo e($reservationToCancel->trainer->name); ?></span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-700 mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium"><?php echo e(__('trainers.date')); ?>:</span>
                                            <span class="ml-2"><?php echo e($reservationToCancel->date->format('d.m.Y')); ?></span>
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-medium"><?php echo e(__('trainers.time')); ?>:</span>
                                            <span class="ml-2"><?php echo e(\Carbon\Carbon::parse($reservationToCancel->start_time)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($reservationToCancel->end_time)->format('H:i')); ?></span>
                                        </div>
                                    </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="cancelReservation" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <?php echo e(__('trainers.cancel_reservation')); ?>

                        </button>
                        <button wire:click="closeCancelModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            <?php echo e(__('trainers.close')); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/user-reservations.blade.php ENDPATH**/ ?>