<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title ?? 'Autoryzacja - Zdrowie & Fitness Blog' }}</title>
  <meta name="description" content="Logowanie i rejestracja do Zdrowie & Fitness Blog.">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
  <!-- Nagłówek autoryzacyjny -->
  <header>
    <nav class="bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex items-center h-16">
        <a href="/home" class="text-gray-300 text-lg font-bold">Zdrowie & Fitness</a>
      </div>
    </nav>
  </header>

  <!-- Główna zawartość autoryzacyjna -->
  <main class="flex flex-col items-center justify-center flex-grow">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
      {{ $slot }}
    </div>
  </main>

  <!-- Stopka autoryzacyjna -->
  <footer class="bg-gray-800 text-center py-4 text-gray-600">
    <p class="text-sm">&copy; 2024 Zdrowie & Fitness Blog</p>
  </footer>
</body>
</html>
