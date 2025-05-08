<x-slot name="header">
    {{ __('admin.manage_category_translations') }}
</x-slot>

<div class="space-y-6">
    <!-- Informacje o kategorii -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">{{ __('admin.category_information') }}</h2>
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <p class="text-gray-500 text-sm mb-1">{{ __('admin.original_name') }}:</p>
                <p class="font-medium">{{ $category->name }}</p>
            </div>
        </div>
        
        <div class="mt-4">
            <a href="{{ route('admin.categories.edit', $category->id) }}" wire:navigate class="text-blue-600 hover:text-blue-800">
                &larr; {{ __('admin.back_to_edit_category') }}
            </a>
        </div>
    </div>
    
    <!-- Lista istniejących tłumaczeń -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">{{ __('admin.existing_translations') }}</h2>
        
        @if (count($translations) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.language') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.category_name') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.modified_date') }}
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('admin.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($translations as $translation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl">{{ $translation['locale'] == 'pl' ? '🇵🇱' : '🇬🇧' }}</span>
                                        <span>{{ $translation['locale'] == 'pl' ? __('admin.polish') : __('admin.english') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $translation['name'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ date('d.m.Y H:i', strtotime($translation['updated_at'])) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button wire:click="editTranslation({{ $translation['id'] }})" class="text-indigo-600 hover:text-indigo-900">
                                        {{ __('admin.edit') }}
                                    </button>
                                    <button wire:click="deleteTranslation({{ $translation['id'] }})" 
                                            wire:confirm="{{ __('admin.confirm_delete_translation') }}"
                                            class="text-red-600 hover:text-red-900">
                                        {{ __('admin.delete') }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">{{ __('admin.no_translations') }}</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>{{ __('admin.no_category_translations_message') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Formularz tłumaczenia -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">
            {{ $editingTranslationId ? __('admin.edit_translation') : __('admin.add_translation') }}
        </h2>
        
        <form wire:submit.prevent="saveTranslation" class="space-y-4">
            @if (session()->has('error'))
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
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
            
            <!-- Wybór języka -->
            <div>
                <label for="locale" class="block text-sm font-medium text-gray-700">{{ __('admin.translation_language') }}</label>
                <select id="locale" wire:model="locale" @if($editingTranslationId) disabled @endif class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="pl">{{ __('admin.polish') }} 🇵🇱</option>
                    <option value="en">{{ __('admin.english') }} 🇬🇧</option>
                </select>
                @error('locale') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Nazwa -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('admin.category_name') }}</label>
                <input type="text" id="name" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Opis -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('admin.category_description') }} ({{ __('admin.optional') }})</label>
                <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <!-- Przyciski -->
            <div class="flex justify-end space-x-3 pt-5">
                <button type="button" wire:click="cancelEdit" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('admin.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $editingTranslationId ? __('admin.update_translation') : __('admin.add_translation') }}
                </button>
            </div>
        </form>
    </div>
</div> 