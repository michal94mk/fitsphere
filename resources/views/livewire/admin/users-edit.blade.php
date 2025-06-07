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
                    
                    <!-- Password change section (optional) -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.change_password') }}</h2>
                        <div class="mt-4">
                            <label class="inline-flex items-center mb-4">
                                <input type="checkbox" wire:model="changePassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ __('admin.change_password_checkbox') }}</span>
                            </label>
                            
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
                        </div>
                    </div>
                    
                    <!-- User-specific information -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.user_profile') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-6 mt-4">
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.role') }}</label>
                                <select id="role" wire:model="role" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="admin">{{ __('admin.admin') }}</option>
                                    <option value="user">{{ __('admin.user') }}</option>
                                </select>
                                @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                
                <div class="px-6 py-3 bg-gray-50 flex justify-between">
                    <x-admin.form-button style="secondary" :href="route('admin.users.index')" navigate>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('admin.back_to_list') }}
                    </x-admin.form-button>
                    <x-admin.form-button type="submit" style="primary" loading>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('admin.save_changes') }}
                    </x-admin.form-button>
                </div>
            </form>
        </div>
    </div>
</div>