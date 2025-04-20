{{-- 
    Language Switcher Component with Dropdown Menu
    
    Displays a dropdown with flags and names of languages available in the application.
    Uses Alpine.js for interactions and wire:navigate for smooth language changes.
    
    Features:
    - Responsive design (flag only on small screens, flag with text on larger ones)
    - Animated transitions when expanding the menu
    - Auto-close when clicking outside the component
    - Highlighting of the currently selected language
    - No page reload thanks to wire:navigate
--}}
<div x-data="{ open: false }" 
     class="relative flex items-center">
    
    {{-- Main button with current language indicator --}}
    <button @click="open = !open" 
            type="button"
            class="flex items-center space-x-1 text-gray-300 px-2 py-1 rounded hover:bg-gray-700 transition-colors"
            :class="{ 'bg-gray-700': open }">
        
        <span class="sr-only">{{ __('common.switch_language') }}</span>
        
        {{-- Display flag and name of current language --}}
        <div class="flex items-center">
            @if($currentLocale === 'pl')
                <span class="flag-icon text-lg">🇵🇱</span>
                <span class="ml-1 text-xs font-medium hidden sm:inline">Polski</span>
            @else
                <span class="flag-icon text-lg">🇬🇧</span>
                <span class="ml-1 text-xs font-medium hidden sm:inline">English</span>
            @endif
        </div>
        
        {{-- Arrow icon with rotation animation --}}
        <svg xmlns="http://www.w3.org/2000/svg" 
             :class="{ 'transform rotate-180': open }"
             class="h-4 w-4 transition-transform duration-200" 
             viewBox="0 0 20 20" 
             fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    
    {{-- Dropdown list with available languages --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute z-50 top-full right-0 mt-1 bg-gray-800 rounded shadow-lg overflow-hidden border border-gray-700 min-w-[120px]">
        
        {{-- Polish language option --}}
        <a href="{{ request()->fullUrlWithQuery(['locale' => 'pl']) }}"
          wire:navigate
          class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 transition-colors {{ $currentLocale === 'pl' ? 'bg-gray-700' : '' }}">
            <span class="flag-icon text-lg">🇵🇱</span>
            <span class="ml-2 text-sm">Polski</span>
        </a>
        
        {{-- English language option --}}
        <a href="{{ request()->fullUrlWithQuery(['locale' => 'en']) }}"
          wire:navigate
          class="flex items-center px-4 py-2 text-gray-300 hover:bg-gray-700 transition-colors {{ $currentLocale === 'en' ? 'bg-gray-700' : '' }}">
            <span class="flag-icon text-lg">🇬🇧</span>
            <span class="ml-2 text-sm">English</span>
        </a>
    </div>
</div> 