<div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        {{ __('profile.trainer_info') }}
    </h2>



    @if (session('error'))
        <div class="bg-gradient-to-r from-red-400 to-red-500 text-white p-4 rounded-lg shadow-md mb-6 animate-pulse">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if (!$user->hasVerifiedEmail() && $user->provider !== 'google')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">
                        {{ __('profile.verification_required') }}
                    </h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>
                            {{ __('profile.verification_instructions') }} {{ $email }}.
                        </p>
                        <button wire:click="resendVerificationEmail" 
                            wire:loading.attr="disabled"
                            class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <span wire:loading.remove wire:target="resendVerificationEmail">{{ __('profile.resend_link') }}</span>
                            <span wire:loading wire:target="resendVerificationEmail">{{ __('profile.sending') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit.prevent="updateProfile" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium mb-1 text-gray-700">{{ __('profile.name') }}</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" id="name" wire:model="name" 
                        class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-lg p-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                </div>
                @error('name') <p class="text-red-600 text-sm mt-1 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> {{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium mb-1 text-gray-700">{{ __('profile.email') }}</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="email" id="email" wire:model="email" 
                        class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-lg p-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
                </div>
                @error('email') <p class="text-red-600 text-sm mt-1 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> {{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="specialization" class="block text-sm font-medium mb-1 text-gray-700">{{ __('profile.specialization') }}</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <input type="text" id="specialization" wire:model="specialization" 
                    class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-lg p-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm">
            </div>
            @error('specialization') <p class="text-red-600 text-sm mt-1 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> {{ $message }}</p> @enderror
        </div>
        
        <div>
            <label for="description" class="block text-sm font-medium mb-1 text-gray-700">{{ __('profile.description') }}</label>
            <p class="text-xs text-gray-500 mb-1">{{ __('profile.description_limit') }}</p>
            <textarea id="description" wire:model="description" rows="3"
                class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"></textarea>
            @error('description') <p class="text-red-600 text-sm mt-1 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> {{ $message }}</p> @enderror
        </div>
        
        <div>
            <label for="bio" class="block text-sm font-medium mb-1 text-gray-700">{{ __('profile.biography') }}</label>
            <p class="text-xs text-gray-500 mb-1">{{ __('profile.biography_limit') }}</p>
            <textarea id="bio" wire:model="bio" rows="6"
                class="w-full bg-gray-50 text-gray-800 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"></textarea>
            @error('bio') <p class="text-red-600 text-sm mt-1 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> {{ $message }}</p> @enderror
        </div>

        {{-- Image upload section --}}
        <div class="mt-6">
            <label class="block text-sm font-medium mb-2 text-gray-700">{{ __('profile.profile_image') }}</label>
            <div class="flex items-start space-x-4">
                <div class="w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center border">
                    @if ($newImage)
                        <img src="{{ $newImage->temporaryUrl() }}" alt="{{ __('profile.image_preview') }}" class="w-full h-full object-cover">
                    @elseif ($image)
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ __('profile.profile_image') }}" class="w-full h-full object-cover">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    @endif
                </div>
                
                <div>
                    <label for="newImage" class="px-4 py-2 border border-blue-500 text-blue-500 rounded-md hover:bg-blue-50 cursor-pointer inline-block">
                        {{ __('profile.choose_new_image') }}
                    </label>
                    <input id="newImage" type="file" wire:model="newImage" class="hidden" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">{{ __('profile.file_formats') }}</p>
                    
                    @error('newImage')
                        <p class="text-red-600 text-sm mt-1 flex items-center"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <div class="flex items-center justify-between w-full">
                <div></div>
                <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-300 hover:scale-105 shadow-md flex items-center transform hover:-translate-y-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ __('profile.save_changes') }}</span>
                    <span wire:loading wire:target="updateProfile" class="ml-2">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </form>
</div> 