<div>
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Wróć do listy użytkowników
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Edytuj użytkownika: {{ $user->name }}</h2>

            <form wire:submit="update" enctype="multipart/form-data">
                <!-- Imię -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Imię</label>
                    <input type="text" id="name" wire:model="user.name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    @error('user.name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" wire:model="user.email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    @error('user.email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rola -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Rola</label>
                    <select id="role" wire:model="user.role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                        <option value="trainer">trainer</option>
                    </select>
                    @error('user.role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pola dodatkowe dla trenerów (pokazywane tylko gdy role == 'trainer') -->
                @if ($user->role === 'trainer')
                    <!-- Specjalizacja -->
                    <div class="mb-4">
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
                        <input type="text" id="specialization" wire:model="user.specialization" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                        @error('user.specialization')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opis -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Opis</label>
                        <textarea id="description" wire:model="user.description" rows="4" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                        @error('user.description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Zdjęcie -->
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">Zdjęcie</label>
                        <input type="file" id="image" wire:model="image" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                        @error('image')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($user->image)
                            <p class="mt-2 text-sm text-gray-600">Aktualne zdjęcie:</p>
                            <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}" class="w-16 h-16 object-cover rounded-full">
                        @endif
                    </div>
                @endif

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    Zaktualizuj użytkownika
                </button>
            </form>
        </div>
    </div>
</div> 