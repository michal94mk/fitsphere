<x-slot name="header">
    {{ __('admin.edit_trainer') }}
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Back button at the top -->
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">{{ __('admin.edit_trainer') }}</h1>
            <a href="{{ route('admin.trainers.index') }}" wire:navigate 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                {{ __('admin.back_to_list') }}
            </a>
        </div>
        


        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- User information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.user_info') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.user_name') }}</label>
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
                                <input type="checkbox" wire:model.live="changePassword" wire:click="toggleChangePassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                    
                    <!-- Trainer-specific information -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.trainer_profile') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.trainer_specialization') }}</label>
                                <input type="text" id="specialization" wire:model="specialization" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('specialization') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="experience" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.trainer_experience_years') }}</label>
                                <input type="number" id="experience" wire:model="experience" min="0" max="100" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('experience') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.trainer_description') }}</label>
                                <textarea id="description" rows="2" wire:model="description" 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="biography" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.trainer_biography') }}</label>
                                <textarea id="biography" rows="4" wire:model="biography" 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                @error('biography') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                                        <img src="{{ $photo->temporaryUrl() }}" alt="Profile preview" class="h-24 w-24 rounded-full object-cover">
                                    @elseif ($existing_photo)
                                        <img src="{{ $existing_photo }}" alt="{{ $name }}" class="h-24 w-24 rounded-full object-cover">
                                    @else
                                        <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center">
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
                    
                    <!-- Approval status -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.trainer_status') }}</h2>
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="is_approved" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">{{ __('admin.approved_trainer') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        {{ __('admin.save_changes') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 