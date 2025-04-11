<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 max-w-2xl mx-auto">
        <div class="text-center">
            <div class="text-green-500 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Rejestracja zakończona pomyślnie!</h2>
            <p class="text-gray-600 mb-6">Twoje konto zostało utworzone. Możesz teraz zalogować się i korzystać z serwisu.</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Zaloguj się
                </a>
                <a href="{{ route('home') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded">
                    Strona główna
                </a>
            </div>
        </div>
    </div>
</div> 