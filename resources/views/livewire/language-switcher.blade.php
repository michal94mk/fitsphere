{{-- 
    Language Switcher Component
    
    This template provides UI for switching between available languages.
    It uses Alpine.js for client-side reactivity and localStorage for persistence.
--}}
<div class="flex items-center space-x-2 text-gray-300"
    x-data="{ currentLocale: '{{ $this->getCurrentLocale() }}' }"
    x-init="
        // Initialize the language state in browser storage
        localStorage.setItem('app_locale', currentLocale);
        
        // Set up listener for language change events from Livewire
        Livewire.on('language-changed', (data) => {
            currentLocale = data.locale;
            localStorage.setItem('app_locale', currentLocale);
        });
    ">
    <span class="text-xs">{{ __('common.switch_language') }}:</span>
    {{-- Polish language button --}}
    <button wire:click="switchLanguage('pl')" class="focus:outline-none {{ $this->getCurrentLocale() === 'pl' ? 'opacity-100 scale-110' : 'opacity-70 hover:opacity-100' }} transition-all">
        <span class="flag-icon" x-bind:class="{ 'opacity-100 scale-110': currentLocale === 'pl', 'opacity-70': currentLocale !== 'pl' }">🇵🇱</span>
    </button>
    {{-- English language button --}}
    <button wire:click="switchLanguage('en')" class="focus:outline-none {{ $this->getCurrentLocale() === 'en' ? 'opacity-100 scale-110' : 'opacity-70 hover:opacity-100' }} transition-all">
        <span class="flag-icon" x-bind:class="{ 'opacity-100 scale-110': currentLocale === 'en', 'opacity-70': currentLocale !== 'en' }">🇬🇧</span>
    </button>
</div> 