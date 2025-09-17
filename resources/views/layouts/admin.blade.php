@extends('layouts.app')

@section('title', $title ?? __('admin.admin_panel'))

@section('body-class', 'bg-gray-100')

@section('body')
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar backdrop (mobile) -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="transition-opacity ease-linear duration-300" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 z-40 md:hidden"
            @click="sidebarOpen = false">
            <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
        </div>
        
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-gray-800">
                <!-- Sidebar header -->
                <div class="flex items-center h-16 px-4 bg-gray-900">
                    <span class="text-lg font-bold text-white">
                        {{ __('admin.admin_panel') }}
                    </span>
                </div>
                
                <!-- Sidebar navigation -->
                <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                    <nav class="flex-1 space-y-2">
                        <a href="{{ route('home') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 22V12h6v10M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            </svg>
                            {{ __('admin.back_to_site') }}
                        </a>
                        
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('admin.dashboard') }}
                        </a>
                        
                        <a href="{{ route('admin.users.index') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ __('admin.users') }}
                        </a>
                        
                        <a href="{{ route('admin.trainers.index') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.trainers.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('admin.trainers') }}
                        </a>
                        
                        <a href="{{ route('admin.posts.index') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.posts.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            {{ __('admin.posts') }}
                        </a>

                        <a href="{{ route('admin.categories.index') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.categories.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            {{ __('admin.manage_categories') }}
                        </a>
                        
                        <a href="{{ route('admin.comments.index') }}" wire:navigate class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.comments.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            {{ __('admin.comments') }}
                        </a>
                    </nav>
                </div>
                
                <!-- User profile area -->
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center justify-between">
                        @if(Auth::check())
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
                                    <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-400">{{ __('admin.admin') }}</p>
                                </div>
                            </div>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </a>
                        @else
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-500 mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium text-white">{{ __('Not Logged In') }}</p>
                                    <p class="text-xs text-gray-400">{{ __('Login') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('login') }}" class="text-gray-400 hover:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile sidebar -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition ease-in-out duration-300 transform" 
            x-transition:enter-start="-translate-x-full" 
            x-transition:enter-end="translate-x-0" 
            x-transition:leave="transition ease-in-out duration-300 transform" 
            x-transition:leave-start="translate-x-0" 
            x-transition:leave-end="-translate-x-full" 
            class="fixed inset-y-0 left-0 z-50 w-full md:hidden">
            
            <div class="relative flex flex-col flex-1 w-full max-w-xs bg-gray-800">
                <!-- Close button -->
                <div class="absolute top-0 right-0 pt-2 -mr-12">
                    <button 
                        @click="sidebarOpen = false" 
                        class="flex items-center justify-center w-10 h-10 ml-1 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile sidebar header -->
                <div class="flex-shrink-0 flex items-center h-16 px-4 bg-gray-900">
                    <span class="text-lg font-bold text-white">{{ __('admin.admin_panel') }}</span>
                </div>
                
                <!-- Mobile sidebar navigation -->
                <div class="mt-5 flex-1 h-0 overflow-y-auto">
                    <nav class="px-2 space-y-1">
                        <a href="{{ route('home') }}" wire:navigate class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:bg-gray-700 hover:text-white">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 22V12h6v10M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            </svg>
                            {{ __('admin.back_to_site') }}
                        </a>
                        
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ __('admin.dashboard') }}
                        </a>
                        
                        <a href="{{ route('admin.users.index') }}" wire:navigate class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            {{ __('admin.users') }}
                        </a>
                        
                        <a href="{{ route('admin.trainers.index') }}" wire:navigate class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.trainers.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('admin.trainers') }}
                        </a>
                        
                        <a href="{{ route('admin.posts.index') }}" wire:navigate class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.posts.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                            {{ __('admin.posts') }}
                        </a>
                        
                        <a href="{{ route('admin.categories.index') }}" wire:navigate class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            {{ __('admin.manage_categories') }}
                        </a>
                        
                        <a href="{{ route('admin.comments.index') }}" wire:navigate class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.comments.*') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                            {{ __('admin.comments') }}
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Content area -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Mobile header -->
            <div class="md:hidden px-3 py-2 flex items-center justify-between shadow bg-white">
                <button 
                    @click="sidebarOpen = true" 
                    class="p-2 text-gray-500 rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <span class="text-lg font-medium text-gray-900">{{ __('admin.admin_panel') }}</span>
                <div class="w-8"></div> <!-- Spacer for balance -->
            </div>
            
            <!-- Admin header navigation -->
            <header class="bg-white shadow border-b border-gray-200">
                <div class="px-4 py-3 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <!-- Page title -->
                        <h1 class="text-xl font-semibold text-gray-900 hidden md:block">
                            {{ $header ? __($header) : __('admin.dashboard') }}
                        </h1>
                        <div class="md:hidden text-lg font-medium text-gray-900">{{ $header ? __($header) : __('admin.admin_panel') }}</div>
                        
                        <!-- Right side - user info and actions -->
                        <div class="flex items-center space-x-4">
                            <!-- Language Switcher -->
                            <livewire:language-switcher />
                            
                            <!-- User dropdown -->
                            @if(Auth::check())
                                <div x-data="{ dropdownOpen: false }" class="relative">
                                    <button @click="dropdownOpen = !dropdownOpen"
                                            class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none">
                                        <div class="w-8 h-8 rounded-full overflow-hidden">
                                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <span>{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="dropdownOpen" 
                                         x-cloak
                                         @click.away="dropdownOpen = false"
                                         class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                                        <div class="py-1">
                                            <div class="px-4 py-2 border-b border-gray-100">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                                <p class="text-xs text-gray-500 truncate" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</p>
                                            </div>
                                            
                                            <a href="{{ route('profile') }}"
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                {{ __('common.profile') }}
                                            </a>
                                            
                                            {{-- Admin can also make reservations if has user role --}}
                                            @if(Auth::user()->isUser())
                                                <a href="{{ route('user.reservations') }}"
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
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                        {{ __('common.login') }}
                                    </a>
                                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                        {{ __('common.register') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main content -->
            <main class="flex-1 overflow-y-auto focus:outline-none">
                <div class="py-6 mt-16">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <!-- Flash Messages Component -->
                        <livewire:flash-messages />
                        
                        <!-- Toast Notifications -->
                        <livewire:admin.toast-notifications />
                        
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
@endsection 