<div>
    @if($trainerId)
        <!-- Przycisk powrotu -->
        <a 
            href="{{ route('about') }}"
            wire:navigate
            class="m-4 px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg flex items-center shadow hover:from-blue-600 hover:to-blue-700 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span wire:loading.remove>Powrót do listy trenerów</span>
            <span wire:loading>Ładowanie...</span>
        </a>
        <livewire:trainer-details :trainer-id="$trainerId" />
    @else
        <livewire:trainers-list />
    @endif
</div>
