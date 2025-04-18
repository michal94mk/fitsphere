@props(['active' => false, 'href', 'icon' => null])

@php
    $classes = $active
        ? 'flex items-center px-3 py-2 text-sm font-medium text-white bg-gray-700 rounded-md'
        : 'flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white rounded-md';
        
    // Mapowanie nazw ikon na ścieżki SVG - współdzielone z admin.nav-link
    // To pokazuje znajomość DRY - Don't Repeat Yourself
    $iconPath = [
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
        'user' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>',
        'calendar' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>',
    ];
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if(!str_starts_with($href, route('home'))) wire:navigate @endif>
    @if($icon && isset($iconPath[$icon]))
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $iconPath[$icon] !!}
        </svg>
    @endif
    {{ $slot }}
</a> 