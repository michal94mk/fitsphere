<div>
    <div class="container mx-auto p-6">
        <!-- Nagłówek z przyciskami i tytułem -->
        <div class="flex items-center justify-between mb-4">
            <!-- Lewa strona: przyciski -->
            <div class="flex space-x-2">
                <a href="{{ route('admin.categories.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                    Cofnij
                </a>
            </div>
            <!-- Środkowa część: tytuł -->
            <h1 class="text-2xl font-bold text-center flex-grow">
                Dodaj nową kategorię
            </h1>
            <!-- Prawa strona: pusta, żeby wyśrodkować tytuł -->
            <div class="w-32"></div>
        </div>

        <!-- Formularz -->
        <form wire:submit="store">
            <div class="space-y-4">
                <!-- Tytuł -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nazwa</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Przycisk zapisz -->
                <div>
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                        Zapisz
                    </button>
                </div>
            </div>
        </form>
    </div>
</div> 