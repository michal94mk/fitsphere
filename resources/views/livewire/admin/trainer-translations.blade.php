<div class="py-12" x-data="{ showDeleteModal: false, translationToDelete: null }">   
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">
                        {{ __('admin.trainer_translations') }}: {{ $trainer->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('admin.trainer_manage_translations') }}
                    </p>
                </div>
            </div>
            

            
            <!-- Original data -->
            <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('admin.default_language') }}:</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">{{ __('admin.trainer_specialization') }}:</p>
                        <p class="mt-1">{{ $trainer->specialization ?? __('admin.trainer_no_specialization') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">{{ __('admin.trainer_specializations') }}:</p>
                        <p class="mt-1">{{ $trainer->specialties ?? __('admin.trainer_no_specialization') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">{{ __('admin.trainer_description') }}:</p>
                        <p class="mt-1">{{ $trainer->description ?? __('admin.trainer_no_description') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-600">{{ __('admin.trainer_biography') }}:</p>
                        <p class="mt-1 whitespace-pre-line">{{ $trainer->bio ?? __('admin.trainer_no_bio') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Existing translations list -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-800 mb-4">{{ __('admin.translations') }}:</h3>
                
                @if (empty($translations))
                    <p class="text-gray-500 italic">{{ __('admin.no_translations') }}</p>
                @else
                    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('admin.target_language') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('admin.trainer_specialization') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('admin.trainer_description') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('admin.actions') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($translations as $translation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $translation['locale'] === 'en' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $translation['locale'] === 'en' ? 'English' : 'Polski' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate">{{ $translation['specialization'] ?: '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="max-w-xs truncate">{{ $translation['description'] ?: '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button wire:click="editTranslation({{ $translation['id'] }})" 
                                                        class="px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-blue-100 text-blue-700 hover:bg-blue-200">
                                                    {{ __('admin.edit') }}
                                                </button>
                                                <button @click="showDeleteModal = true; translationToDelete = {{ $translation['id'] }}"
                                                        class="px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-red-100 text-red-700 hover:bg-red-200">
                                                    {{ __('admin.delete') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            <!-- Translation form -->
            <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-200 p-6">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                    {{ $editingTranslationId ? __('admin.edit_translation') : __('admin.add_translation') }}
                </h3>
                
                <form wire:submit.prevent="saveTranslation" class="space-y-4">
                    <!-- Language -->
                    <div>
                        <label for="locale" class="block text-sm font-medium text-gray-700">{{ __('admin.target_language') }}</label>
                        <select id="locale" wire:model="locale" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ $editingTranslationId ? 'disabled' : '' }}>
                            <option value="en">English</option>
                            <option value="pl">Polski</option>
                        </select>
                        @error('locale') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Specialization -->
                    <div>
                        <label for="specialization" class="block text-sm font-medium text-gray-700">{{ __('admin.trainer_specialization') }}</label>
                        <input type="text" id="specialization" wire:model="specialization" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('specialization') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Short description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">{{ __('admin.trainer_description') }}</label>
                        <textarea id="description" wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Biography -->
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700">{{ __('admin.trainer_biography') }}</label>
                        <textarea id="bio" wire:model="bio" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Specialties -->
                    <div>
                        <label for="specialties" class="block text-sm font-medium text-gray-700">{{ __('admin.trainer_specializations') }}</label>
                        <input type="text" id="specialties" wire:model="specialties" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">{{ __('admin.tags_placeholder') }}</p>
                        @error('specialties') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <x-admin.form-button type="button" wire:click="cancelEdit" style="secondary">
                            {{ __('admin.cancel') }}
                        </x-admin.form-button>
                        
                        <x-admin.form-button type="submit" style="primary" loading>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $editingTranslationId ? __('admin.save_changes') : __('admin.add_translation') }}
                        </x-admin.form-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div x-cloak x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" @click="showDeleteModal = false"></div>
            
            <!-- Centering trick -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal panel -->
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                {{ __('admin.confirm_delete') }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    {{ __('admin.confirm_delete_trainer') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 sm:px-6 flex justify-end space-x-3">
                    <x-admin.form-button type="button" @click="showDeleteModal = false" style="secondary">
                        {{ __('admin.cancel') }}
                    </x-admin.form-button>
                    <x-admin.form-button type="button" @click="$wire.deleteTranslation(translationToDelete); showDeleteModal = false" style="danger">
                        {{ __('admin.delete') }}
                    </x-admin.form-button>
                </div>
            </div>
        </div>
    </div>
</div> 