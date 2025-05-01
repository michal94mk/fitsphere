<x-slot name="header">
    Articles Management
</x-slot>

<div>
    <div class="container mx-auto p-4">
        <!-- Header with title and buttons -->
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Posts List</h1>
            <a href="{{ route('admin.posts.create') }}" wire:navigate
               class="bg-blue-600 text-white px-3 py-2 rounded-md hover:bg-blue-700 transition flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-600 text-white rounded">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-3 bg-red-600 text-white rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Search and filters -->
        <div class="mb-4 bg-white p-3 rounded-lg shadow">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="Title, content, author or category...">
                </div>
                <div class="md:w-40">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.live="status" id="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">All</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="md:w-40">
                    <label for="sortField" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select wire:model.live="sortField" id="sortField"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="created_at">Creation Date</option>
                        <option value="title">Title</option>
                        <option value="status">Status</option>
                    </select>
                </div>
                <div class="md:w-40">
                    <label for="sortDirection" class="block text-sm font-medium text-gray-700 mb-1">Direction</label>
                    <select wire:model.live="sortDirection" id="sortDirection"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="desc">Descending</option>
                        <option value="asc">Ascending</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Posts table -->
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Post
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Info
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($posts as $post)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($post->image)
                                            <img class="h-8 w-8 rounded-full object-cover" 
                                                 src="{{ asset('storage/' . $post->image) }}" 
                                                 alt="{{ $post->title }}">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $post->title }}
                                        </div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">
                                            {{ Str::limit($post->slug, 30) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-xs text-gray-900">Author: {{ optional($post->user)->name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-900">Cat.: {{ optional($post->category)->name ?? 'None' }}</div>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $post->status === 'published' ? 'Published' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                                <div>Created: {{ $post->created_at->format('d.m.Y') }}</div>
                                <div>Updated: {{ $post->updated_at->format('d.m.Y') }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex flex-wrap items-center justify-end gap-1">
                                    <!-- Edit button -->
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md hover:bg-blue-200 text-xs font-medium transition">
                                        Edit
                                    </a>
                                    
                                    <!-- Translations button -->
                                    <a href="{{ route('admin.posts.translations', $post->id) }}" class="bg-green-100 text-green-700 px-2 py-1 rounded-md hover:bg-green-200 text-xs font-medium transition">
                                        Translations
                                    </a>
                                    
                                    <!-- Delete button -->
                                    <button wire:click="confirmPostDeletion({{ $post->id }})" class="bg-red-100 text-red-700 px-2 py-1 rounded-md hover:bg-red-200 text-xs font-medium transition">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                                No posts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmingPostDeletion') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Delete Post
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this post? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deletePost" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" wire:click="cancelDeletion" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 