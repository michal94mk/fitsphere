<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Zdrowie & Fitness Blog' }}</title>
    <meta name="description" content="Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.">
    
    <!-- Tailwind CSS Integration -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
    
    <!-- Header Section -->
    <header>
        <nav x-data="{ open: false }" class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    
                    <!-- Left Side: Logo / Home -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="text-gray-300 text-lg font-bold">Home</a>
                    </div>
                    
                    <!-- Desktop Navigation Links -->
                    <div class="hidden md:flex md:space-x-4">
                        <a href="{{ route('admin.dashboard') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
                        <a href="{{ route('admin.users.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Users</a>
                        <a href="{{ route('admin.posts.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Posts</a>
                        <a href="{{ route('admin.comments.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Comments</a>
                        <a href="{{ route('admin.categories.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Categories</a>
                    </div>
                    
                    <!-- Hamburger Button for Mobile -->
                    <div class="flex md:hidden">
                        <button @click="open = !open" type="button" class="inline-flex items-center justify-center rounded-md p-2 text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-orange-500">
                            <svg class="h-6 w-6" x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg class="h-6 w-6" x-show="open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Right Side: User Authentication -->
                    <div class="hidden md:flex md:items-center md:space-x-4">
                        @auth
                            <div x-data="{ dropdownOpen: false }" class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" class="text-gray-300 px-3 py-2 rounded-md hover:bg-gray-700 focus:outline-none">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="dropdownOpen" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">Wyloguj się</button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Login</a>
                            <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div x-show="open" x-cloak class="md:hidden">
                <div class="space-y-1 px-2 pt-2 pb-3">
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Users</a>
                    <a href="{{ route('admin.posts.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Posts</a>
                    <a href="{{ route('admin.comments.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Comments</a>
                    <a href="{{ route('admin.categories.index') }}" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Categories</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main class="flex-grow p-4">
        {{ $slot }}
    </main>

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-center py-6 text-gray-600">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <p class="mb-4">&copy; 2024 Zdrowie & Fitness Blog</p>
            <!-- Newsletter Subscription Form -->
            <form action="#" method="POST" class="flex justify-center">
                <input type="email" name="newsletter" placeholder="Enter your email" class="px-4 py-2 rounded-l-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-r-full hover:bg-orange-600 transform transition duration-300 hover:scale-105">Subscribe</button>
            </form>
        </div>
    </footer>
</body>
</html>
