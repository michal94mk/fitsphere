<x-slot name="header">
    {{ __('admin.add_category') }}
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold">{{ __('admin.add_category') }}</h1>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- Category information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.category_information') }}</h2>
                        <div class="grid grid-cols-1 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.category_name') }}</label>
                                <input type="text" id="name" wire:model="name" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0">
                    <div class="w-full sm:w-auto">
                        <x-admin.form-button style="secondary" :href="route('admin.categories.index')" navigate>
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