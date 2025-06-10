<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Zarządzanie rezerwacjami</h1>
    
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
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model="statusFilter" id="status-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Wszystkie statusy</option>
                        <option value="pending">Oczekujące</option>
                        <option value="confirmed">Potwierdzone</option>
                        <option value="completed">Zakończone</option>
                        <option value="cancelled">Anulowane</option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label for="date-filter" class="block text-sm font-medium text-gray-700 mb-1">Data</label>
                    <select wire:model="dateFilter" id="date-filter" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Wszystkie daty</option>
                        <option value="today">Dzisiaj</option>
                        <option value="tomorrow">Jutro</option>
                        <option value="this_week">Ten tydzień</option>
                        <option value="next_week">Przyszły tydzień</option>
                    </select>
                </div>
                <div class="w-full md:w-1/3">
                    <label for="user-search" class="block text-sm font-medium text-gray-700 mb-1">Wyszukaj</label>
                    <div class="relative rounded-md shadow-sm">
                        <input wire:model.debounce.300ms="search" type="text" id="user-search" class="block w-full rounded-md border-gray-300 pr-10 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Imię lub email">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 px-4 py-5 border-b border-gray-200">
            <div class="px-4 py-3 bg-yellow-50 rounded-lg shadow-sm border border-yellow-100">
                <p class="text-xs font-medium text-yellow-800 uppercase">Oczekujące</p>
                <p class="mt-1 text-2xl font-semibold text-yellow-700"><?php echo e($pendingCount ?? 0); ?></p>
            </div>
            <div class="px-4 py-3 bg-green-50 rounded-lg shadow-sm border border-green-100">
                <p class="text-xs font-medium text-green-800 uppercase">Potwierdzone</p>
                <p class="mt-1 text-2xl font-semibold text-green-700"><?php echo e($confirmedCount ?? 0); ?></p>
            </div>
            <div class="px-4 py-3 bg-blue-50 rounded-lg shadow-sm border border-blue-100">
                <p class="text-xs font-medium text-blue-800 uppercase">Zakończone</p>
                <p class="mt-1 text-2xl font-semibold text-blue-700"><?php echo e($completedCount ?? 0); ?></p>
            </div>
            <div class="px-4 py-3 bg-red-50 rounded-lg shadow-sm border border-red-100">
                <p class="text-xs font-medium text-red-800 uppercase">Anulowane</p>
                <p class="mt-1 text-2xl font-semibold text-red-700"><?php echo e($cancelledCount ?? 0); ?></p>
            </div>
        </div>
        
        <!--[if BLOCK]><![endif]--><?php if($reservations->isEmpty()): ?>
            <div class="px-4 py-12 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Brak rezerwacji</h3>
                <p class="mt-1 text-sm text-gray-500">Nie masz jeszcze żadnych rezerwacji od klientów.</p>
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
                                            Użytkownik
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Data
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Godzina
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Uwagi
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Akcje
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $reservations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reservation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-medium">
                                                        <?php echo e(strtoupper(substr($reservation->user->name, 0, 2))); ?>

                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?php echo e($reservation->user->name); ?>

                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?php echo e($reservation->user->email); ?>

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
                                                        <span class="text-green-600">Dzisiaj</span>
                                                    <?php elseif($diff === 1): ?>
                                                        <span class="text-blue-600">Jutro</span>
                                                    <?php elseif($diff > 1 && $diff <= 7): ?>
                                                        <span class="text-indigo-600">Za <?php echo e($diff); ?> dni</span>
                                                    <?php elseif($diff < 0): ?>
                                                        <span class="text-gray-600"><?php echo e(abs($diff)); ?> dni temu</span>
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
                                                    Czas trwania: 
                                                    <!--[if BLOCK]><![endif]--><?php if($hours > 0): ?>
                                                        <?php echo e($hours); ?> <?php echo e($hours == 1 ? 'godzina' : ($hours < 5 ? 'godziny' : 'godzin')); ?>

                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <!--[if BLOCK]><![endif]--><?php if($minutes > 0): ?>
                                                        <!--[if BLOCK]><![endif]--><?php if($hours > 0): ?> i <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        <?php echo e($minutes); ?> <?php echo e($minutes == 1 ? 'minuta' : ($minutes < 5 ? 'minuty' : 'minut')); ?>

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
                                                    <!--[if BLOCK]><![endif]--><?php if($reservation->status == 'confirmed'): ?> Potwierdzona
                                                    <?php elseif($reservation->status == 'pending'): ?> Oczekująca
                                                    <?php elseif($reservation->status == 'cancelled'): ?> Anulowana
                                                    <?php elseif($reservation->status == 'completed'): ?> Zakończona
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                                    <!--[if BLOCK]><![endif]--><?php if($reservation->notes): ?>
                                                        <span x-data="{ showNotes: false }" class="relative">
                                                            <button @click="showNotes = !showNotes" class="text-left underline text-sm text-indigo-600 hover:text-indigo-800">
                                                                <?php echo e(\Illuminate\Support\Str::limit($reservation->notes, 30)); ?>

                                                            </button>
                                                            <div x-show="showNotes" @click.away="showNotes = false" class="absolute z-10 mt-2 p-3 bg-white rounded-lg shadow-lg border border-gray-200 text-gray-700 text-sm w-64">
                                                                <?php echo e($reservation->notes); ?>

                                                            </div>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-gray-500 italic">Brak uwag</span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <!--[if BLOCK]><![endif]--><?php if($reservation->status == 'pending'): ?>
                                                    <div class="flex space-x-2">
                                                        <button wire:click="confirmReservation(<?php echo e($reservation->id); ?>)" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Potwierdź
                                                        </button>
                                                        <button wire:click="cancelReservation(<?php echo e($reservation->id); ?>)" 
                                                            onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')"
                                                            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Anuluj
                                                        </button>
                                                    </div>
                                                <?php elseif($reservation->status == 'confirmed'): ?>
                                                    <div class="flex space-x-2">
                                                        <button wire:click="completeReservation(<?php echo e($reservation->id); ?>)" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            Zakończ
                                                        </button>
                                                        <button wire:click="cancelReservation(<?php echo e($reservation->id); ?>)" 
                                                            onclick="return confirm('Czy na pewno chcesz anulować tę rezerwację?')"
                                                            class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-red-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Anuluj
                                                        </button>
                                                    </div>
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
</div>
<?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/trainer-reservations.blade.php ENDPATH**/ ?>