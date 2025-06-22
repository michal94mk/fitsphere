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
        @if($action && $action['type'] === 'link')
            <a href="{{ $action['url'] }}" 
               @if(isset($action['navigate']) && $action['navigate']) wire:navigate @endif
               class="{{ $buttonStyles[$action['style']] ?? $buttonStyles['secondary'] }}"
               @if(isset($action['title'])) title="{{ $action['title'] }}" @endif>
                {{ $action['label'] }}
            </a>
        @elseif($action && $action['type'] === 'button')
            <button wire:click="{{ $action['action'] }}" 
                    @if(isset($action['loading'])) 
                        wire:loading.attr="disabled" 
                        wire:target="{{ $action['action'] }}"
                    @endif
                    class="{{ $buttonStyles[$action['style']] ?? $buttonStyles['secondary'] }} disabled:opacity-50"
                    @if(isset($action['title'])) title="{{ $action['title'] }}" @endif>
                @if(isset($action['loading']))
                    <span wire:loading.remove wire:target="{{ $action['action'] }}">{{ $action['label'] }}</span>
                    <span wire:loading wire:target="{{ $action['action'] }}">
                        <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ isset($action['loading_label']) ? $action['loading_label'] : 'Loading...' }}
                    </span>
                @else
                    {{ $action['label'] }}
                @endif
            </button>
        @endif
    @endforeach
</div> 