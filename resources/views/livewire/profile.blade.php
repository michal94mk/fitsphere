<div class="max-w-4xl mx-auto mt-10 p-8 bg-white rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 border-b pb-4">Twój profil</h1>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md font-medium">
            {{ session('status') }}
        </div>
    @endif

    @if (session('verified'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md font-medium">
            {{ session('verified') }}
        </div>
    @endif

    @if ($isTrainer)
        <!-- Dla trenera używamy oddzielnego komponentu -->
        <livewire:profile.update-trainer-profile />
    @else
        <!-- Dla zwykłego użytkownika -->
        <form wire:submit.prevent="update" class="space-y-6">
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

            @if ($user->email_verified_at)
                <div class="p-4 bg-green-50 text-green-700 rounded-md">
                    <span class="font-medium">Email zweryfikowany:</span> Tak
                </div>
            @else
                <div class="p-4 bg-yellow-50 text-yellow-700 rounded-md">
                    <span class="font-medium">Email niezweryfikowany.</span> 
                    <a href="{{ route('verification.notice') }}" class="text-blue-600 underline">Zweryfikuj teraz</a>
                </div>
            @endif

            <div>
                <button type="submit"
                        class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Aktualizuj profil
                </button>
            </div>
        </form>
    @endif
</div>

