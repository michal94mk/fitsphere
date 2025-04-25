<x-slot name="header">
    Edytuj kategorię
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Edytuj kategorię</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- Category information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Dane kategorii</h2>
                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nazwa kategorii</label>
                                <input type="text" id="name" wire:model="name" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 flex justify-between">
                    <a href="{{ route('admin.categories.index') }}" wire:navigate 
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