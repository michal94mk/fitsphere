<div class="bg-gray-900 text-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-4">Aktualizacja profilu</h2>

    @if (session()->has('status'))
        <p class="text-green-400 text-center mb-4">{{ session('status') }}</p>
    @endif

    <form wire:submit.prevent="updateProfile" class="space-y-4">
        <div>
            <label for="name" class="block text-lg">Imię</label>
            <input type="text" id="name" wire:model="name" 
                class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg p-2 focus:ring-2 focus:ring-orange-500">
            @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="block text-lg">Email</label>
            <input type="email" id="email" wire:model="email" 
                class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg p-2 focus:ring-2 focus:ring-orange-500">
            @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-orange-600 transition duration-500 hover:scale-105">
                Zapisz zmiany
            </button>
        </div>
    </form>
</div>
