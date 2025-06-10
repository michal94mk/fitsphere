{{-- Flash Messages Component - Zielony pasek u góry --}}
<div>
    @if($show && count($messages) > 0)
        @foreach($messages as $index => $message)
            <div 
                wire:key="flash-message-{{ $index }}"
                x-data="{ show: true }" 
                x-show="show"
                x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-[-100%] opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="w-full @if($message['type'] === 'success') bg-green-100 border-green-500 text-green-700 @elseif($message['type'] === 'error') bg-red-100 border-red-500 text-red-700 @elseif($message['type'] === 'warning') bg-yellow-100 border-yellow-500 text-yellow-700 @else bg-blue-100 border-blue-500 text-blue-700 @endif border-b-2 shadow-md"
            >
                <div class="container mx-auto px-4 py-3 flex items-center justify-center relative">
                    <div class="flex items-center">
                        @if($message['type'] === 'success')
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif($message['type'] === 'error')
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        @elseif($message['type'] === 'warning')
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        @endif
                        
                        <span class="text-sm font-medium">{{ $message['message'] }}</span>
                    </div>
                    
                    <button 
                        wire:click="removeMessage({{ $index }})"
                        @click="show = false"
                        type="button" 
                        class="absolute right-4 top-1/2 transform -translate-y-1/2 inline-flex text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                
                {{-- Auto-hide po 3 sekundach --}}
                <div x-init="setTimeout(() => { $wire.removeMessage({{ $index }}) }, 3000)"></div>
            </div>
        @endforeach
    @endif
</div>

 