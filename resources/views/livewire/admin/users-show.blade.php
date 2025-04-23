<div>
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Wróć do listy użytkowników
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Szczegóły użytkownika: {{ $user->name }}</h2>

            <p class="mb-2"><strong>Imię:</strong> {{ $user->name }}</p>
            <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="mb-2"><strong>Rola:</strong> {{ ucfirst($user->role) }}</p>
            <p class="mb-2"><strong>Data dodania:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
            
            @if ($user->role === 'trainer')
                <div class="mt-4">
                    <h3 class="text-xl font-semibold mb-2">Informacje trenera</h3>
                    <p class="mb-2"><strong>Specjalizacja:</strong> {{ $user->specialization ?? 'Brak' }}</p>
                    <p class="mb-2"><strong>Opis:</strong> {{ $user->description ?? 'Brak' }}</p>
                    
                    @if ($user->image)
                        <div class="mt-4">
                            <p class="mb-2"><strong>Zdjęcie:</strong></p>
                            <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="w-32 h-32 object-cover rounded">
                        </div>
                    @endif
                </div>
            @endif

            <div class="py-3 flex justify-between items-center">
                <a href="{{ route('admin.users.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Powrót
                </a>
                <div class="flex space-x-2">
                    @if($user->role === 'trainer')
                        <a href="{{ route('admin.trainers.translations', $user->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                            </svg>
                            Zarządzaj tłumaczeniami
                        </a>
                    @endif
                    
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edytuj
                    </a>
                    
                    <button wire:click="confirmUserDeletion" wire:loading.attr="disabled" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded inline-flex items-center text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Usuń
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 