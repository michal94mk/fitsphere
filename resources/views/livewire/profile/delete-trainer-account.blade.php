<div>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Usunięcie konta</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Po usunięciu konta, wszystkie jego zasoby i dane zostaną trwale usunięte. Przed usunięciem konta pobierz wszelkie dane i informacje, które chcesz zachować.
                    </p>
                </header>

                @if (!$confirmDelete)
                    <div class="mt-6">
                        <button 
                            wire:click="confirmDelete" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                        >
                            Usuń konto
                        </button>
                    </div>
                @else
                    <div class="mt-6 border border-red-200 rounded-md p-4 bg-red-50">
                        <h3 class="text-md font-medium text-red-800 mb-3">Potwierdź usunięcie konta</h3>
                        <p class="text-sm text-red-600 mb-4">
                            Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna, a wszystkie Twoje dane zostaną trwale usunięte.
                        </p>
                        
                        <form wire:submit.prevent="deleteAccount" class="space-y-4">
                            <div>
                                <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Podaj hasło, aby potwierdzić</label>
                                <input 
                                    type="password" 
                                    id="confirmPassword" 
                                    wire:model="confirmPassword" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-500 focus:ring-opacity-50"
                                    autofocus
                                >
                                @error('confirmPassword') 
                                    <span class="text-red-600 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <button 
                                    type="button"
                                    wire:click="cancelDelete" 
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors"
                                >
                                    Anuluj
                                </button>
                                <button 
                                    type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                                >
                                    Potwierdź usunięcie
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div> 