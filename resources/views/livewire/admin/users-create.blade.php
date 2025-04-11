<div>
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('admin.users.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Wróć do listy użytkowników
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-4">Dodaj nowego użytkownika</h2>

            <form wire:submit="store" enctype="multipart/form-data">
                <!-- Imię -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Imię</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" wire:model="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hasło -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Hasło</label>
                    <input type="password" id="password" wire:model="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Potwierdzenie hasła -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Potwierdź hasło</label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                </div>

                <!-- Rola -->
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Rola</label>
                    <select id="role" wire:model="role" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                        <option value="trainer">trainer</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pola dodatkowe dla trenerów (pokazywane tylko, gdy rola == 'trainer') -->
                @if ($role === 'trainer')
                    <!-- Specjalizacja -->
                    <div class="mb-4">
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
                        <input type="text" id="specialization" wire:model="specialization" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md">
                        @error('specialization')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opis -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Opis</label>
                        <textarea id="description" wire:model="description" rows="4" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                        @error('description')
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
                    </div>
                @endif

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    Dodaj użytkownika
                </button>
            </form>
        </div>
    </div>
</div> 