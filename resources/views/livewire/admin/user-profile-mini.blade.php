{{-- 
    Komponent mini profilu użytkownika - pokazuje znajomość Livewire
    Wykorzystane: wire:click, @auth, @method
--}}
<div class="flex items-center">
    {{-- Avatar użytkownika --}}
    <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
    </div>
    
    {{-- Dane użytkownika --}}
    <div>
        <p class="text-sm font-medium text-white">{{ $user->name }}</p>
        <p class="text-xs text-gray-400">Administrator</p>
    </div>
    
    {{-- Przycisk wylogowania - wykorzystuje metody Livewire --}}
    <button wire:click="logout" class="ml-auto text-gray-400 hover:text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
    </button>
</div> 