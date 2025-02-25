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
  <header>
      <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 items-center justify-between">
            <!-- Left Side: Navigation Links -->
            <div class="flex space-x-4">
              <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Home</a>
            </div>
            <!-- Right Side: User Authentication -->
            <div class="flex space-x-4 items-center">
              @auth
                <!-- Dropdown Menu for Logged-in User -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                  <button @click="dropdownOpen = !dropdownOpen" class="text-gray-300 px-3 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    {{ Auth::user()->name }}
                    <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                  </button>
                  <!-- Dropdown Menu Content -->
                  <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profile</a>                    
                    <form method="POST" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">Logout</button>
                    </form>
                  </div>
                </div>
              @else
                <!-- Login and Register Links for Guest Users -->
                <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Login</a>
                <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Register</a>
              @endauth
            </div>
          </div>
        </div>
      </nav>
    </header>
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
