<x-slot name="header">
    {{ __('admin.add_category') }}
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <!-- Back button at the top -->
        <div class="mb-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold">{{ __('admin.add_category') }}</h1>
            <a href="{{ route('admin.categories.index') }}" wire:navigate 
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                {{ __('admin.back_to_list') }}
            </a>
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
                
                <div class="px-6 py-3 bg-gray-50 text-right">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        {{ __('admin.create') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 