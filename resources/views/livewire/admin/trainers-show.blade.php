<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Szczegóły trenera</h2>
            <p class="text-gray-600">Przeglądaj informacje o trenerze</p>
        </div>
        <div>
            <a href="{{ route('admin.trainers.edit', $trainer->id) }}" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 mr-2">
                Edytuj
            </a>
            <a href="{{ route('admin.trainers.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Powrót
            </a>
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
            </dl>
        </div>
    </div>
</div> 