<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel - Zdrowie & Fitness Blog</title>
  <meta name="description" content="Panel admina bloga o zdrowiu i fitness. Zarządzaj użytkownikami, postami i treściami.">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
  <!-- Header Section -->
  <header>
    <nav x-data="{ open: false, profileDropdownOpen: false }" class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <!-- Left Side: Logo / Home -->
          <div class="flex-shrink-0">
            <a href="/home" class="text-gray-300 text-lg font-bold">Home</a>
          </div>

          <!-- Desktop Navigation Links -->
          <div class="hidden md:flex md:space-x-4">
            <a href="/admin/dashboard" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
            <a href="/admin/users" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Users</a>
            <a href="/admin/posts" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Posts</a>
            <a href="/admin/comments" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Comments</a>
            <a href="/admin/categories" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Categories</a>
          </div>

          <!-- Desktop Right: User Authentication / Profile Dropdown -->
          <div class="hidden md:flex md:items-center md:space-x-4">
            <!-- Zakładamy, że użytkownik jest zalogowany – dostosuj wg potrzeb -->
            <div x-data="{ dropdownOpen: false }" class="relative">
            <button @click="dropdownOpen = !dropdownOpen" 
                                        class="text-gray-300 px-3 py-2 rounded-md hover:bg-gray-700">
                                    {{ Auth::user()->name }}
                                    <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" 
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
              <div x-show="dropdownOpen" x-cloak class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-50">
                <a href="/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                            Wyloguj się
                                        </button>
                                    </form>
              </div>
            </div>
          </div>

          <!-- Mobile Menu Button -->
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
        </div>
      </div>

      <!-- Mobile Navigation Menu -->
      <div x-show="open" x-cloak x-transition class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <a href="/admin/dashboard" @click="open = false" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
          <a href="/admin/users" @click="open = false" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Users</a>
          <a href="/admin/posts" @click="open = false" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Posts</a>
          <a href="/admin/comments" @click="open = false" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Comments</a>
          <a href="/admin/categories" @click="open = false" class="block rounded-md px-3 py-2 text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Categories</a>

          <!-- Mobile Authentication Buttons -->
          <!-- Zakładamy, że użytkownik jest zalogowany. Jeśli nie, zmień poniższy blok. -->
          <div x-data="{ dropdownOpen: false }" class="w-full">
          <button @click="dropdownOpen = !dropdownOpen" 
                        class="block w-full px-4 py-2 text-gray-900 font-medium bg-gray-200 rounded-md hover:bg-gray-300">
                    {{ Auth::user()->name }}
                </button>
            <div x-show="dropdownOpen" x-cloak class="mt-2 w-full bg-white border border-gray-300 rounded-md shadow-md">
              <a href="/profile" @click="open = false" class="block w-full px-4 py-2 text-gray-900 text-left hover:bg-gray-100">
                Profil
              </a>
              <form method="POST" action="/logout">
                <!-- Dodaj token CSRF, jeśli potrzebne -->
                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-900 hover:bg-gray-100">
                  Wyloguj się
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <!-- Main Content Section -->
  <main class="flex-grow p-4">
    {{ $slot }}
  </main>

    <!-- Footer section -->
    <footer>
        <livewire:footer />
    </footer>

    <!-- Scroll-to-top button -->
    @include('partials.scroll-to-top')
</body>
</html>
