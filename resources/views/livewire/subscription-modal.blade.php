<div>
    @if($isVisible)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-70 flex justify-center items-center z-50">
            <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-100">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 mb-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-2xl font-bold text-gray-900 mb-4">Dziękujemy za subskrybcję!</p>
                    <p class="text-gray-600 mb-6">Będziemy informować Cię o najnowszych aktualnościach i promocjach.</p>
                    <button wire:click="closeModal" class="w-full py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-300 shadow-md">
                        Zamknij
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>