<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Zdrowie & Fitness Blog' }}</title>
    <meta name="description" content="Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
    <!-- Subscription Modal -->
    <livewire:subscription-modal />

    <!-- Header with navigation -->
    <header>
        <livewire:navigation />
    </header>

    <main>
        <livewire:page-content />
    </main>

    <footer>
        <livewire:footer />
    </footer>
    <!-- Scroll-to-top button -->
    @include('partials.scroll-to-top')

    @livewireScripts
</body>
</html>
