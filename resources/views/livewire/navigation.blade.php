<div x-data="{ currentPage: @entangle('currentPage'), mobileOpen: false, profileDropdownOpen: false, languageDropdown: false }"
     x-init="$watch('currentPage', value => { 
         window.dispatchEvent(new CustomEvent('page-changed'));
         history.pushState(null, '', '/' + value);
     })">
    <!-- Główna nawigacja -->
    <div class="bg-gray-800">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Desktop & Tablet Navigation -->
            <div class="hidden lg:block py-2">
                <div class="flex items-center justify-between">
                    <!-- Lewa strona - przyciski nawigacyjne desktop -->
                    <div class="flex items-center space-x-1">
                        <div class="flex space-x-1">
                            <button @click="currentPage='home'" wire:click="goToPage('home')"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white text-center">
                                Home
                            </button>
                            <button @click="currentPage='posts'" wire:click="goToPage('posts')"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white text-center">
                                Posts
                            </button>
                            <button @click="currentPage='about'" wire:click="goToPage('about')"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white text-center">
                                About
                            </button>
                            <button @click="currentPage='contact'" wire:click="goToPage('contact')"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white text-center">
                                Contact
                            </button>
                            <button @click="currentPage='terms'" wire:click="goToPage('terms')"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white text-center">
                                Terms
                            </button>
                            
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                class="rounded-md px-3 py-2 text-sm font-medium bg-red-600 text-white hover:bg-red-700 text-center">
                                    Admin Panel
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Prawa strona - język, przyciski logowania / profil -->
                    <div class="flex items-center space-x-4 pr-4">
                        <!-- Przełącznik języka -->
                        <div x-data="{ open: false }" class="relative z-50">
                            <button @click="open = !open" 
                                    class="flex items-center justify-center rounded-md bg-gray-700 text-gray-300 px-3 py-1.5 text-sm hover:bg-gray-600 transition-colors">
                                <span class="mr-1 flex items-center">
                                    <span class="mr-1.5">🇵🇱</span> PL
                                </span>
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-cloak
                                 @click.away="open = false"
                                 style="position: absolute; top: 100%; margin-top: 0.5rem; left: auto; right: 0;"
                                 class="w-40 bg-white rounded-md shadow-lg overflow-hidden border border-gray-200 z-[100]">
                                <div class="py-1">
                                    <a href="#" wire:click.prevent="changeLanguage('pl')" class="flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                        <span class="mr-3 text-lg">🇵🇱</span> 
                                        <span class="font-medium">Polski</span>
                                    </a>
                                    <a href="#" wire:click.prevent="changeLanguage('en')" class="flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                        <span class="mr-3 text-lg">🇬🇧</span> 
                                        <span class="font-medium">English</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        @auth
                            <div x-data="{ dropdownOpen: false }"
                                 x-init="$watch('currentPage', () => { dropdownOpen = false; })"
                                 class="relative">
                                <button @click="dropdownOpen = !dropdownOpen"
                                        class="flex items-center text-gray-300 px-3 py-2 rounded-md hover:bg-gray-700 text-center">
                                    <span>{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <!-- Dropdown menu -->
                                <div x-show="dropdownOpen" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                    <a href="{{ route('profile') }}"
                                       class="block w-full px-4 py-2 text-gray-800 text-center hover:bg-gray-100">
                                        Profil
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full text-center px-4 py-2 text-gray-800 hover:bg-gray-100">
                                            Wyloguj się
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <button @click="currentPage='login'" wire:click="goToPage('login')"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white text-center">
                                Zaloguj się
                            </button>
                            <button @click="currentPage='register'" wire:click="goToPage('register')"
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white text-center">
                                Zarejestruj się
                            </button>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Mobile Menu Header -->
            <div class="lg:hidden flex items-center justify-between min-h-[4rem]">
                <div class="flex items-center">
                    <a @click="currentPage='home'" wire:click="goToPage('home')"
                       class="text-xl font-bold text-white">
                        LOGO
                    </a>
                </div>
                
                <!-- Przełącznik języka na mobilnym header -->
                <div class="flex items-center space-x-2">
                    <div x-data="{ open: false }" class="relative z-50 mr-2">
                        <button @click="open = !open" 
                                class="flex items-center justify-center rounded-md bg-gray-700 text-gray-300 px-3 py-1.5 text-sm hover:bg-gray-600 transition-colors">
                            <span class="flex items-center">
                                <span>🇵🇱</span>
                            </span>
                        </button>
                        <div x-show="open" x-cloak
                             @click.away="open = false"
                             style="margin-top: 0.5rem; position: absolute; right: 0; bottom: auto; top: 100%;"
                             class="w-40 bg-white rounded-md shadow-lg overflow-hidden border border-gray-200">
                            <div class="py-1">
                                <a href="#" wire:click.prevent="changeLanguage('pl')" class="flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                    <span class="mr-3 text-lg">🇵🇱</span> 
                                    <span class="font-medium">Polski</span>
                                </a>
                                <a href="#" wire:click.prevent="changeLanguage('en')" class="flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition-colors">
                                    <span class="mr-3 text-lg">🇬🇧</span> 
                                    <span class="font-medium">English</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Menu button -->
                    <button @click="mobileOpen = !mobileOpen"
                            class="text-gray-300 hover:text-white focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none"
                             viewBox="0 0 24 24">
                            <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen }"
                                  class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                            <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen }"
                                  class="hidden" stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
    </div>

    <!-- Pasek wyszukiwania - tylko desktop/tablet -->
    <div class="hidden lg:block bg-gray-700 shadow-md">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-3">
            <div class="relative max-w-2xl mx-auto">
                <div class="flex items-center">
                    <div class="w-full relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            type="text"
                            wire:model.defer="searchQuery"
                            wire:keydown.enter="goToSearch"
                            placeholder="Wyszukaj na stronie..."
                            class="block w-full pl-10 pr-3 py-3 border-0 rounded-l-lg bg-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <button 
                        wire:click="goToSearch"
                        class="flex items-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-r-lg transition-colors duration-300">
                        <span>Szukaj</span>
                    </button>
                </div>
                
                <!-- Toast pod wyszukiwarką desktop -->
                <div x-data="{ 
                    toast: false, 
                    message: '',
                    showToast() {
                        this.toast = true;
                        setTimeout(() => this.toast = false, 3000);
                    }
                }" 
                x-init="$watch('$wire.toastMessage', value => {
                    if (value) {
                        message = value;
                        showToast();
                        $wire.resetToast();
                    }
                })"
                class="absolute top-full left-0 right-0 mt-2 z-20">
                    <div x-show="toast" 
                        x-cloak
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-[-10px]"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-[-10px]"
                        class="bg-white shadow-lg rounded-lg p-3 border-l-4 border-orange-500 text-center">
                        <div class="flex items-center justify-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 text-center" x-text="message"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-transition @close-mobile-menu.window="mobileOpen = false" class="lg:hidden bg-gray-800">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <!-- Mobilna wyszukiwarka -->
            <div class="p-3 mb-2 border-b border-gray-700 bg-gray-700">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        type="text"
                        wire:model.defer="searchQuery"
                        wire:keydown.enter="goToSearch"
                        placeholder="Wyszukaj na stronie..."
                        class="block w-full pl-10 pr-10 py-3 border-0 rounded-lg bg-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        <button 
                            wire:click="goToSearch"
                            class="p-2 mr-1 text-gray-400 hover:text-white"
                        >
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Toast pod wyszukiwarką mobilną -->
                    <div x-data="{ 
                        mobileToast: false, 
                        mobileMessage: '',
                        showMobileToast() {
                            this.mobileToast = true;
                            setTimeout(() => this.mobileToast = false, 3000);
                        }
                    }" 
                    x-init="$watch('$wire.toastMessage', value => {
                        if (value) {
                            mobileMessage = value;
                            showMobileToast();
                            $wire.resetToast();
                        }
                    })"
                    class="absolute top-full left-0 right-0 mt-2 z-20">
                        <div x-show="mobileToast" 
                            x-cloak
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-y-[-10px]"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform translate-y-[-10px]"
                            class="bg-white shadow-lg rounded-lg p-3 border-l-4 border-orange-500 text-center">
                            <div class="flex items-center justify-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-700 text-center" x-text="mobileMessage"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <button @click="currentPage='home'; mobileOpen = false" wire:click="goToPage('home')"
                    class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                Home
            </button>
            <button @click="currentPage='posts'; mobileOpen = false" wire:click="goToPage('posts')"
                    class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                Posts
            </button>
            <button @click="currentPage='about'; mobileOpen = false" wire:click="goToPage('about')"
                    class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                About
            </button>
            <button @click="currentPage='contact'; mobileOpen = false" wire:click="goToPage('contact')"
                    class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                Contact
            </button>
            <button @click="currentPage='terms'; mobileOpen = false" wire:click="goToPage('terms')"
                    class="block w-full text-left rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                Terms
            </button>
            
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" @click="mobileOpen = false"
                   class="block w-full text-left rounded-md px-3 py-2 text-base font-medium bg-red-600 text-white hover:bg-red-700">
                    Admin Panel
                </a>
            @endif

            <!-- Mobile Authentication buttons -->
            @auth
                <div x-data="{ dropdownOpen: false }" class="w-full">
                    <button @click="dropdownOpen = !dropdownOpen"
                            class="block w-full px-4 py-2 text-gray-900 font-medium bg-gray-200 rounded-md hover:bg-gray-300 text-left">
                        {{ Auth::user()->name }}
                    </button>
                    <div x-show="dropdownOpen" x-cloak
                         class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-md">
                        <button @click="currentPage='profile'; dropdownOpen = false; mobileOpen = false"
                                wire:click="goToPage('profile')"
                                class="block w-full px-4 py-2 text-gray-900 text-left hover:bg-gray-100">
                            Profil
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full px-4 py-2 text-gray-900 text-left hover:bg-gray-100">
                                Wyloguj się
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <button @click="currentPage='login'; mobileOpen = false" wire:click="goToPage('login')"
                        class="block w-full text-left rounded-md px-4 py-2 text-gray-900 bg-gray-200 hover:bg-gray-300">
                    Zaloguj się
                </button>
                <button @click="currentPage='register'; mobileOpen = false" wire:click="goToPage('register')"
                        class="block w-full text-left rounded-md px-4 py-2 text-gray-900 bg-gray-200 hover:bg-gray-300">
                    Zarejestruj się
                </button>
            @endauth
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    Livewire.on('showToast', (message) => {
        window.dispatchEvent(new CustomEvent('show-toast', { 
            detail: typeof message === 'string' ? message : message.message 
        }));
    });
    
    // Dodajemy nasłuchiwanie na zdarzenie zamknięcia menu mobilnego
    Livewire.on('closeMobileMenu', () => {
        window.dispatchEvent(new CustomEvent('close-mobile-menu'));
    });
});
</script>