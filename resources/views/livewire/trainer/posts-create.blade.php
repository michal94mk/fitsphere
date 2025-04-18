<div class="bg-white overflow-hidden shadow-md rounded-lg">
    <div class="p-6">
        <form wire:submit.prevent="store">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tytuł</label>
                <input type="text" id="title" wire:model="title" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Fragment (krótki opis)</label>
                <textarea id="excerpt" wire:model="excerpt" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                @error('excerpt') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Treść</label>
                <textarea id="content" wire:model="content" rows="10" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                @error('content') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategoria</label>
                <select id="category_id" wire:model="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="">Wybierz kategorię</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Zdjęcie</label>
                <input type="file" id="image" wire:model="image" class="w-full border border-gray-300 p-2 rounded-md">
                <div class="text-xs text-gray-500 mt-1">Maksymalny rozmiar: 1MB. Dozwolone formaty: jpg, png, gif.</div>
                @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                
                @if ($image)
                    <div class="mt-2">
                        <span class="text-sm text-gray-500">Podgląd:</span>
                        <img src="{{ $image->temporaryUrl() }}" class="mt-1 h-32 object-cover rounded-md">
                    </div>
                @endif
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="status" value="draft" class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2">Szkic</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" wire:model="status" value="published" class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                        <span class="ml-2">Opublikowany</span>
                    </label>
                </div>
                @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex items-center justify-end">
                <a href="{{ route('trainer.posts') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition mr-2">
                    Anuluj
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                    Zapisz post
                </button>
            </div>
        </form>
    </div>
</div> 