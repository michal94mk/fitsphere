<div class="max-w-md mx-auto py-8 bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">{{ __('auth.reset_password') }}</h2>

    <form wire:submit.prevent="resetPassword">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.email') }}</label>
            <input 
                type="email" 
                wire:model="email" 
                class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800"
                placeholder="{{ __('auth.email_placeholder') }}">
            @error('email') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.new_password') }}</label>
            <input 
                type="password" 
                wire:model="password" 
                class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800"
                placeholder="{{ __('auth.password_placeholder') }}">
            @error('password') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.confirm_new_password') }}</label>
            <input 
                type="password" 
                wire:model="password_confirmation" 
                class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800"
                placeholder="{{ __('auth.confirm_password_placeholder') }}">
        </div>

        <button 
            type="submit" 
            class="w-full bg-green-500 hover:bg-orange-600 transition duration-500 text-white font-semibold py-2 px-4 rounded-lg shadow">
            {{ __('auth.reset_password_button') }}
        </button>
    </form>
    
    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" wire:navigate wire:prefetch class="text-blue-600 hover:text-blue-800 font-medium">
            {{ __('auth.back_to_login') }}
        </a>
    </div>
</div>