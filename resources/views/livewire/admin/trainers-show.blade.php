<x-slot name="header">
    {{ __('admin.trainer_details') }}
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold">{{ __('admin.trainer_details') }}: {{ $trainer->name }}</h1>
        </div>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Szczegóły trenera</h2>
                <p class="text-gray-600">Przeglądaj informacje o trenerze</p>
            </div>
            <div>
                <x-admin.form-button style="secondary" :href="route('admin.trainers.index')" navigate>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    {{ __('admin.back_to_list') }}
                </x-admin.form-button>
            </div>
        </div>
        
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center">
                    @if ($trainer->image)
                        <img src="{{ asset('storage/' . $trainer->image) }}" alt="{{ $trainer->name }}" class="h-20 w-20 rounded-full object-cover mr-4">
                    @else
                        <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                            <svg class="h-10 w-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $trainer->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $trainer->specialization ?? 'Brak specjalizacji' }}</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trainer->email }}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status konta</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($trainer->email_verified_at)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Zweryfikowany
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Niezweryfikowany
                                </span>
                            @endif
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Data dołączenia</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $trainer->created_at->format('d.m.Y H:i') }}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Specjalizacja</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $trainer->specialization ?? 'Brak specjalizacji' }}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Opis / Biografia</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {!! nl2br(e($trainer->description ?? 'Brak opisu')) !!}
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Pełna biografia</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {!! nl2br(e($trainer->bio ?? 'Brak pełnej biografii')) !!}
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Status zatwierdzenia</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($trainer->is_approved)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Zatwierdzony
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Oczekujący na zatwierdzenie
                                </span>
                            @endif
                        </dd>
                    </div>
                    @if(isset($trainer->experience))
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">doświadczenie (lata)</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $trainer->experience ?? '0' }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
            
            <!-- Action buttons -->
            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-start">
                    <a href="{{ route('admin.trainers.edit', $trainer->id) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        {{ __('admin.edit') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 