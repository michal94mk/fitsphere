<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Strona główna')</title>
    <meta name="description" content="{{ $description ?? 'Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.' }}">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🏋️</text></svg>">

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
    
    @stack('styles')
    @yield('head')
</head>
<body class="font-sans antialiased @yield('body-class', 'bg-gray-100')">
    @yield('body')
    
    @livewireScripts
    @stack('scripts')
    
    <!-- Asynchronous language change handling -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Store current language in localStorage
            localStorage.setItem('app_locale', '{{ app()->getLocale() }}');
            
            // Listen for language change events
            Livewire.on('language-changed', ({ locale }) => {
                // Update language in localStorage
                localStorage.setItem('app_locale', locale);
                
                // Update lang attribute in html tag
                document.documentElement.lang = locale.replace('_', '-');
                
                // Refresh all Livewire components on the page
                Livewire.all().forEach(component => {
                    if (component.$wire.$refresh) {
                        component.$wire.$refresh();
                    }
                });
            });
        });
    </script>
</body>
</html> 