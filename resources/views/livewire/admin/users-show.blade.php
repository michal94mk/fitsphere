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
        </div>
    </div>
</div> 