<div class="bg-gray-800 text-center py-6 text-gray-600">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="mb-4">&copy; 2024 Zdrowie & Fitness Blog</p>
        
        <div>
            <form wire:submit.prevent="subscribe" class="flex justify-center mt-4">
                <input type="email" wire:model="email" placeholder="Wprowadź swój email"
                       class="px-4 py-2 rounded-l-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-full hover:bg-orange-600 transition duration-500">
                    Subskrybuj
                </button>
            </form>

            @error('email')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
    </div>
</div>
