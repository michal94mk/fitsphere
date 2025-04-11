<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Panel administratora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-gray-800">
                <div class="flex items-center h-16 px-4 bg-gray-900">
                    <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold text-white">
                        {{ config('app.name') }} Admin
                    </a>
                </div>
                <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                    <nav class="flex-1 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Użytkownicy
                                <svg class="w-5 h-5 ml-auto" :class="{'rotate-90': open, 'rotate-0': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div class="pl-6 mt-1 space-y-1" x-show="open" style="display: none;">
                                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                                    Wszyscy użytkownicy
                                </a>
                                <a href="{{ route('admin.users.create') }}" class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.create') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                                    Dodaj użytkownika
                                </a>
                            </div>
                        </div>
                        
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Trenerzy
                                <svg class="w-5 h-5 ml-auto" :class="{'rotate-90': open, 'rotate-0': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div class="pl-6 mt-1 space-y-1" x-show="open" style="display: none;">
                                <a href="{{ route('admin.trainers.index') }}" class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.trainers.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                                    Wszyscy trenerzy
                                </a>
                                <a href="{{ route('admin.trainers.create') }}" class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.trainers.create') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                                    Dodaj trenera
                                </a>
                            </div>
                        </div>
                        
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                </svg>
                                Artykuły
                                <svg class="w-5 h-5 ml-auto" :class="{'rotate-90': open, 'rotate-0': !open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                            <div class="pl-6 mt-1 space-y-1" x-show="open" style="display: none;">
                                <a href="{{ route('admin.posts.index') }}" class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.posts.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                                    Wszystkie artykuły
                                </a>
                                <a href="{{ route('admin.posts.create') }}" class="block px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.posts.create') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }} rounded-md">
                                    Dodaj artykuł
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center">
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-8 h-8 rounded-full mr-3">
                        <div>
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="ml-auto text-gray-400 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile sidebar -->
        <div 
            x-show="sidebarOpen" 
            x-transition:enter="transition-opacity ease-linear duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="transition-opacity ease-linear duration-300" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="fixed inset-0 z-40 md:hidden">
            <div class="absolute inset-0 bg-gray-600 opacity-75" @click="sidebarOpen = false"></div>
            
            <div class="relative flex flex-col flex-1 w-full max-w-xs bg-gray-800">
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
                
                <div class="flex-shrink-0 flex items-center h-16 px-4 bg-gray-900">
                    <span class="text-lg font-bold text-white">{{ config('app.name') }} Admin</span>
                </div>
                
                <div class="mt-5 flex-1 h-0 overflow-y-auto">
                    <nav class="px-2 space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('admin.dashboard') ? 'text-white bg-gray-700' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            <svg class="mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        
                        <!-- More mobile menu items -->
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Mobile header -->
            <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 flex items-center justify-between shadow">
                <button 
                    @click="sidebarOpen = true" 
                    class="p-2 text-gray-500 rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <span class="text-lg font-medium text-gray-900">{{ $header ?? 'Dashboard' }}</span>
                <div class="w-8"></div> <!-- Spacer for balance -->
            </div>
            
            <!-- Page heading -->
            <header class="bg-white shadow hidden md:block">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h1 class="text-xl font-semibold text-gray-900">
                        {{ $header ?? 'Dashboard' }}
                    </h1>
                </div>
            </header>
            
            <!-- Main content -->
            <main class="flex-1 overflow-y-auto focus:outline-none">
                <div class="py-6">
                    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                        @if (session('success'))
                            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if (session('error'))
                            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    
    @livewireScripts
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
