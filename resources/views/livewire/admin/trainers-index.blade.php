<x-slot name="header">
    {{ __('admin.manage_trainers') }}
</x-slot>

<div>
    <div class="container mx-auto p-4">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Header with title and buttons -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ __('admin.trainer_list') }}</h1>
            <x-admin.add-button 
                :route="route('admin.trainers.create')" 
                :label="__('admin.add')" />
        </div>

        <!-- Search and filters -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.search') }}</label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="{{ __('admin.trainer_search_placeholder') }}">
                </div>
                <div class="md:w-40">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.trainer_status') }}</label>
                    <select wire:model.live="status" id="status" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="">{{ __('admin.all') }}</option>
                        <option value="approved">{{ __('admin.trainer_approved') }}</option>
                        <option value="pending">{{ __('admin.trainer_pending') }}</option>
                    </select>
                </div>
                <div class="md:w-40">
                    <label for="sortField" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.sort_field') }}</label>
                    <select wire:model.live="sortField" id="sortField" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="created_at">{{ __('admin.trainer_join_date') }}</option>
                        <option value="name">{{ __('admin.trainer_name') }}</option>
                        <option value="email">{{ __('admin.email') }}</option>
                        <option value="specialization">{{ __('admin.trainer_specialization') }}</option>
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

        <!-- Trainers table -->
        <x-admin.data-table 
            :data="$trainers" 
            :headers="[
                ['label' => __('admin.trainers')],
                ['label' => __('admin.trainer_info_title')],
                ['label' => __('admin.trainer_date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ]"
            :empty-message="__('admin.no_trainers_found')"
            colspan="4">
            
            @foreach($trainers as $trainer)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200" 
                                     src="{{ $trainer->image ? asset('storage/' . $trainer->image) : 'https://ui-avatars.com/api/?name='.urlencode($trainer->name) }}" 
                                     alt="{{ $trainer->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $trainer->name }}
                                </div>
                                <div class="text-xs text-gray-500 truncate max-w-48">
                                    {{ $trainer->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="space-y-1">
                            <div class="text-xs text-gray-900"><span class="font-medium">{{ __('admin.trainer_specialization') }}:</span> {{ $trainer->specialization }}</div>
                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                {{ $trainer->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $trainer->is_approved ? __('admin.trainer_approved') : __('admin.trainer_pending') }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                        <div class="space-y-1">
                            <div><span class="font-medium">{{ __('admin.created') }}:</span> {{ $trainer->created_at->format('d.m.Y') }}</div>
                            <div><span class="font-medium">{{ __('admin.updated') }}:</span> {{ $trainer->updated_at->format('d.m.Y') }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right">
                        <x-admin.action-buttons :actions="array_merge([
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.edit', $trainer->id),
                                'navigate' => true,
                                'style' => 'primary',
                                'label' => __('admin.edit'),
                                'title' => __('admin.edit')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.translations', $trainer->id),
                                'navigate' => true,
                                'style' => 'success',
                                'label' => __('admin.trainer_translations'),
                                'title' => __('admin.trainer_translations')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.show', $trainer->id),
                                'navigate' => true,
                                'style' => 'info',
                                'label' => __('admin.show'),
                                'title' => __('admin.show')
                            ]
                        ], [
                            !$trainer->is_approved ? [
                                'type' => 'button',
                                'action' => 'approveTrainer(' . $trainer->id . ')',
                                'style' => 'success',
                                'label' => __('admin.trainer_approve_button'),
                                'title' => __('admin.approve'),
                                'loading' => true,
                                'loading_label' => __('admin.approving') . '...'
                            ] : [
                                'type' => 'button',
                                'action' => 'disapproveTrainer(' . $trainer->id . ')',
                                'style' => 'warning',
                                'label' => __('admin.trainer_disapprove_button'),
                                'title' => __('admin.trainer_disapprove_button'),
                                'loading' => true,
                                'loading_label' => __('admin.disapproving') . '...'
                            ],
                            [
                                'type' => 'button',
                                'action' => 'confirmTrainerDeletion(' . $trainer->id . ')',
                                'loading' => true,
                                'loading_label' => __('admin.deleting') . '...',
                                'style' => 'danger',
                                'label' => __('admin.delete'),
                                'title' => __('admin.delete')
                            ]
                        ])" />
                    </td>
                </tr>
            @endforeach
        </x-admin.data-table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $trainers->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: @entangle('confirmingTrainerDeletion') }"
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
                                {{ __('admin.trainer_delete_confirmation') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('admin.trainer_delete_confirmation_message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deleteTrainer" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
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