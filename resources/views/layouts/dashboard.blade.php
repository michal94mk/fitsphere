<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
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
                        @yield('dashboard-name')
                    </span>
                </div>
                
                <!-- Sidebar navigation -->
                <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                    <nav class="flex-1 space-y-2">
                        @yield('sidebar-navigation')
                    </nav>
                </div>
                
                <!-- User profile area -->
                <div class="p-4 border-t border-gray-700">
                    <div class="flex items-center">
                        @yield('user-profile')
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
                    <span class="text-lg font-bold text-white">@yield('dashboard-name')</span>
                </div>
                
                <!-- Mobile sidebar navigation -->
                <div class="mt-5 flex-1 h-0 overflow-y-auto">
                    <nav class="px-2 space-y-1">
                        @yield('mobile-navigation')
                    </nav>
                </div>
            </div>
        </div>
        
        <!-- Content area -->
        <div class="flex flex-col flex-1 w-0 overflow-hidden">
            <!-- Mobile header -->
            <div class="md:hidden pl-1 pt-1 sm:pl-3 sm:pt-3 flex items-center justify-between shadow bg-white">
                <button 
                    @click="sidebarOpen = true" 
                    class="p-2 text-gray-500 rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <span class="text-lg font-medium text-gray-900">@yield('mobile-title', 'Dashboard')</span>
                <div class="w-8"></div> <!-- Spacer for balance -->
            </div>
            
            <!-- Page heading -->
            <header class="bg-white shadow hidden md:block">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <h1 class="text-xl font-semibold text-gray-900">
                        @yield('header-title', 'Dashboard')
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
</body>
</html> 