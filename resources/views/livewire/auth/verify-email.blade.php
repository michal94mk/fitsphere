<div class="max-w-2xl mx-auto mt-10 p-8 bg-white rounded-2xl shadow-md text-center">
    <h1 class="text-3xl font-bold text-gray-900 mb-4">Potwierdź swój adres e-mail</h1>

    @if (session('status'))
        <div class="mb-4 p-4 rounded bg-green-100 text-green-800 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <p class="text-gray-700 mb-6">
        Na Twój adres e-mail został wysłany link weryfikacyjny. Kliknij w niego, aby aktywować konto.
        <br>
        Jeśli nie otrzymałeś wiadomości, możesz wysłać link ponownie.
    </p>

    <button
        wire:click="resendVerificationLink"
        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded-lg transition">
        Wyślij ponownie link weryfikacyjny
    </button>
</div>
