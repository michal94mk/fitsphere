<div>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-4 p-4 bg-blue-100 text-blue-700 rounded-md font-medium">
                    {{ session('info') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md font-medium">
                    {{ session('error') }}
                </div>
            @endif

            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900">Informacje o profilu trenera</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Zaktualizuj informacje o swoim profilu i adres email.
                    </p>
                </header>

                <form wire:submit.prevent="updateProfile" class="mt-6 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Imię i nazwisko</label>
                        <input type="text" id="name" wire:model="name"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        @error('name')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" wire:model="email"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        @error('email')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if (!$user->hasVerifiedEmail())
                        <div class="p-4 bg-yellow-50 text-yellow-700 rounded-md flex justify-between items-center">
                            <span class="text-sm">
                                Twój adres email nie został jeszcze zweryfikowany.
                            </span>
                            <button type="button" wire:click="resendVerificationEmail" class="text-blue-500 text-sm">
                                Wyślij ponownie
                            </button>
                        </div>
                    @endif

                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
                        <input type="text" id="specialization" wire:model="specialization"
                              class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        @error('specialization')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Krótki opis</label>
                        <p class="text-xs text-gray-500 mb-1">Maksymalnie 1000 znaków</p>
                        <textarea id="description" wire:model="description" rows="3"
                                  class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                        @error('description')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700">Biografia</label>
                        <p class="text-xs text-gray-500 mb-1">Maksymalnie 5000 znaków</p>
                        <textarea id="bio" wire:model="bio" rows="6"
                                  class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"></textarea>
                        @error('bio')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Zdjęcie profilowe</label>
                        
                        @if ($image)
                            <div class="mt-2 mb-4">
                                <p class="text-sm text-gray-500 mb-2">Aktualne zdjęcie:</p>
                                <img src="{{ asset('storage/' . $image) }}" alt="Zdjęcie profilowe" class="w-32 h-32 object-cover rounded-lg border">
                            </div>
                        @endif
                        
                        <div class="mt-2">
                            <input type="file" id="newImage" wire:model="newImage" accept="image/*"
                                   class="mt-1 w-full text-gray-700">
                            <p class="text-xs text-gray-500 mt-1">Akceptowane formaty: JPG, PNG. Maksymalny rozmiar: 1MB</p>
                        </div>
                        
                        @error('newImage')
                            <span class="text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                        
                        @if ($newImage)
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 mb-2">Podgląd nowego zdjęcia:</p>
                                <img src="{{ $newImage->temporaryUrl() }}" alt="Podgląd" class="w-32 h-32 object-cover rounded-lg border">
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Zapisz zmiany
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div> 