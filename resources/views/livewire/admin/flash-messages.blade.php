{{-- 
    Komponent powiadomień - pokazuje znajomość zaawansowanych funkcji Livewire i Alpine.js
    Wykorzystane: wire:key, x-transition, wire:click
--}}
<div>
    @if($show)
        <div 
            x-data="{ show: true }" 
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-6"
        >
            @foreach($messages as $index => $message)
                <div 
                    wire:key="message-{{ $index }}"
                    class="p-4 {{ $message['type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-lg mb-2 flex justify-between items-center shadow-sm"
                >
                    <div class="flex items-center">
                        @if($message['type'] === 'success')
                            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                        <span class="text-sm font-medium">{{ $message['message'] }}</span>
                    </div>
                    <button 
                        wire:click="removeMessage({{ $index }})"
                        type="button" 
                        class="inline-flex rounded-md p-1.5 {{ $message['type'] === 'success' ? 'text-green-500 hover:bg-green-100' : 'text-red-500 hover:bg-red-100' }}"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            @endforeach
            
            @if(count($messages) > 1)
                <div class="text-right">
                    <button 
                        wire:click="hideMessages"
                        class="text-sm text-gray-500 hover:text-gray-700"
                    >
                        Ukryj wszystkie
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>