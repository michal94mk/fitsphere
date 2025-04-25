<x-slot name="header">
    Edytuj użytkownika
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Alerts -->
        @if (session()->has('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" wire:click="$set('alertSuccess', null)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </button>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" wire:click="$set('alertError', null)" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </button>
            </div>
        @endif

        <h1 class="text-2xl font-bold mb-6">Edytuj użytkownika</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- User information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Dane podstawowe</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Imię</label>
                                <input type="text" id="name" wire:model="name" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" wire:model="email" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password change section (optional) -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Zmiana hasła (opcjonalnie)</h2>
                        <div class="mt-4">
                            <label class="inline-flex items-center mb-4">
                                <input type="checkbox" wire:model="changePassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Zmień hasło</span>
                            </label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Nowe hasło</label>
                                    <input type="password" id="password" wire:model="password" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Potwierdź nowe hasło</label>
                                    <input type="password" id="password_confirmation" wire:model="password_confirmation" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User-specific information -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Profil użytkownika</h2>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mt-4">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Rola</label>
                                <select id="role" wire:model="role" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="admin">Admin</option>
                                    <option value="user">Użytkownik</option>
                                </select>
                                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile photo upload -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Zdjęcie profilowe</h2>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Profile preview" class="h-24 w-24 rounded-full object-cover">
                                    @elseif ($existing_photo)
                                        <img src="{{ $existing_photo }}" alt="{{ $name }}" class="h-24 w-24 rounded-full object-cover">
                                    @else
                                        <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-5">
                                    <div class="flex items-center">
                                        <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span>{{ $existing_photo ? 'Zmień zdjęcie' : 'Prześlij zdjęcie' }}</span>
                                            <input id="photo" wire:model.live="photo" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, do 1MB</p>
                                    @error('photo') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                                    
                                    @if ($photo || $existing_photo)
                                        <button type="button" wire:click="removePhoto" class="mt-2 text-sm text-red-600 hover:text-red-900">
                                            Usuń zdjęcie
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 flex justify-between">
                    <a href="{{ route('admin.users.index') }}" wire:navigate 
                       class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                        Wróć do listy
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Zapisz zmiany
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 