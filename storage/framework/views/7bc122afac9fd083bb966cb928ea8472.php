<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
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
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
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
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div>
    <h2 class="text-lg font-medium text-gray-900 border-b pb-2"><?php echo e(__('admin.' . $title)); ?></h2>
    <div class="mt-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <!--[if BLOCK]><![endif]--><?php if($newImage): ?>
                    <img src="<?php echo e($newImage->temporaryUrl()); ?>" alt="<?php echo e($altText); ?>" class="h-32 w-32 rounded-lg object-cover">
                <?php elseif($currentImage): ?>
                    <img src="<?php echo e(asset('storage/' . $currentImage)); ?>" alt="<?php echo e($altText); ?>" class="h-32 w-32 rounded-lg object-cover">
                <?php else: ?>
                    <div class="h-32 w-32 rounded-lg bg-gray-200 flex items-center justify-center">
                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div class="ml-5">
                <div class="flex items-center">
                    <label for="<?php echo e($inputName); ?>" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                        <span><?php echo e(__('admin.' . ($currentImage ? $changeText : $placeholder))); ?></span>
                        <input id="<?php echo e($inputName); ?>" wire:model.live="<?php echo e($wireModel); ?>" type="file" class="sr-only" accept="image/*">
                    </label>
                </div>
                <p class="text-xs text-gray-500"><?php echo e(__('admin.' . $requirements)); ?></p>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = [$wireModel];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm block mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($newImage || $currentImage): ?>
                    <button type="button" wire:click="<?php echo e($removeMethod); ?>" class="mt-2 text-sm text-red-600 hover:text-red-900">
                        <?php echo e(__('admin.remove_image')); ?>

                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Laravel\fitsphere\resources\views/components/admin/image-upload.blade.php ENDPATH**/ ?>