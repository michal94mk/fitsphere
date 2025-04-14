<x-slot name="header">
    Edytuj komentarz
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Edytuj komentarz</h1>
            <a href="{{ route('admin.comments.index') }}" wire:navigate
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                Powrót do listy
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- Comment information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Informacje o komentarzu</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex-shrink-0 mr-3"></div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $userName }}</p>
                                        <p class="text-gray-500 text-sm">{{ $userEmail }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Artykuł</label>
                                <p class="text-gray-900">{{ $postTitle }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data dodania</label>
                                <p class="text-gray-900">{{ $createdAt }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comment content section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">Treść komentarza</h2>
                        <div class="mt-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Treść</label>
                            <textarea id="content" rows="6" wire:model="content" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                        Zapisz zmiany
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 