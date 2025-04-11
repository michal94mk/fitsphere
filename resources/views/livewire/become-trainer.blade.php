<div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Zostań naszym trenerem
        </h1>
        <p class="mt-4 text-lg text-gray-500">
            Dołącz do naszego zespołu trenerów i dziel się swoją wiedzą.
        </p>
    </div>

    @if ($success)
        <div class="rounded-md bg-green-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">
                        Rejestracja zakończona pomyślnie
                    </h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Dziękujemy za rejestrację jako trener. Twoje zgłoszenie zostało przyjęte i oczekuje na weryfikację.</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Imię i nazwisko</label>
                            <div class="mt-1">
                                <input type="text" id="name" wire:model="name" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <div class="mt-1">
                                <input type="email" id="email" wire:model="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Hasło</label>
                            <div class="mt-1">
                                <input type="password" id="password" wire:model="password" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Powtórz hasło</label>
                            <div class="mt-1">
                                <input type="password" id="password_confirmation" wire:model="password_confirmation" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
                            <div class="mt-1">
                                <input type="text" id="specialization" wire:model="specialization" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            @error('specialization') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Zdjęcie</label>
                            <div class="mt-1">
                                <input type="file" id="image" wire:model="image">
                            </div>
                            @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            
                            @if ($image)
                                <div class="mt-2">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Podgląd" class="h-20 w-20 object-cover rounded-full">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">O sobie</label>
                        <div class="mt-1">
                            <textarea id="description" wire:model="description" rows="4" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50" onclick="window.location.href='{{ route('home') }}'">
                                Anuluj
                            </button>
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Wyślij zgłoszenie
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div> 