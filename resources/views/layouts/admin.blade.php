<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Panel Admina - Zdrowie & Fitness Blog' }}</title>
  <meta name="description" content="Panel administracyjny dla Zdrowie & Fitness Blog.">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
  <!-- Nagłówek panelu admina -->
  <header>
    <nav class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <div class="flex items-center">
          <a href="{{ route('admin.dashboard') }}" class="text-white text-lg font-bold">Panel Admina</a>
        </div>
        <div class="flex items-center space-x-4">
          <a href="{{ route('profile.edit') }}" class="text-gray-300 px-3 py-2">Profil</a>
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-gray-300 px-3 py-2">Wyloguj się</button>
          </form>
        </div>
      </div>
    </nav>
  </header>

  <!-- Główna zawartość panelu admina -->
  <div class="flex flex-grow">
    <!-- Sidebar admina -->
    <aside class="w-64 bg-gray-200 p-4">
      <ul>
        <li class="mb-2">
          <a href="{{ route('admin.dashboard') }}" class="text-gray-800 hover:text-blue-500">Dashboard</a>
        </li>
        <li class="mb-2">
          <a href="{{ route('admin.posts.index') }}" class="text-gray-800 hover:text-blue-500">Posty</a>
        </li>
        <li class="mb-2">
          <a href="{{ route('admin.categories.index') }}" class="text-gray-800 hover:text-blue-500">Kategorie</a>
        </li>
      </ul>
    </aside>
    
    <!-- Główna sekcja zawartości admina -->
    <main class="flex-grow p-4">
      {{ $slot }}
    </main>
  </div>

  <!-- Stopka panelu admina -->
  <footer class="bg-gray-800 text-center py-4 text-gray-600">
    <p class="text-sm">&copy; 2024 Panel Admina - Zdrowie & Fitness Blog</p>
  </footer>
</body>
</html>
