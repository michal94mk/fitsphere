<div>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Dodaj nowego użytkownika</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="store" enctype="multipart/form-data">
                <div class="p-6 space-y-6">
                    <!-- Dane podstawowe -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Dane podstawowe</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Imię</label>
                                <input type="text" id="name" wire:model="name" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" wire:model="email" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hasło -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Hasło</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Hasło</label>
                                <input type="password" id="password" wire:model="password" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Potwierdź hasło</label>
                                <input type="password" id="password_confirmation" wire:model="password_confirmation" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                        </div>
                    </div>

                    <!-- Profil użytkownika -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Profil użytkownika</h2>
                        <div class="mt-4">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rola</label>
                                <select id="role" wire:model="role" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                    <option value="admin">Admin</option>
                                    <option value="user">Użytkownik</option>
                                </select>
                                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Zdjęcie profilowe -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Zdjęcie profilowe</h2>
                        <div class="mt-4">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Zdjęcie</label>
                            <input type="file" id="image" wire:model="image" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 flex justify-between">
                    <a href="{{ route('admin.users.index') }}" wire:navigate 
                        class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                        Wróć do listy
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Dodaj użytkownika
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 