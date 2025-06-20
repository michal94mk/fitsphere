<div>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('admin.add_user') }}</h1>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="store" enctype="multipart/form-data">
                <div class="p-6 space-y-6">
                    <!-- Dane podstawowe -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.user_info') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.first_name') }}</label>
                                <input type="text" id="name" wire:model="name" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.email') }}</label>
                                <input type="email" id="email" wire:model="email" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Hasło -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.password_section') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.password') }}</label>
                                <input type="password" id="password" wire:model="password" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.confirm_password') }}</label>
                                <input type="password" id="password_confirmation" wire:model="password_confirmation" 
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            </div>
                        </div>
                    </div>

                    <!-- Profil użytkownika -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.user_profile') }}</h2>
                        <div class="mt-4">
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

                    <!-- Zdjęcie profilowe -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.profile_image') }}</h2>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Profile preview" class="h-24 w-24 rounded-lg object-cover">
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
                                            <span>{{ __('admin.upload_image') }}</span>
                                            <input id="photo" wire:model.live="photo" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ __('admin.image_requirements') }}</p>
                                    @error('photo') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                                    
                                    @if ($photo)
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
                        <x-admin.form-button type="submit" style="success" loading>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('admin.create') }}
                        </x-admin.form-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>