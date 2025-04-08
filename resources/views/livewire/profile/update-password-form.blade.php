<div class="bg-gray-900 text-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-4">Zmiana hasła</h2>

    @if (session()->has('status'))
        <p class="text-green-400 text-center mb-4">{{ session('status') }}</p>
    @endif

    <form wire:submit.prevent="updatePassword" class="space-y-4">
        <div>
            <label for="current_password" class="block text-lg">Obecne hasło</label>
            <input type="password" id="current_password" wire:model="current_password" 
                class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg p-2 focus:ring-2 focus:ring-orange-500">
            @error('current_password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="block text-lg">Nowe hasło</label>
            <input type="password" id="password" wire:model="password" 
                class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg p-2 focus:ring-2 focus:ring-orange-500">
            @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-lg">Potwierdź hasło</label>
            <input type="password" id="password_confirmation" wire:model="password_confirmation" 
                class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg p-2 focus:ring-2 focus:ring-orange-500">
        </div>

        <div class="flex justify-end">
            <button type="submit" 
                class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-orange-600 transition duration-500 hover:scale-105">
                Zmień hasło
            </button>
        </div>
    </form>
</div>
