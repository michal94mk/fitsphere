<div x-data="{ mobileOpen: false, profileDropdownOpen: false }">
    <div>
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
                <div class="relative flex items-center justify-between h-16">
                    
                    <!-- Mobile Menu button - przeniesiony na lewo -->
                    <div class="sm:hidden flex items-center justify-start">
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
                    
                    <!-- Lewa strona - przyciski nawigacyjne desktop -->
                    <div class="hidden sm:flex sm:items-center sm:space-x-2 md:space-x-4">
                        <a href="{{ route('home') }}" 
                           wire:navigate
                           class="rounded-md px-2 md:px-3 py-2 text-sm font-medium {{ $currentPage == 'home' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Home
                        </a>
                        <a href="{{ route('posts.list') }}"
                           wire:navigate
                           class="rounded-md px-2 md:px-3 py-2 text-sm font-medium {{ $currentPage == 'posts' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Posts
                        </a>
                        <a href="{{ route('trainers.list') }}"
                           wire:navigate
                           class="rounded-md px-2 md:px-3 py-2 text-sm font-medium {{ $currentPage == 'about' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Trainers
                        </a>
                        <a href="{{ route('contact') }}"
                           wire:navigate
                           class="rounded-md px-2 md:px-3 py-2 text-sm font-medium {{ $currentPage == 'contact' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Contact
                        </a>
                        <a href="{{ route('terms') }}"
                           wire:navigate
                           class="rounded-md px-2 md:px-3 py-2 text-sm font-medium {{ $currentPage == 'terms' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Terms
                        </a>
                        
                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               wire:navigate
                               class="rounded-md px-2 md:px-3 py-2 text-sm font-medium bg-red-600 text-white hover:bg-red-700">
                                Admin Panel
                            </a>
                        @endif
                        
                        @if(Auth::guard('trainer')->check())
                            <a href="{{ route('trainer.reservations') }}"
                               wire:navigate
                               class="rounded-md px-2 md:px-3 py-2 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700">
                                Panel Trenera
                            </a>
                        @endif
                    </div>

                    <!-- Prawa strona - przyciski logowania / profil -->
                    <div class="hidden sm:flex sm:items-center sm:space-x-2 md:space-x-4">
                        @if(Auth::check())
                            <div x-data="{ dropdownOpen: false }"
                                 x-init="$watch('currentPage', () => { dropdownOpen = false; })"
                                 class="relative">
                                <button @click="dropdownOpen = !dropdownOpen"
                                        class="text-gray-300 px-2 md:px-3 py-2 rounded-md hover:bg-gray-700 flex items-center whitespace-nowrap">
                                    <span class="truncate max-w-[100px] md:max-w-none">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div x-show="dropdownOpen" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                    <a href="{{ route('profile') }}"
                                       wire:navigate
                                       class="block w-full px-4 py-2 text-gray-800 text-left hover:bg-gray-100">
                                        Profil
                                    </a>
                                    <a href="{{ route('user.reservations') }}"
                                       wire:navigate
                                       class="block w-full px-4 py-2 text-gray-800 text-left hover:bg-gray-100">
                                        Moje rezerwacje
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                            Wyloguj się
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @elseif(Auth::guard('trainer')->check())
                            <div x-data="{ dropdownOpen: false }"
                                 x-init="$watch('currentPage', () => { dropdownOpen = false; })"
                                 class="relative">
                                <button @click="dropdownOpen = !dropdownOpen"
                                        class="text-gray-300 px-2 md:px-3 py-2 rounded-md hover:bg-gray-700 flex items-center whitespace-nowrap">
                                    <span class="truncate max-w-[100px] md:max-w-none">{{ Auth::guard('trainer')->user()->name }}</span>
                                    <span class="ml-1 text-blue-300 text-xs">(Trener)</span>
                                    <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown menu -->
                                <div x-show="dropdownOpen" x-cloak
                                     class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                    <a href="{{ route('profile') }}"
                                       wire:navigate
                                       class="block w-full px-4 py-2 text-gray-800 text-left hover:bg-gray-100">
                                        Profil
                                    </a>
                                    <a href="{{ route('user.reservations') }}"
                                       wire:navigate
                                       class="block w-full px-4 py-2 text-gray-800 text-left hover:bg-gray-100">
                                        Moje rezerwacje
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                            Wyloguj się
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}"
                               wire:navigate
                               class="rounded-md px-2 md:px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Zaloguj
                            </a>
                            <a href="{{ route('register') }}"
                               wire:navigate
                               class="rounded-md px-2 md:px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Rejestracja
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileOpen" x-transition class="sm:hidden bg-gray-800">
                <div class="px-2 pt-2 pb-3 space-y-1">
               
                    <a href="{{ route('home') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'home' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Home
                    </a>
                    <a href="{{ route('posts.list') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'posts' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Posts
                    </a>
                    <a href="{{ route('trainers.list') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'about' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Trainers
                    </a>
                    <a href="{{ route('contact') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'contact' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Contact
                    </a>
                    <a href="{{ route('terms') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'terms' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Terms
                    </a>
                    
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           wire:navigate
                           @click="mobileOpen = false"
                           class="block w-full text-center rounded-md px-3 py-2 text-base font-medium bg-red-600 text-white hover:bg-red-700">
                            Admin Panel
                        </a>
                    @endif
                    
                    @if(Auth::guard('trainer')->check())
                        <a href="{{ route('trainer.reservations') }}"
                           wire:navigate
                           @click="mobileOpen = false"
                           class="block w-full text-center rounded-md px-3 py-2 text-base font-medium bg-blue-600 text-white hover:bg-blue-700">
                            Panel Trenera
                        </a>
                    @endif

                    <!-- Mobile Authentication buttons -->
                    @if(Auth::check())
                        <div x-data="{ dropdownOpen: false }" class="w-full">
                            <button @click="dropdownOpen = !dropdownOpen"
                                    class="block w-full text-center px-4 py-2 text-gray-900 font-medium bg-gray-200 rounded-md hover:bg-gray-300">
                                {{ Auth::user()->name }}
                            </button>
                            <div x-show="dropdownOpen" x-cloak
                                 class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-md">
                                <a href="{{ route('profile') }}"
                                   wire:navigate
                                   @click="dropdownOpen = false; mobileOpen = false"
                                   class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                    Profil
                                </a>
                                <a href="{{ route('user.reservations') }}"
                                   wire:navigate
                                   @click="dropdownOpen = false; mobileOpen = false"
                                   class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                    Moje rezerwacje
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                        Wyloguj się
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif(Auth::guard('trainer')->check())
                        <div x-data="{ dropdownOpen: false }" class="w-full">
                            <button @click="dropdownOpen = !dropdownOpen"
                                    class="block w-full text-center px-4 py-2 text-gray-900 font-medium bg-gray-200 rounded-md hover:bg-gray-300">
                                {{ Auth::guard('trainer')->user()->name }} <span class="text-blue-600 text-xs">(Trener)</span>
                            </button>
                            <div x-show="dropdownOpen" x-cloak
                                 class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-md">
                                <a href="{{ route('profile') }}"
                                   wire:navigate
                                   @click="dropdownOpen = false; mobileOpen = false"
                                   class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                    Profil
                                </a>
                                <a href="{{ route('user.reservations') }}"
                                   wire:navigate
                                   @click="dropdownOpen = false; mobileOpen = false"
                                   class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                    Moje rezerwacje
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                        Wyloguj się
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           wire:navigate
                           @click="mobileOpen = false"
                           class="block w-full text-center rounded-md px-4 py-2 text-gray-900 bg-gray-200 hover:bg-gray-300">
                            Zaloguj się
                        </a>
                        <a href="{{ route('register') }}"
                           wire:navigate
                           @click="mobileOpen = false"
                           class="block w-full text-center rounded-md px-4 py-2 text-gray-900 bg-gray-200 hover:bg-gray-300">
                            Zarejestruj się
                        </a>
                    @endif
                </div>
            </div>
        </nav>
        
        <!-- Wyszukiwarka pod nawigacją -->
        <div class="bg-gray-700 py-4 shadow-md">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative">
                    <input type="text"
                           wire:model="searchQuery"
                           wire:keydown.enter="goToSearch"
                           placeholder="Wyszukaj artykuły, porady, tematy..."
                           class="w-full p-3 pl-5 pr-24 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md text-gray-700 text-base" />
                    <button wire:click="goToSearch"
                            class="absolute right-0 top-0 bottom-0 h-full text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg px-5 transition-all duration-200 ease-in-out shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="font-medium">Szukaj</span>
                    </button>
                </div>
                
                <!-- Toast pod wyszukiwarką -->
                @if(!empty($toastMessage))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-init="setTimeout(() => { show = false; $wire.resetToast(); }, 2000)"
                     class="mt-2 bg-gray-800 text-white px-4 py-3 rounded-md text-sm font-medium flex items-center border border-gray-600 shadow-lg"
                     id="search-toast">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ $toastMessage }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>