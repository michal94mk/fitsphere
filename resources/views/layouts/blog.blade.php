<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Zdrowie & Fitness Blog' }}</title>
  <meta name="description" content="Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.">
  <!-- Tailwind CSS -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
  <!-- Nagłówek -->
  <header>
    <nav class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
          <!-- Lewa strona: linki nawigacyjne -->
          <div class="flex space-x-4">
            <a href="{{ route('home') }}" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Strona Główna</a>
            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Treningi</a>
            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Dieta</a>
            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Zdrowie Psychiczne</a>
            <a href="#" class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">Porady</a>
          </div>
          <!-- Prawa strona: autoryzacja -->
          <div class="flex space-x-4 items-center">
            @auth
              <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="text-gray-300 px-3 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                  {{ Auth::user()->name }}
                  <svg class="ml-1 inline-block h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                  </svg>
                </button>
                <!-- Rozwijane menu -->
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-transition class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg">
                  <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">Wyloguj</button>
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
    </nav>
    <!-- Baner -->
    <section class="bg-gray-900 text-white py-16 text-center">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold">Zdrowie & Fitness</h1>
        <p class="mt-2 text-lg text-gray-400">Trenuj, jedz zdrowo i dbaj o siebie!</p>
        <button class="mt-4 px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-600">Rozpocznij swoją podróż</button>
      </div>
    </section>
  </header>

  <!-- Główna zawartość -->
  <main class="container mx-auto mt-4 p-4 flex-grow">
    {{ $slot }}
  </main>

  <!-- Stopka -->
  <footer class="bg-gray-800 text-center py-6 text-gray-600">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <p class="mb-4">&copy; 2024 Zdrowie & Fitness Blog</p>
      <form action="#" method="POST" class="flex justify-center">
        <input type="email" name="newsletter" placeholder="Podaj swój e-mail" class="px-4 py-2 rounded-l-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-full hover:bg-blue-600">Subskrybuj</button>
      </form>
    </div>
  </footer>
</body>
</html>