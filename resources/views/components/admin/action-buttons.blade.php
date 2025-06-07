@props(['actions' => []])

@php
$buttonStyles = [
    'primary' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-blue-100 text-blue-700 hover:bg-blue-200',
    'success' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-green-100 text-green-700 hover:bg-green-200',
    'warning' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-orange-100 text-orange-700 hover:bg-orange-200',
    'danger' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-red-100 text-red-700 hover:bg-red-200',
    'info' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-indigo-100 text-indigo-700 hover:bg-indigo-200',
    'secondary' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200'
];
@endphp

<div class="flex flex-wrap items-center justify-end gap-1">
    @foreach($actions as $action)
        @if($action['type'] === 'link')
            <a href="{{ $action['url'] }}" 
               @if(isset($action['navigate']) && $action['navigate']) wire:navigate @endif
               class="{{ $buttonStyles[$action['style']] ?? $buttonStyles['secondary'] }}"
               @if(isset($action['title'])) title="{{ $action['title'] }}" @endif>
                {{ $action['label'] }}
            </a>
        @elseif($action['type'] === 'button')
            <button wire:click="{{ $action['action'] }}" 
                    @if(isset($action['loading'])) wire:loading.attr="disabled" @endif
                    class="{{ $buttonStyles[$action['style']] ?? $buttonStyles['secondary'] }}"
                    @if(isset($action['title'])) title="{{ $action['title'] }}" @endif>
                {{ $action['label'] }}
            </button>
        @endif
    @endforeach
</div> 