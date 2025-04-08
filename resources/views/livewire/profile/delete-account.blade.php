<div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-2xl font-semibold text-red-600 mb-4">Usuń konto</h3>

    <form wire:submit.prevent="deleteAccount" class="space-y-4">
        <div>
            <input type="password" wire:model="password"
                   class="w-full p-3 border border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500"
                   placeholder="Wpisz hasło" />
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-right">
            <button type="submit"
                    class="bg-red-600 hover:bg-red-700 transition duration-500 text-white font-semibold py-2 px-6 rounded-lg shadow">
                Usuń
            </button>
        </div>
    </form>
</div>