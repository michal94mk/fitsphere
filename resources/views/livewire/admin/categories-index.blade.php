<x-slot name="header">
    {{ __('admin.manage_categories') }}
</x-slot>

<div>
    <div class="container mx-auto p-4">
        <!-- Header with buttons -->
        <div class="flex justify-between items-center mb-6">
            <div></div>
            <x-admin.add-button 
                :route="route('admin.categories.create')" 
                :label="__('admin.add')" />
        </div>







        <!-- Search and filters -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.search') }}</label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="{{ __('admin.category_search_placeholder') }}">
                </div>
                <div class="md:w-40">
                    <label for="sortField" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.sort_field') }}</label>
                    <select wire:model.live="sortField" id="sortField"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="created_at">{{ __('admin.created_at') }}</option>
                        <option value="name">{{ __('admin.category_name') }}</option>
                    </select>
                </div>
                <div class="md:w-40">
                    <label for="sortDirection" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.sort_direction') }}</label>
                    <select wire:model.live="sortDirection" id="sortDirection"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="desc">{{ __('admin.descending') }}</option>
                        <option value="asc">{{ __('admin.ascending') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Categories table -->
        <x-admin.data-table 
            :data="$categories" 
            :headers="[
                ['label' => __('admin.user_id')],
                ['label' => __('admin.category_name')],
                ['label' => __('admin.posts')],
                ['label' => __('admin.date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ]"
            :empty-message="__('admin.no_categories_found')"
            colspan="5">
            
            @foreach($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center ring-2 ring-indigo-200">
                                    <span class="text-sm font-semibold text-indigo-800">{{ $category->id }}</span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">{{ $category->getTranslatedName() }}</div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full {{ $category->posts_count > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $category->posts_count }} {{ __('admin.posts') }}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                        <div class="space-y-1">
                            <div><span class="font-medium">{{ __('admin.created') }}:</span> {{ $category->created_at ? $category->created_at->format('d.m.Y') : 'N/A' }}</div>
                            <div><span class="font-medium">{{ __('admin.updated') }}:</span> {{ $category->updated_at ? $category->updated_at->format('d.m.Y') : 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right">
                        <x-admin.action-buttons :actions="[
                            [
                                'type' => 'link',
                                'url' => route('admin.categories.edit', $category),
                                'navigate' => true,
                                'style' => 'primary',
                                'label' => __('admin.edit'),
                                'title' => __('admin.edit')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.categories.translations', $category->id),
                                'navigate' => true,
                                'style' => 'success',
                                'label' => __('admin.translations'),
                                'title' => __('admin.translations')
                            ],
                            [
                                'type' => 'button',
                                'action' => 'confirmCategoryDeletion(' . $category->id . ')',
                                'style' => 'danger',
                                'label' => __('admin.delete'),
                                'title' => __('admin.delete')
                            ]
                        ]" />
                    </td>
                </tr>
            @endforeach
        </x-admin.data-table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmingCategoryDeletion') }"
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
                                {{ __('admin.delete_category') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('admin.confirm_delete_category') }}
                                </p>
                                <p class="text-sm text-gray-500 mt-2">
                                    {{ __('admin.category_delete_warning') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deleteCategory" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('admin.delete') }}
                    </button>
                    <button type="button" wire:click="cancelDeletion" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        {{ __('admin.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>