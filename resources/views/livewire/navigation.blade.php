<div x-data="{ currentPage: @entangle('currentPage'), mobileOpen: false, profileDropdownOpen: false }" 
     x-init="$watch('currentPage', value => { 
         window.dispatchEvent(new CustomEvent('page-changed'));
         history.pushState(null, '', '/' + value);
     })">

    <div>
        <!-- Navigation bar -->
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex min-h-[4rem] items-center justify-between">
                    <!-- Left side: Desktop Menu -->
                    <div class="hidden sm:flex space-x-4">
                        <button @click="currentPage='home'" wire:click="goToPage('home')" 
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                            Home
                        </button>
                        <button @click="currentPage='posts'" wire:click="goToPage('posts')" 
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                            Posts
                        </button>
                        <button @click="currentPage='about'" wire:click="goToPage('about')" 
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                            About
                        </button>
                        <button @click="currentPage='contact'" wire:click="goToPage('contact')" 
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                            Contact
                        </button>
                        <button @click="currentPage='terms'" wire:click="goToPage('terms')" 
                                class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                            Terms
                        </button>

                        @if(auth()->check() && auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="rounded-md px-3 py-2 text-sm font-medium bg-red-600 text-white hover:bg-red-700">
                                Admin Panel
                            </a>
                        @endif
                    </div>

                    <!-- Right side: Authentication buttons -->
                    <div class="hidden sm:flex items-center space-x-4">
                        @auth
                            <!-- User profile dropdown -->
                            <div x-data="{ dropdownOpen: false }" 
                                 x-init="$watch('currentPage', () => { dropdownOpen = false; })"
                                 class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" 
                                        class="text-gray-300 px-3 py-2 rounded-md hover:bg-gray-700">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <!-- Dropdown menu -->
                                <div x-show="dropdownOpen" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                <a href="{{ route('profile') }}" 
                               class="block w-full px-4 py-2 text-gray-800 text-left hover:bg-gray-100">
                                Profil
                            </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                            Wyloguj się
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <button @click="currentPage='login'" wire:click="goToPage('login')" 
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Zaloguj się
                            </button>
                            <button @click="currentPage='register'" wire:click="goToPage('register')" 
                                    class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                Zarejestruj się
                            </button>
                        @endauth
                    </div>

                    <!-- Mobile menu button -->
                    <div class="sm:hidden flex items-center">
                        <button @click="mobileOpen = !mobileOpen" class="text-gray-300 hover:text-white focus:outline-none">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen }" class="inline-flex"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                                <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen }" class="hidden"
                                      stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

<!-- Mobile Menu -->
<div x-show="mobileOpen" x-transition class="sm:hidden">
    <div class="px-2 pt-2 pb-3 space-y-1">
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
            <!-- Mobile User Profile Dropdown -->
            <div x-data="{ dropdownOpen: false }" class="w-full">
                <button @click="dropdownOpen = !dropdownOpen" 
                        class="block w-full px-4 py-2 text-gray-900 font-medium bg-gray-200 rounded-md hover:bg-gray-300">
                    {{ Auth::user()->name }}
                </button>
                <div x-show="dropdownOpen" x-cloak class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-md">
                    <button @click="currentPage='profile'; dropdownOpen = false; mobileOpen = false" wire:click="goToPage('profile')"
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

        </nav>

        <!-- Page Content -->
        <div>
            <!-- Dynamic page content based on currentPage -->
            @if($currentPage === 'home')
                <livewire:home-page wire:key="home-page" />
            @elseif($currentPage === 'posts')
                <livewire:posts-page wire:key="posts-page" />
            @elseif($currentPage === 'about')
                <livewire:about-page wire:key="about-page" />
            @elseif($currentPage === 'contact')
                <livewire:contact-page wire:key="contact-page" />
            @elseif($currentPage === 'terms')
                <livewire:terms-page wire:key="terms-page" />
            @elseif($currentPage === 'login')
                <livewire:auth.login wire:key="login" />
            @elseif($currentPage === 'register')
                <livewire:auth.register wire:key="register" />
            @elseif($currentPage === 'profile')
                <livewire:profile.profile wire:key="profile" />
            @elseif($currentPage === 'forgot-password')
                <livewire:auth.forgot-password wire:key="forgot-password" />
            @endif
        </div>
    </div>
</div>
