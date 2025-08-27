@props([
    'title' => 'Image',
    'currentImage' => null,
    'newImage' => null,
    'inputName' => 'image',
    'wireModel' => 'image',
    'removeMethod' => 'removeImage',
    'placeholder' => 'upload_image',
    'changeText' => 'change_image',
    'requirements' => 'image_requirements',
    'altText' => 'Image preview'
])

<div>
    <h2 class="text-lg font-medium text-gray-900 border-b pb-2">{{ __('admin.' . $title) }}</h2>
    <div class="mt-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                @if ($newImage)
                    <img src="{{ $newImage->temporaryUrl() }}" alt="{{ $altText }}" class="h-32 w-32 rounded-lg object-cover">
                @elseif ($currentImage)
                    <img src="{{ asset('storage/' . $currentImage) }}" alt="{{ $altText }}" class="h-32 w-32 rounded-lg object-cover">
                @else
                    <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="ml-5">
                <div class="flex items-center">
                    <label for="{{ $inputName }}" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                        <span>{{ __('admin.' . ($currentImage ? $changeText : $placeholder)) }}</span>
                        <input id="{{ $inputName }}" wire:model.live="{{ $wireModel }}" type="file" class="sr-only" accept="image/*">
                    </label>
                </div>
                <p class="text-xs text-gray-500">{{ __('admin.' . $requirements) }}</p>
                @error($wireModel) <span class="text-red-500 text-sm block mt-1">{{ $message }}</span> @enderror
                
                @if ($newImage || $currentImage)
                    <button type="button" wire:click="{{ $removeMethod }}" class="mt-2 text-sm text-red-600 hover:text-red-900">
                        {{ __('admin.remove_image') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
