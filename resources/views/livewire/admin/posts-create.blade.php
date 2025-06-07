<div>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">{{ __('admin.add_post') }}</h1>

        @if (session()->has('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <form wire:submit.prevent="store" enctype="multipart/form-data">
            <div class="space-y-4 bg-white p-6 rounded-lg shadow">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">{{ __('admin.post_title') }}</label>
                    <input type="text" id="title" wire:model="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700">{{ __('admin.post_excerpt') }}</label>
                    <textarea id="excerpt" wire:model.defer="excerpt" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    <p class="mt-1 text-sm text-gray-500">{{ __('admin.excerpt_description') }}</p>
                    @error('excerpt')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">{{ __('admin.post_content') }}</label>
                    <textarea id="content" wire:model.defer="content" rows="10" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">{{ __('admin.post_category') }}</label>
                    <select id="category_id" wire:model.defer="category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">{{ __('admin.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">{{ __('admin.post_image') }}</label>
                    <input type="file" id="image" wire:model="image" class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md cursor-pointer focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <div wire:loading wire:target="image" class="mt-1 text-sm text-blue-500">
                        {{ __('admin.loading') }}
                    </div>
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="px-6 py-3 bg-gray-50 flex justify-between mt-6 -mx-6 -mb-6">
                    <x-admin.form-button style="secondary" :href="route('admin.posts.index')" navigate>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        {{ __('admin.back_to_list') }}
                    </x-admin.form-button>
                    <x-admin.form-button type="submit" style="success" loading>
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ __('admin.save') }}
                    </x-admin.form-button>
                </div>
            </div>
        </form>
    </div>
</div>