@props(['type' => 'button', 'style' => 'primary', 'size' => 'normal', 'href' => null, 'navigate' => false, 'loading' => false])

@php
$baseClasses = 'inline-flex items-center border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring transition ease-in-out duration-150';

$sizeClasses = [
    'small' => 'px-3 py-1.5',
    'normal' => 'px-4 py-2',
    'large' => 'px-6 py-3'
];

$styleClasses = [
    'primary' => 'bg-blue-600 border-transparent text-white hover:bg-blue-700 active:bg-blue-900 focus:border-blue-900 focus:ring-blue-300 disabled:opacity-25',
    'secondary' => 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 active:bg-gray-100 focus:border-blue-300 focus:ring-blue-200 disabled:opacity-25',
    'success' => 'bg-green-600 border-transparent text-white hover:bg-green-700 active:bg-green-900 focus:border-green-900 focus:ring-green-300 disabled:opacity-25',
    'danger' => 'bg-red-600 border-transparent text-white hover:bg-red-700 active:bg-red-900 focus:border-red-900 focus:ring-red-300 disabled:opacity-25',
    'warning' => 'bg-yellow-600 border-transparent text-white hover:bg-yellow-700 active:bg-yellow-900 focus:border-yellow-900 focus:ring-yellow-300 disabled:opacity-25',
    'info' => 'bg-indigo-600 border-transparent text-white hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:ring-indigo-300 disabled:opacity-25'
];

$classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['normal']) . ' ' . ($styleClasses[$style] ?? $styleClasses['primary']);
@endphp

@if($href)
    <a href="{{ $href }}" 
       @if($navigate) wire:navigate @endif
       {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" 
            @if($loading) wire:loading.attr="disabled" @endif
            {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif 