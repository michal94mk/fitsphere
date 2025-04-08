<div class="max-w-md mx-auto py-8 bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">Potwierdź hasło</h2>

    <form wire:submit.prevent="confirm">
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Wpisz swoje hasło</label>
            <input 
                type="password" 
                wire:model="password" 
                placeholder="Wpisz hasło" 
                class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800">
            @error('password') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <button 
            type="submit" 
            class="w-full bg-blue-500 hover:bg-orange-600 transition duration-500 text-white font-semibold py-2 px-4 rounded-lg shadow">
            Potwierdź
        </button>
    </form>
</div>
