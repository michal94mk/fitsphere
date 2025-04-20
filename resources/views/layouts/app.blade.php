<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Strona główna')</title>
    <meta name="description" content="{{ $description ?? 'Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Custom styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @yield('head')
</head>
<body class="font-sans antialiased @yield('body-class', 'bg-gray-100')">
    @yield('body')
    
    @livewireScripts
    @stack('scripts')
    
    <!-- Language Synchronization Script 
         Ensures language consistency between browser storage and server-side state.
         Triggers a language change event if needed during page initialization.
    -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Check if we have a saved locale in browser storage
            const savedLocale = localStorage.getItem('app_locale');
            
            // If locale in browser differs from server-side locale, notify components
            if (savedLocale && savedLocale !== '{{ app()->getLocale() }}') {
                // Broadcast the language change to all listening components
                Livewire.dispatch('language-changed', { locale: savedLocale });
            }
        });
    </script>
</body>
</html> 