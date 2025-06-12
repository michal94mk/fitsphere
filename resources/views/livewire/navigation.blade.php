<div x-data="{ mobileOpen: false, profileDropdownOpen: false }">
    <div>
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-2 sm:px-3 lg:px-4">
                <div class="relative flex flex-wrap items-center justify-between py-2">
                    
                    <!-- Mobile Menu button -->
                    <div class="flex-shrink-0 flex items-center sm:hidden">
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
                    
                    <!-- Logo or site name - visible on all devices -->
                    <div class="flex-shrink-0 flex items-center sm:hidden">
                        <a href="{{ route('home') }}" wire:navigate class="text-white font-bold text-xl">FitSphere</a>
                    </div>
                    
                    <!-- Desktop navigation -->
                    <div class="hidden sm:flex sm:flex-wrap items-center justify-between w-full">
                        <!-- Left side navigation links -->
                        <div class="flex flex-wrap items-center py-1 gap-1 md:gap-1.5 lg:gap-2">
                            <a href="{{ route('home') }}" 
                               wire:navigate
                               class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium {{ $currentPage == 'home' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                {{ __('common.home') }}
                            </a>
                            <a href="{{ route('posts.list') }}"
                               wire:navigate
                               class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium {{ $currentPage == 'posts' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <span data-i18n="common.posts">{{ __('common.posts') }}</span>
                            </a>
                            <a href="{{ route('trainers.list') }}"
                               wire:navigate
                               class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium {{ $currentPage == 'trainers' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <span data-i18n="common.trainers">{{ __('common.trainers') }}</span>
                            </a>
                            
                            <!-- Nutrition links -->
                            <div class="hidden md:block">
                                <a href="{{ route('nutrition.calculator') }}"
                                   wire:navigate
                                   class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium {{ $currentPage == 'nutrition.calculator' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <span data-i18n="common.nutrition_calculator">{{ __('common.nutrition_calculator') }}</span>
                                </a>
                            </div>
                            
                            <div class="hidden md:block">
                                <a href="{{ route('meal-planner') }}"
                                   wire:navigate
                                   class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium {{ $currentPage == 'meal-planner' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                    <span data-i18n="common.meal_planner">{{ __('common.meal_planner') }}</span>
                                </a>
                            </div>
                            
                            <!-- Nutrition dropdown for smaller screens -->
                            <div class="md:hidden" x-data="{ nutritionOpen: false }">
                                <button @click="nutritionOpen = !nutritionOpen" 
                                        class="rounded-md px-1 sm:px-1.5 py-1.5 text-xs sm:text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white flex items-center">
                                    <span>{{ __('common.nutrition') }}</span>
                                    <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <div x-show="nutritionOpen" x-cloak
                                     class="absolute z-50 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg">
                                    <a href="{{ route('nutrition.calculator') }}"
                                       wire:navigate
                                       @click="nutritionOpen = false"
                                       class="block w-full px-4 py-2 text-gray-800 text-left hover:bg-gray-100">
                                        <span>{{ __('common.nutrition_calculator') }}</span>
                                    </a>
                                    <a href="{{ route('meal-planner') }}"
                                       wire:navigate
                                       @click="nutritionOpen = false"
                                       class="block w-full px-4 py-2 text-gray-800 text-left hover:bg-gray-100">
                                        <span>{{ __('common.meal_planner') }}</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Contact and Terms links -->
                            <a href="{{ route('contact') }}"
                               wire:navigate
                               class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium {{ $currentPage == 'contact' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <span data-i18n="common.contact">{{ __('common.contact') }}</span>
                            </a>
                            <a href="{{ route('terms') }}"
                               wire:navigate
                               class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium {{ $currentPage == 'terms' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                                <span data-i18n="common.terms">{{ __('common.terms') }}</span>
                            </a>
                            

                        </div>

                        <!-- Right side - authentication and language -->
                        <div class="flex items-center space-x-2">
                            <!-- Language Switcher Component -->
                            <livewire:language-switcher />
                            
                            @if(Auth::check())
                                <div x-data="{ dropdownOpen: false }" class="relative">
                                    <button @click="dropdownOpen = !dropdownOpen"
                                            class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 bg-gray-200 hover:bg-gray-300 rounded-md focus:outline-none">
                                        <div class="w-8 h-8 rounded-full overflow-hidden">
                                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <span>{{ Auth::user()->name }}</span>
                                        @if(Auth::user()->isAdmin())
                                            <span class="text-red-600 text-xs">({{ __('common.admin') }})</span>
                                        @elseif(Auth::user()->isTrainer())
                                            <span class="text-blue-600 text-xs">({{ __('common.trainer') }})</span>
                                        @endif
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="dropdownOpen" 
                                         x-cloak
                                         @click.away="dropdownOpen = false"
                                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                        <div class="py-1">
                                            <div class="px-4 py-2 border-b border-gray-100">
                                                <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                                            </div>
                                            
                                            {{-- Admin Section --}}
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{ route('profile') }}"
                                                   wire:navigate
                                                   @click="dropdownOpen = false"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ __('common.profile') }}
                                                </a>
                                                <a href="{{ route('admin.dashboard') }}"
                                                   wire:navigate
                                                   @click="dropdownOpen = false"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ __('common.admin_panel') }}
                                                </a>
                                                {{-- Admin can also make reservations if has user role --}}
                                                @if(Auth::user()->isUser())
                                                    <a href="{{ route('user.reservations') }}"
                                                       wire:navigate
                                                       @click="dropdownOpen = false"
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        {{ __('common.my_reservations') }}
                                                    </a>
                                                @endif
                                            {{-- Trainer Section (non-admin trainers) --}}
                                            @elseif(Auth::user()->isTrainer())
                                                <a href="{{ route('trainer.profile') }}"
                                                   wire:navigate
                                                   @click="dropdownOpen = false"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ __('common.trainer_profile') }}
                                                </a>
                                                <a href="{{ route('trainer.my.reservations') }}"
                                                   wire:navigate
                                                   @click="dropdownOpen = false"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ __('common.my_reservations') }}
                                                </a>
                                            {{-- Regular User Section --}}
                                            @elseif(Auth::user()->isUser())
                                                <a href="{{ route('profile') }}"
                                                   wire:navigate
                                                   @click="dropdownOpen = false"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ __('common.profile') }}
                                                </a>
                                                <a href="{{ route('user.reservations') }}"
                                                   wire:navigate
                                                   @click="dropdownOpen = false"
                                                   class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                    {{ __('common.my_reservations') }}
                                                </a>
                                            @endif
                                            
                                            <div class="border-t border-gray-100">
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                        {{ __('common.logout') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex space-x-1">
                                    <a href="{{ route('login') }}"
                                       wire:navigate
                                       class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                        <span data-i18n="common.login">{{ __('common.login') }}</span>
                                    </a>
                                    <a href="{{ route('register') }}"
                                       wire:navigate
                                       class="rounded-md px-1 md:px-1.5 lg:px-2 py-1.5 text-xs sm:text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                                        <span data-i18n="common.register">{{ __('common.register') }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileOpen" x-transition class="sm:hidden bg-gray-800">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- Language Switcher Component (mobile) -->
                    <div class="flex justify-center mb-2 py-2">
                        <livewire:language-switcher />
                    </div>
               
                    <a href="{{ route('home') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'home' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        {{ __('common.home') }}
                    </a>
                    <a href="{{ route('posts.list') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'posts' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <span data-i18n="common.posts">{{ __('common.posts') }}</span>
                    </a>
                    <a href="{{ route('trainers.list') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'trainers' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <span data-i18n="common.trainers">{{ __('common.trainers') }}</span>
                    </a>
                    
                    <!-- Linki do funkcjonalności żywieniowych (wersja mobilna) -->
                    <a href="{{ route('nutrition.calculator') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'nutrition.calculator' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <span data-i18n="common.nutrition_calculator">{{ __('common.nutrition_calculator') }}</span>
                    </a>
                    
                    <a href="{{ route('meal-planner') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'meal-planner' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <span data-i18n="common.meal_planner">{{ __('common.meal_planner') }}</span>
                    </a>
                    
                    <a href="{{ route('contact') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'contact' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <span data-i18n="common.contact">{{ __('common.contact') }}</span>
                    </a>
                    <a href="{{ route('terms') }}"
                       wire:navigate
                       @click="mobileOpen = false"
                       class="block w-full text-center rounded-md px-3 py-2 text-base font-medium {{ $currentPage == 'terms' ? 'bg-gray-900 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        <span data-i18n="common.terms">{{ __('common.terms') }}</span>
                    </a>
                    

                    
                    @if(Auth::check())
                        <div x-data="{ dropdownOpen: false }" class="w-full">
                            <button @click="dropdownOpen = !dropdownOpen"
                                    class="flex items-center justify-center w-full px-4 py-2 text-gray-900 font-medium bg-gray-200 rounded-md hover:bg-gray-300">
                                <div class="w-6 h-6 rounded-full overflow-hidden mr-2">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                </div>
                                {{ Auth::user()->name }}
                                @if(Auth::user()->isAdmin())
                                    <span class="text-red-600 text-xs ml-1">({{ __('common.admin') }})</span>
                                @elseif(Auth::user()->isTrainer())
                                    <span class="text-blue-600 text-xs ml-1">({{ __('common.trainer') }})</span>
                                @endif
                            </button>
                            <div x-show="dropdownOpen" x-cloak
                                 class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-md">
                                <div class="py-1">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-medium text-gray-900 text-center">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500 text-center">{{ Auth::user()->email }}</p>
                                    </div>
                                    
                                    {{-- Admin Section --}}
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('profile') }}"
                                           wire:navigate
                                           @click="dropdownOpen = false"
                                           class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                             {{ __('common.profile') }}
                                         </a>
                                         <a href="{{ route('admin.dashboard') }}"
                                            wire:navigate
                                            @click="dropdownOpen = false"
                                            class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                             {{ __('common.admin_panel') }}
                                         </a>
                                         {{-- Admin can also make reservations if has user role --}}
                                         @if(Auth::user()->isUser())
                                             <a href="{{ route('user.reservations') }}"
                                                wire:navigate
                                                @click="dropdownOpen = false"
                                                class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                                 {{ __('common.my_reservations') }}
                                             </a>
                                         @endif
                                    {{-- Trainer Section (non-admin trainers) --}}
                                    @elseif(Auth::user()->isTrainer())
                                        <a href="{{ route('trainer.profile') }}"
                                           wire:navigate
                                           @click="dropdownOpen = false"
                                           class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                            {{ __('common.trainer_profile') }}
                                        </a>
                                        <a href="{{ route('trainer.my.reservations') }}"
                                           wire:navigate
                                           @click="dropdownOpen = false"
                                           class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                            {{ __('common.my_reservations') }}
                                        </a>
                                    {{-- Regular User Section --}}
                                    @elseif(Auth::user()->isUser())
                                        <a href="{{ route('profile') }}"
                                           wire:navigate
                                           @click="dropdownOpen = false"
                                           class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                            {{ __('common.profile') }}
                                        </a>
                                        <a href="{{ route('user.reservations') }}"
                                           wire:navigate
                                           @click="dropdownOpen = false"
                                           class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                            {{ __('common.my_reservations') }}
                                        </a>
                                    @endif
                                    
                                    <div class="border-t border-gray-100">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                    class="block w-full text-center px-4 py-2 text-gray-900 hover:bg-gray-100">
                                                {{ __('common.logout') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           wire:navigate
                           @click="mobileOpen = false"
                           class="block w-full text-center rounded-md px-4 py-2 text-gray-900 bg-gray-200 hover:bg-gray-300">
                            <span data-i18n="common.login">{{ __('common.login') }}</span>
                        </a>
                        <a href="{{ route('register') }}"
                           wire:navigate
                           @click="mobileOpen = false"
                           class="block w-full text-center rounded-md px-4 py-2 text-gray-900 bg-gray-200 hover:bg-gray-300">
                            <span data-i18n="common.register">{{ __('common.register') }}</span>
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
                           placeholder="{{ __('common.search_placeholder') }}"
                           class="w-full p-3 pl-5 pr-24 rounded-lg border-0 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-md text-gray-700 text-base" />
                    <button wire:click="goToSearch"
                            class="absolute right-0 top-0 bottom-0 h-full text-white bg-blue-600 hover:bg-blue-700 rounded-r-lg px-3 sm:px-5 transition-all duration-200 ease-in-out shadow-md flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="font-medium text-sm sm:text-base" data-i18n="common.search">{{ __('common.search') }}</span>
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