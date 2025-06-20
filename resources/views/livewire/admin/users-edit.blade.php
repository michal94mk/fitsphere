<x-slot name="header">
    {{ __('admin.edit_user') }}
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold">{{ __('admin.edit_user') }}</h1>
        </div>
        


        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- User information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.user_info') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.first_name') }}</label>
                                <input type="text" id="name" wire:model="name" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.email') }}</label>
                                <input type="email" id="email" wire:model="email" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password change section or Social login info -->
                    @if($provider)
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.login_method') }}</h2>
                        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-blue-500" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-medium text-blue-900">{{ __('admin.social_login_user') }}</h3>
                                    <p class="text-blue-700">{{ __('admin.logged_in_via') }} <strong>{{ ucfirst($provider) }}</strong></p>
                                    <p class="text-sm text-blue-600">{{ __('admin.password_managed_externally') }}</p>
                                    @if($provider_id)
                                        <div class="mt-2 text-xs text-blue-500 bg-blue-50 px-2 py-1 rounded border">
                                            <span class="font-medium">Provider ID:</span> <span class="font-mono">{{ $provider_id }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.change_password') }}</h2>
                        <div class="mt-4">
                            <label class="inline-flex items-center mb-4">
                                <input type="checkbox" wire:model.live="changePassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ __('admin.change_password_checkbox') }}</span>
                            </label>
                            
                            @if($changePassword)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.new_password') }}</label>
                                    <input type="password" id="password" wire:model="password" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.confirm_new_password') }}</label>
                                    <input type="password" id="password_confirmation" wire:model="password_confirmation" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <!-- User-specific information -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.user_profile') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">{{ __('admin.roles') }}</label>
                                <div class="space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="roles" value="admin" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <span class="font-medium">{{ __('admin.admin_role') }}</span>
                                            <span class="text-gray-500"> - {{ __('admin.admin_role_desc') }}</span>
                                        </span>
                                    </label>
                                    
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="roles" value="user" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <span class="font-medium">{{ __('admin.user_role') }}</span>
                                            <span class="text-gray-500"> - {{ __('admin.user_role_desc') }}</span>
                                        </span>
                                    </label>
                                    
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" wire:model="roles" value="trainer" 
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">
                                            <span class="font-medium">{{ __('admin.trainer_role') }}</span>
                                            <span class="text-gray-500"> - {{ __('admin.trainer_role_desc') }}</span>
                                        </span>
                                    </label>
                                </div>
                                @error('roles') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile photo upload -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.profile_image') }}</h2>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Profile preview" class="h-24 w-24 rounded-lg object-cover">
                                    @elseif ($existing_photo)
                                        <img src="{{ $existing_photo }}" alt="{{ $name }}" class="h-24 w-24 rounded-lg object-cover">
                                    @else
                                        <div class="h-24 w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-5">
                                    <div class="flex items-center">
                                        <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span>{{ $existing_photo ? __('admin.change_image') : __('admin.upload_image') }}</span>
                                            <input id="photo" wire:model.live="photo" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ __('admin.image_requirements') }}</p>
                                    @error('photo') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                                    
                                    @if ($photo || $existing_photo)
                                        <button type="button" wire:click="removePhoto" class="mt-2 text-sm text-red-600 hover:text-red-900">
                                            {{ __('admin.remove_image') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0">
                    <div class="w-full sm:w-auto">
                        <x-admin.form-button style="secondary" :href="route('admin.users.index')" navigate>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            {{ __('admin.back_to_list') }}
                        </x-admin.form-button>
                    </div>
                    <div class="w-full sm:w-auto">
                        <x-admin.form-button type="submit" style="primary" loading>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ __('admin.save_changes') }}
                        </x-admin.form-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>