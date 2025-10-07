<div>
    <div class="container mx-auto p-2">
        <!-- Header -->
        <div class="flex justify-between items-center mb-3">
            <div class="text-sm font-normal bg-blue-100 text-blue-800 rounded-full px-3 py-1">
                {{ __('admin.newsletter_subscribers') }}
            </div>
            <div class="text-sm text-gray-600">
                {{ __('admin.total_subscribers') }}: {{ $subscribers->total() }}
            </div>
        </div>

        <!-- Search and filters -->
        <div class="mb-3 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.search') }}</label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="{{ __('admin.search_email_placeholder') }}">
                </div>
                <div class="md:w-40">
                    <label for="sortField" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.sort_field') }}</label>
                    <select wire:model.live="sortField" id="sortField"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="created_at">{{ __('admin.created_at') }}</option>
                        <option value="email">{{ __('admin.email') }}</option>
                        <option value="id">{{ __('admin.subscriber_id') }}</option>
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

        <!-- Subscribers table -->
        <x-admin.data-table 
            :data="$subscribers" 
            :headers="[
                ['label' => __('admin.email')],
                ['label' => __('admin.subscription_date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ]"
            :empty-message="__('admin.no_subscribers_found')"
            colspan="3">
            
            @foreach($subscribers as $subscriber)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 ring-2 ring-blue-200">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $subscriber->email }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <span class="font-medium">ID:</span> {{ $subscriber->id }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                        <div class="space-y-1">
                            <div><span class="font-medium">{{ __('admin.subscribed') }}:</span> {{ $subscriber->created_at ? $subscriber->created_at->format('d.m.Y H:i') : 'N/A' }}</div>
                            <div><span class="font-medium">{{ __('admin.updated') }}:</span> {{ $subscriber->updated_at ? $subscriber->updated_at->format('d.m.Y H:i') : 'N/A' }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right">
                        <x-admin.action-buttons :actions="[
                            [
                                'type' => 'button',
                                'action' => 'confirmSubscriberDeletion(' . $subscriber->id . ')',
                                'style' => 'danger',
                                'label' => __('admin.delete'),
                                'title' => __('admin.delete_subscriber')
                            ]
                        ]" />
                    </td>
                </tr>
            @endforeach
        </x-admin.data-table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $subscribers->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmingSubscriberDeletion') }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2" id="modal-title">
                        {{ __('admin.delete_subscriber_confirmation') }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ __('admin.delete_subscriber_confirmation_message') }}
                    </p>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deleteSubscriber" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
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
