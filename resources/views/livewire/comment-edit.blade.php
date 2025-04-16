<div class="max-w-4xl mx-auto px-6">
    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-10">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-5 flex items-center">
            <div class="flex-shrink-0 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h1 class="text-2xl font-medium text-gray-800">Edytuj komentarz</h1>
        </div>

        <div class="p-6">
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit.prevent="update">
                <div class="mb-6">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Treść komentarza</label>
                    <textarea 
                        wire:model="content" 
                        id="content" 
                        rows="5" 
                        class="w-full p-3 border border-gray-300 rounded-md resize-none focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                        placeholder="Wprowadź treść komentarza..."
                    ></textarea>
                    @error('content') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        wire:click="cancel" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300 transition"
                    >
                        Anuluj
                    </button>
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                    >
                        Zapisz zmiany
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
