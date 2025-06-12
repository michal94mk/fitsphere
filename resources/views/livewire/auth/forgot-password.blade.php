<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            <h2 class="text-xl font-semibold text-white">{{ __('auth.forgot_password_title') }}</h2>
        </div>

        <div class="p-6">
            <p class="text-gray-600 mb-6">{{ __('auth.forgot_password_description') }}</p>

            @if (session()->has('message'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="sendResetLink">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.email') }}</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            wire:model="email" 
                            placeholder="{{ __('auth.email_placeholder') }}"
                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-300 transition-all duration-200">
                    </div>
                    @error('email') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex flex-col space-y-4">
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-2 px-4 rounded-md hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span wire:loading.remove>{{ __('auth.send_reset_link') }}</span>
                        <span wire:loading>{{ __('common.loading') }}</span>
                    </button>
                </div>
            </form>
            
            <div class="mt-6 flex items-center justify-center">
                <span class="text-sm text-gray-600">
                    {{ __('auth.remember_password') }} 
                    <a href="{{ route('login') }}" 
                       wire:navigate 
                       wire:prefetch
                       class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        <span wire:loading.remove>{{ __('auth.sign_in') }}</span>
                        <span wire:loading>{{ __('common.loading') }}</span>
                    </a>
                </span>
            </div>
        </div>
    </div>
</div>