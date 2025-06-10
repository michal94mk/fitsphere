@extends('layouts.app')

@section('title', $title ?? 'Zdrowie & Fitness Blog')

@section('head')
    <meta name="description" content="Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.">
@endsection

@section('body-class', 'bg-gray-100 text-gray-900 min-h-screen flex flex-col font-sans antialiased')

@section('body')
    <!-- Subscription Modal -->
    <livewire:subscription-modal />

    <!-- Header with navigation -->
    <header>
        <livewire:navigation />
    </header>

    <!-- Flash Messages -->
    <livewire:flash-messages />

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <livewire:footer />
    </footer>
    
    <!-- Scroll-to-top button -->
    @include('partials.scroll-to-top')
@endsection