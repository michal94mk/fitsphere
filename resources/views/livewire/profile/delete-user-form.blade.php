<div class="bg-gray-900 text-white p-6 rounded-lg shadow-lg max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold text-center mb-4 text-red-500">Usuń konto</h2>
    <p class="text-gray-300 text-center mb-4">Usunięcie konta jest nieodwracalne. Jeśli jesteś pewien, wprowadź hasło, aby kontynuować.</p>

    <form wire:submit.prevent="deleteUser" class="space-y-4">
        <div>
            <label for="password" class="block text-lg">Hasło</label>
            <input type="password" id="password" wire:model="password" 
                class="w-full bg-gray-800 text-white border border-gray-600 rounded-lg p-2 focus:ring-2 focus:ring-orange-500">
            @error('password') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-between">
            <button type="submit" 
                class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-500 hover:scale-105">
                Usuń konto
            </button>
        </div>
    </form>
</div>
