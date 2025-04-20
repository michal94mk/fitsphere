<div class="space-y-6">
    <!-- Informacje o artykule -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Informacje o artykule</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 text-sm mb-1">Tytuł oryginału (język podstawowy):</p>
                <p class="font-medium">{{ $post->title }}</p>
            </div>
            
            <div>
                <p class="text-gray-500 text-sm mb-1">Slug:</p>
                <p class="font-medium">{{ $post->slug }}</p>
            </div>
            
            <div class="md:col-span-2">
                <p class="text-gray-500 text-sm mb-1">Fragment:</p>
                <p>{{ $post->excerpt }}</p>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('admin.posts.edit', $post->id) }}" wire:navigate class="text-blue-600 hover:text-blue-800">
                &larr; Wróć do edycji artykułu
            </a>
        </div>
    </div>
    
    <!-- Lista istniejących tłumaczeń -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">Istniejące tłumaczenia</h2>
        
        @if (count($translations) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Język
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tytuł
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Data modyfikacji
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Akcje
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($translations as $translation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl">{{ $translation['locale'] == 'pl' ? '🇵🇱' : '🇬🇧' }}</span>
                                        <span>{{ $translation['locale'] == 'pl' ? 'Polski' : 'Angielski' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $translation['title'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ date('d.m.Y H:i', strtotime($translation['updated_at'])) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button wire:click="editTranslation({{ $translation['id'] }})" class="text-indigo-600 hover:text-indigo-900">
                                        Edytuj
                                    </button>
                                    <button wire:click="deleteTranslation({{ $translation['id'] }})" 
                                            wire:confirm="Czy na pewno chcesz usunąć to tłumaczenie?"
                                            class="text-red-600 hover:text-red-900">
                                        Usuń
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Brak tłumaczeń</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Ten artykuł nie posiada jeszcze żadnych tłumaczeń. Dodaj tłumaczenie przy pomocy formularza poniżej.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Formularz tłumaczenia -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">
            {{ $editingTranslationId ? 'Edytuj tłumaczenie' : 'Dodaj nowe tłumaczenie' }}
        </h2>
        
        <form wire:submit.prevent="saveTranslation" class="space-y-4">
            @if (session()->has('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Wybór języka -->
            <div>
                <label for="locale" class="block text-sm font-medium text-gray-700">Język tłumaczenia</label>
                <select id="locale" wire:model="locale" @if($editingTranslationId) disabled @endif class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="pl">Polski 🇵🇱</option>
                    <option value="en">Angielski 🇬🇧</option>
                </select>
                @error('locale') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Tytuł -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Tytuł</label>
                <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Fragment -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700">Fragment (opcjonalnie)</label>
                <textarea id="excerpt" wire:model="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('excerpt') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Treść -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700">Treść</label>
                <textarea id="content" wire:model="content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Przyciski -->
            <div class="flex justify-end space-x-3 pt-5">
                <button type="button" wire:click="cancelEdit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Anuluj
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $editingTranslationId ? 'Aktualizuj tłumaczenie' : 'Dodaj tłumaczenie' }}
                </button>
            </div>
        </form>
    </div>
</div> 