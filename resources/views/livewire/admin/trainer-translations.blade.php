<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Nagłówek -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">
                        Tłumaczenia dla trenera: {{ $trainer->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Dodaj lub edytuj tłumaczenia informacji o trenerze.
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.trainers.show', $trainer->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring focus:ring-gray-200 disabled:opacity-25 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Powrót
                    </a>
                </div>
            </div>
            
            <!-- Komunikaty -->
            @if (session()->has('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 border-l-4 border-green-500 rounded">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session()->has('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 text-red-700 border-l-4 border-red-500 rounded">
                    {{ session('error') }}
                </div>
            @endif
            
            <!-- Dane oryginalne -->
            <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Dane oryginalne:</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Specjalizacja:</p>
                        <p class="mt-1">{{ $trainer->specialization ?? 'Brak' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Specjalności:</p>
                        <p class="mt-1">{{ $trainer->specialties ?? 'Brak' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-semibold text-gray-600">Krótki opis:</p>
                        <p class="mt-1">{{ $trainer->description ?? 'Brak' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm font-semibold text-gray-600">Biografia:</p>
                        <p class="mt-1">{{ $trainer->bio ?? 'Brak' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Lista istniejących tłumaczeń -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Istniejące tłumaczenia:</h3>
                
                @if (empty($translations))
                    <p class="text-gray-500 italic">Brak tłumaczeń.</p>
                @else
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Język
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Specjalizacja
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Akcje
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($translations as $translation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $translation['locale'] === 'en' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $translation['locale'] === 'en' ? 'Angielski' : 'Polski' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $translation['specialization'] ?: '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="editTranslation({{ $translation['id'] }})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                Edytuj
                                            </button>
                                            <button wire:click="deleteTranslation({{ $translation['id'] }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Czy na pewno chcesz usunąć to tłumaczenie?')">
                                                Usuń
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            <!-- Formularz dodawania/edycji tłumaczenia -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                    {{ $editingTranslationId ? 'Edytuj tłumaczenie' : 'Dodaj nowe tłumaczenie' }}
                </h3>
                
                <form wire:submit.prevent="saveTranslation" class="space-y-4">
                    <!-- Język -->
                    <div>
                        <label for="locale" class="block text-sm font-medium text-gray-700">Język</label>
                        <select id="locale" wire:model="locale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ $editingTranslationId ? 'disabled' : '' }}>
                            <option value="en">Angielski</option>
                            <option value="pl">Polski</option>
                        </select>
                        @error('locale') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Specjalizacja -->
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
                        <input type="text" id="specialization" wire:model="specialization" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('specialization') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Krótki opis -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Krótki opis</label>
                        <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Biografia -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700">Biografia</label>
                        <textarea id="bio" wire:model="bio" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Specjalności -->
                    <div>
                        <label for="specialties" class="block text-sm font-medium text-gray-700">Specjalności (oddzielone przecinkami)</label>
                        <input type="text" id="specialties" wire:model="specialties" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('specialties') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Przyciski -->
                    <div class="flex justify-end space-x-3 pt-4">
                        @if ($editingTranslationId)
                            <button type="button" wire:click="cancelEdit" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300">
                                Anuluj
                            </button>
                        @endif
                        
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                            {{ $editingTranslationId ? 'Zapisz zmiany' : 'Dodaj tłumaczenie' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 