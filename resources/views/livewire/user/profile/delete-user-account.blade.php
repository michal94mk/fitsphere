<div>
    <div class="p-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Usuń konto') }}</h3>

        <div class="mt-3">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('Po usunięciu konta wszystkie jego zasoby i dane zostaną trwale usunięte.') }}
            </p>
        </div>

        <!-- Pole hasła przeniesione tutaj, przed przyciskiem Delete -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Hasło') }}
            </label>
            <input id="password" type="password" 
                class="mt-1 block w-3/4 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                placeholder="{{ __('Podaj swoje hasło') }}"
                wire:model="password" />
            
            @if($errorMessage)
                <div class="text-red-500 mt-1 text-sm">
                    {{ $errorMessage }}
                </div>
            @endif
        </div>

        <div class="mt-5">
            <button wire:click="validatePasswordAndOpenModal" wire:loading.attr="disabled" 
                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-300 disabled:opacity-25 transition">
                {{ __('Usuń konto') }}
            </button>
        </div>
    </div>

    <!-- Modal do potwierdzenia usunięcia - teraz bez pola hasła -->
    <div
        x-data="{ show: @entangle('showDeleteModal') }"
        x-on:keydown.escape.window="show = false"
        x-show="show"
        class="relative z-10"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
        style="display: none;"
    >
        <div 
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        ></div>

        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div 
                    x-show="show"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                >
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">{{ __('Potwierdź usunięcie konta') }}</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ __('Czy na pewno chcesz usunąć swoje konto? Ta operacja jest nieodwracalna. Wszystkie Twoje dane zostaną trwale usunięte.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteAccount" type="button" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            {{ __('Tak, usuń moje konto') }}
                        </button>
                        <button wire:click="closeModal" type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">
                            {{ __('Anuluj') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 