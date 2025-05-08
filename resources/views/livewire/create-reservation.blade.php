<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ __('trainers.reservation_with') }}: {{ $trainer->name }}</h1>
    
    @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm flex items-start" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 flex-shrink-0 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <p>{{ session('error') }}</p>
        </div>
    @endif
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('trainers.trainer_info') }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('trainers.trainer_details') }}</p>
                </div>
                @if($trainer->image)
                    <div class="mt-3 sm:mt-0">
                        <img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="h-20 w-20 rounded-full object-cover shadow-sm border border-gray-200">
                    </div>
                @endif
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('trainers.specialization') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trainer->specialization }}</dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('trainers.experience') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trainer->experience }} {{ __('trainers.years') }}</dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">{{ __('trainers.about_trainer') }}</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trainer->description }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <form wire:submit.prevent="createReservation" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __('trainers.reservation_form') }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ __('trainers.select_date_time') }}</p>
        </div>
        
        <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
            <!-- Calendar and time selection section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Calendar section -->
                <div class="col-span-1 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">{{ __('trainers.choose_date') }}</label>
                    <div class="relative">
                        <div class="flex items-center mb-3">
                            <button type="button" wire:click="previousMonth" class="p-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <span class="text-sm font-medium text-gray-700 mx-2">
                                {{ \Carbon\Carbon::parse($date)->format('F Y') }}
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
                            @php
                                $startOfMonth = \Carbon\Carbon::parse($date)->startOfMonth();
                                $endOfMonth = \Carbon\Carbon::parse($date)->endOfMonth();
                                $currentDate = \Carbon\Carbon::parse($date);
                                $today = \Carbon\Carbon::today();
                                $firstDayOfWeek = $startOfMonth->copy()->startOfWeek(0); // 0 = Sunday
                                $lastDayOfWeek = $endOfMonth->copy()->endOfWeek(0);
                            @endphp
                            
                            @for($day = $firstDayOfWeek; $day <= $lastDayOfWeek; $day->addDay())
                                @php
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
                                @endphp
                                
                                <div wire:click="{{ $isSelectable ? 'updateDate(\'' . $day->format('Y-m-d') . '\')' : '' }}" 
                                    class="{{ $classes }}">
                                    {{ $day->day }}
                                </div>
                            @endfor
                        </div>
                        
                        <input type="hidden" name="date" id="date" wire:model="date">
                        @error('date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Time slots section -->
                <div class="col-span-2 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-4">
                        <label class="block text-sm font-medium text-gray-700">{{ __('trainers.choose_hours') }}</label>
                        @if($startTime)
                            <button type="button" wire:click="resetTimeSelection" class="inline-flex items-center py-1 px-2.5 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                {{ __('trainers.clear_selection') }}
                            </button>
                        @endif
                    </div>

                    <!-- Past date warning -->
                    @if($this->isPastDate)
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
                                            {{ __('trainers.past_date_warning') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(count($timeSlotGrid) === 0)
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
                                            {{ __('trainers.no_available_slots') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Selected time info -->
                        @if($startTime || $endTime)
                            <div class="mb-4 p-3 rounded-md {{ ($startTime && $endTime) ? 'bg-green-50 border-l-4 border-green-500 text-green-700' : 'bg-blue-50 border-l-4 border-blue-400 text-blue-700' }}">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 {{ ($startTime && $endTime) ? 'text-green-500' : 'text-blue-400' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium">
                                            @if($startTime && $endTime)
                                                {{ __('trainers.time_selected') }}: <span class="font-bold">{{ $startTime }} - {{ $endTime }}</span>
                                                @php
                                                    $start = \Carbon\Carbon::parse($startTime);
                                                    $end = \Carbon\Carbon::parse($endTime);
                                                    $duration = $start->diffInMinutes($end);
                                                    $hours = floor($duration / 60);
                                                    $minutes = $duration % 60;
                                                @endphp
                                                <span class="block text-xs mt-1">
                                                    {{ __('trainers.time_duration') }}: 
                                                    @if($hours > 0)
                                                        {{ $hours }} {{ $hours == 1 ? __('trainers.hour') : __('trainers.hours') }}
                                                    @endif
                                                    @if($minutes > 0)
                                                        @if($hours > 0) i @endif
                                                        {{ $minutes }} {{ $minutes == 1 ? __('trainers.minute') : __('trainers.minutes') }}
                                                    @endif
                                                </span>
                                            @elseif($startTime)
                                                {{ __('trainers.start_time_selected') }}: <span class="font-bold">{{ $startTime }}</span>
                                                <span class="block text-xs mt-1">{{ __('trainers.end_time_now') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Time grid -->
                        <div class="overflow-auto max-h-80 pr-1 custom-scrollbar">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                @foreach($timeSlotGrid as $hourGroup)
                                    <div class="mb-2">
                                        <div class="flex items-center mb-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700">{{ $hourGroup['hour'] }}</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            @foreach($hourGroup['slots'] as $slot)
                                                <button
                                                    type="button"
                                                    wire:click="{{ $slot['available'] ? 'selectTimeSlot(\'' . $slot['time'] . '\')' : '' }}"
                                                    class="py-2 px-2 text-sm text-center rounded-md transition-all duration-150 flex items-center justify-center
                                                        {{ !$slot['available'] ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : '' }}
                                                        {{ $slot['available'] && (!isset($slot['selected']) || !$slot['selected']) && $slot['time'] !== $this->endTime ? 'bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 hover:shadow-sm' : '' }}
                                                        {{ $slot['time'] === $this->startTime ? 'bg-blue-600 text-white border border-blue-700 shadow' : '' }}
                                                        {{ $slot['time'] === $this->endTime ? 'bg-blue-500 text-white border border-blue-600 shadow' : '' }}
                                                        {{ isset($slot['selected']) && $slot['selected'] && $slot['time'] !== $this->startTime && $slot['time'] !== $this->endTime ? 'bg-blue-100 text-blue-800 border border-blue-300' : '' }}"
                                                    {{ !$slot['available'] ? 'disabled title="' . ($slot['reason'] ?? 'Niedostępny') . '"' : '' }}
                                                >
                                                    {{ $slot['time'] }}
                                                    @if($slot['time'] === $this->startTime)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11l7-7 7 7M5 19l7-7 7 7" />
                                                        </svg>
                                                    @endif
                                                    @if($slot['time'] === $this->endTime)
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Hidden inputs for form submission -->
                    <input type="hidden" wire:model="startTime">
                    <input type="hidden" wire:model="endTime">
                    @error('startTime') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('endTime') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">{{ __('trainers.notes') }}</label>
                <textarea id="notes" name="notes" rows="3" wire:model="notes" 
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 resize-none"
                    placeholder="{{ __('trainers.notes_placeholder') }}"></textarea>
                @error('notes') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 border-t border-gray-200">
            <button type="submit" 
                {{ $this->isPastDate || !$startTime || !$endTime ? 'disabled' : '' }} 
                class="inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white 
                {{ $this->isPastDate || !$startTime || !$endTime ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                @if(app()->getLocale() == 'pl')
                    Zarezerwuj termin
                @else
                    Reserve time
                @endif
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
