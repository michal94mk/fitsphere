@props(['type' => 'info', 'message', 'dismissible' => true])

@if($message)
<div class="p-4 mb-4 text-sm rounded-lg {{ $type === 'success' ? 'text-green-700 bg-green-100' : ($type === 'error' ? 'text-red-700 bg-red-100' : 'text-blue-700 bg-blue-100') }} {{ $dismissible ? 'relative' : '' }}" role="alert">
    {{ $message }}
    
    @if($dismissible)
        <button onclick="this.parentElement.remove()" class="absolute top-2 right-2 {{ $type === 'success' ? 'text-green-700 hover:text-green-900' : ($type === 'error' ? 'text-red-700 hover:text-red-900' : 'text-blue-700 hover:text-blue-900') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    @endif
</div>
@endif 