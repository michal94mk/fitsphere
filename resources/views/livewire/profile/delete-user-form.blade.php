<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        Usuń konto
    </h2>

    <div class="max-w-xl text-sm text-gray-600 mb-6">
        <p>Po usunięciu konta wszystkie jego zasoby i dane zostaną trwale usunięte. Przed usunięciem konta pobierz wszelkie dane lub informacje, które chcesz zachować.</p>
    </div>

    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    <strong>Uwaga:</strong> Usunięcie konta jest nieodwracalne. Wszystkie Twoje dane, w tym posty i komentarze, zostaną trwale utracone.
                </p>
            </div>
        </div>
    </div>

    <div x-data="{ confirmingUserDeletion: false }" class="w-full">
        {{-- Password input field --}}
        <div class="mb-6">
            <label for="current_password" class="block text-sm font-medium mb-2 text-gray-700">Potwierdź hasło</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-red-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input type="password"
                       id="current_password"
                       wire:model.defer="password"
                       class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-lg p-3 pl-10 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 shadow-sm"
                       placeholder="Wprowadź swoje aktualne hasło" />
            </div>
            @error('password') 
                <p class="text-red-600 text-sm mt-1 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg> 
                    {{ $message }}
                </p> 
            @enderror
        </div>

        <button type="button" @click="confirmingUserDeletion = true" 
            class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-300 hover:scale-105 shadow-md flex items-center transform hover:-translate-y-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Usuń konto</span>
        </button>

        <div x-show="confirmingUserDeletion" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40"
             @click="confirmingUserDeletion = false">
        </div>

        <div x-show="confirmingUserDeletion" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="fixed inset-0 z-50 flex items-center justify-center" @click.away="confirmingUserDeletion = false">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full overflow-hidden mx-4" @click.stop>
                {{-- Modal Header --}}
                <div class="p-5 border-b border-gray-200">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Potwierdzenie usunięcia konta</h3>
                    </div>
                </div>
                
                <div class="p-5 text-gray-700">
                    <p>Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna i spowoduje trwałe usunięcie wszystkich Twoich danych.</p>
                </div>
                
                <div class="p-5 border-t border-gray-200 flex justify-between">
                    <button type="button" 
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors text-gray-700 flex items-center"
                            @click="confirmingUserDeletion = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Anuluj
                    </button>

                    <button type="button" 
                            class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all flex items-center"
                            wire:click="deleteUser"
                            wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span wire:loading.remove wire:target="deleteUser">Usuń konto</span>
                        <span wire:loading wire:target="deleteUser">Usuwanie...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>