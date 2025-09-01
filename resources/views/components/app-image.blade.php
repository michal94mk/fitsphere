@props([
    'src' => null,
    'alt' => '',
    'fallback' => null,
    'type' => 'post', // post, trainer, user
    'username' => '',
    'class' => '',
    'width' => 800,
    'height' => 600
])

@php
use App\Helpers\ImageHelper;

$imageUrl = match($type) {
    'user', 'trainer' => ImageHelper::getUserAvatar($src, $username ?: $alt),
    default => ImageHelper::getImageAsset($src, $fallback)
};
@endphp

<img 
    src="{{ $imageUrl }}" 
    alt="{{ $alt }}" 
    {{ $attributes->merge(['class' => $class]) }}
    loading="lazy"
    onerror="this.src='{{ ImageHelper::getPlaceholderUrl($width, $height, 'FitSphere') }}'"
>
