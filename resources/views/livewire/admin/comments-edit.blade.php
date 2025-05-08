<x-slot name="header">
    {{ __('admin.edit_comment') }}
</x-slot>

<div>
    <div class="container mx-auto p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">{{ __('admin.edit_comment') }}</h1>
            <a href="{{ route('admin.comments.index') }}" wire:navigate
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                {{ __('admin.back_to_list') }}
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- Comment information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.comment_info') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.comment_author') }}</label>
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex-shrink-0 mr-3"></div>
                                    <div>
                                        <p class="text-gray-900 font-medium">{{ $userName }}</p>
                                        <p class="text-gray-500 text-sm">{{ $userEmail }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.post') }}</label>
                                <p class="text-gray-900">{{ $postTitle }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.comment_date') }}</label>
                                <p class="text-gray-900">{{ $createdAt }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Comment content section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.comment_content') }}</h2>
                        <div class="mt-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.comment_content') }}</label>
                            <textarea id="content" rows="6" wire:model="content" 
                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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