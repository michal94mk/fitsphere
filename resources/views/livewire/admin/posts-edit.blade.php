<x-slot name="header">
    {{ __('admin.edit_post') }}
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold">{{ __('admin.edit_post') }}</h1>
        </div>

        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if (!$dataLoaded)
            <div class="text-center py-8">
                <div class="spinner-border text-blue-500" role="status">
                    <span class="sr-only">{{ __('admin.loading') }}</span>
                </div>
                <p class="mt-2 text-gray-600">{{ __('admin.loading') }}</p>
            </div>
        @else
            <!-- Formularz edycji posta -->
            <form wire:submit.prevent="update" enctype="multipart/form-data">
                <div class="space-y-4 bg-white p-6 rounded-lg shadow">
                    <!-- Tytuł -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">{{ __('admin.post_title') }}</label>
                        <input type="text" id="title" wire:model="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug z przyciskiem generowania -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">{{ __('admin.post_slug') }}</label>
                        <div class="flex space-x-2">
                            <input type="text" id="slug" wire:model.defer="slug" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <button type="button" onclick="document.getElementById('slug').value = document.getElementById('title').value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''); Livewire.dispatch('input', { target: document.getElementById('slug') })" class="mt-1 px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm transition">
                                {{ __('admin.generate') }}
                            </button>
                        </div>
                        @error('slug')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Streszczenie -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700">{{ __('admin.post_excerpt') }}</label>
                        <textarea id="excerpt" wire:model.defer="excerpt" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        <p class="mt-1 text-sm text-gray-500">{{ __('admin.excerpt_description') }}</p>
                        @error('excerpt')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Treść -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700">{{ __('admin.post_content') }}</label>
                        <textarea id="content" wire:model.defer="content" rows="10" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                        @error('content')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategoria -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">{{ __('admin.post_category') }}</label>
                        <select id="category_id" wire:model.defer="category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">{{ __('admin.select_category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">{{ __('admin.post_status') }}</label>
                        <select id="status" wire:model.defer="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="draft">{{ __('admin.status_draft') }}</option>
                            <option value="published">{{ __('admin.status_published') }}</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Post image upload -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.post_image') }}</h2>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    @if ($image)
                                        <img src="{{ $image->temporaryUrl() }}" alt="Post preview" class="h-32 w-32 rounded-lg object-cover">
                                    @elseif ($currentImage)
                                        <img src="{{ asset('storage/' . $currentImage) }}" alt="{{ $title }}" class="h-32 w-32 rounded-lg object-cover">
                                    @else
                                        <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-5">
                                    <div class="flex items-center">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span>{{ $currentImage ? __('admin.change_image') : __('admin.upload_image') }}</span>
                                            <input id="image" wire:model.live="image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">{{ __('admin.image_requirements') }}</p>
                                    @error('image') <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                                    
                                    @if ($image || $currentImage)
                                        <button type="button" wire:click="removeImage" class="mt-2 text-sm text-red-600 hover:text-red-900">
                                            {{ __('admin.remove_image') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Przyciski nawigacyjne -->
                    <div class="px-6 py-3 bg-gray-50 flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0">
                        <div class="w-full sm:w-auto">
                            <x-admin.form-button style="secondary" :href="route('admin.posts.index')" navigate>
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
                </div>
            </form>
        @endif
    </div>
</div> 