<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-12 flex flex-col justify-center sm:px-6 lg:px-8">
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center mb-6">
            <div class="inline-block p-1 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 mb-4">
                <div class="bg-white rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-extrabold text-center">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-blue-500">
                    Rejestracja
                </span>
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Lub
                <a href="{{ route('login') }}" wire:navigate wire:prefetch class="font-medium text-blue-600 hover:text-blue-500">
                    zaloguj się na istniejące konto
                </a>
            </p>
        </div>

        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
            {{-- Social Login Component --}}
            <div class="mb-6">
                <livewire:auth.social-login />
            </div>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">lub utwórz konto ręcznie</span>
                </div>
            </div>

            <form wire:submit.prevent="register" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Login</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.defer="name" id="name" name="name" type="text" required class="py-3 text-gray-900 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Twój login">
                    </div>
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <input wire:model.defer="email" id="email" name="email" type="email" autocomplete="email" required class="py-3 text-gray-900 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="you@example.com">
                    </div>
                    @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Hasło</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.defer="password" id="password" name="password" type="password" autocomplete="new-password" required class="py-3 text-gray-900 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Min. 6 znaków">
                    </div>
                    @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Potwierdź hasło</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input wire:model.defer="password_confirmation" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="py-3 text-gray-900 placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Powtórz hasło">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Typ konta</label>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input type="radio" id="user_type_regular" name="account_type" value="regular" wire:model="account_type" checked class="hidden peer">
                            <label for="user_type_regular" class="flex flex-col items-center justify-center p-4 text-gray-500 bg-white rounded-lg border border-gray-200 cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm font-medium">Użytkownik</span>
                            </label>
                        </div>
                        
                        <div>
                            <input type="radio" id="user_type_trainer" name="account_type" value="trainer" wire:model="account_type" class="hidden peer">
                            <label for="user_type_trainer" class="flex flex-col items-center justify-center p-4 text-gray-500 bg-white rounded-lg border border-gray-200 cursor-pointer peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mb-2 h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium">Trener</span>
                            </label>
                        </div>
                    </div>
                    @error('account_type') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ show: false }" x-show="$wire.account_type === 'trainer'" x-transition class="space-y-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Wybierając konto trenera, zostaniesz poproszony o podanie specjalizacji. Twoje konto będzie musiało zostać zatwierdzone przez administratora.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
                        <div class="mt-1">
                            <input wire:model.defer="specialization" id="specialization" name="specialization" type="text" class="py-3 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Np. Trening siłowy, Dietetyka, Joga">
                        </div>
                        @error('specialization') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if($account_type === 'trainer')
                <div class="space-y-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Wybierając konto trenera, zostaniesz poproszony o podanie specjalizacji. Twoje konto będzie musiało zostać zatwierdzone przez administratora.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">Specjalizacja</label>
                        <div class="mt-1">
                            <input wire:model.defer="specialization" id="specialization" name="specialization" type="text" class="py-3 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Np. Trening siłowy, Dietetyka, Joga">
                        </div>
                        @error('specialization') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                @endif

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transform transition hover:scale-105">
                        Utwórz konto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>