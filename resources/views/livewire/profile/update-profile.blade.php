<div class="bg-white p-6 rounded-xl shadow-md">
    <h3 class="text-2xl font-semibold text-gray-900 mb-4">Aktualizacja profilu</h3>

    <form wire:submit.prevent="updateProfile" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Imię</label>
            <input type="text" wire:model="name"
                   class="w-full p-3 border border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" wire:model="email"
                   class="w-full p-3 border border-gray-800 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-right">
            <button type="submit"
                    class="bg-blue-500 hover:bg-orange-600 transition duration-500 text-white font-semibold py-2 px-6 rounded-lg shadow">
                Zapisz
            </button>
        </div>
    </form>
</div>