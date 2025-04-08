<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-lg rounded-2xl p-8">

        {{-- Komunikaty flash --}}
        @if (session('status'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if (session('verified'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('verified') }}
            </div>
        @endif

        @if (! Auth::user()->hasVerifiedEmail())
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-red-600 mb-4">Zweryfikuj swój adres e-mail</h2>
                <p class="text-gray-600">Zanim będziesz mógł zarządzać profilem, potwierdź swój adres e-mail.</p>
                <livewire:auth.verify-email />
            </div>
        @else
            <h2 class="text-3xl font-bold text-gray-900 mb-2 text-center">Zarządzanie profilem</h2>
            <p class="text-gray-600 text-center mb-6">Zmień swoje dane, hasło lub usuń konto.</p>

            <div class="space-y-10">
                <div>
                    <livewire:profile.update-profile />
                </div>
                <div>
                    <livewire:profile.update-password />
                </div>
                <div>
                    <livewire:profile.delete-account />
                </div>
            </div>
        @endif
    </div>
</div>
