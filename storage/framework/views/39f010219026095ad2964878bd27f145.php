<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['actions' => []]));

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

foreach (array_filter((['actions' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$buttonStyles = [
    'primary' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-blue-100 text-blue-700 hover:bg-blue-200',
    'success' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-green-100 text-green-700 hover:bg-green-200',
    'warning' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-orange-100 text-orange-700 hover:bg-orange-200',
    'danger' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-red-100 text-red-700 hover:bg-red-200',
    'info' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-indigo-100 text-indigo-700 hover:bg-indigo-200',
    'secondary' => 'px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-gray-100 text-gray-700 hover:bg-gray-200'
];
?>

<div class="flex flex-wrap items-center justify-end gap-1">
    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!--[if BLOCK]><![endif]--><?php if($action && $action['type'] === 'link'): ?>
            <a href="<?php echo e($action['url']); ?>" 
               <?php if(isset($action['navigate']) && $action['navigate']): ?> wire:navigate <?php endif; ?>
               class="<?php echo e($buttonStyles[$action['style']] ?? $buttonStyles['secondary']); ?>"
               <?php if(isset($action['title'])): ?> title="<?php echo e($action['title']); ?>" <?php endif; ?>>
                <?php echo e($action['label']); ?>

            </a>
        <?php elseif($action && $action['type'] === 'button'): ?>
            <button wire:click="<?php echo e($action['action']); ?>" 
                    <?php if(isset($action['loading'])): ?> 
                        wire:loading.attr="disabled" 
                        wire:target="<?php echo e($action['action']); ?>"
                    <?php endif; ?>
                    class="<?php echo e($buttonStyles[$action['style']] ?? $buttonStyles['secondary']); ?> disabled:opacity-50"
                    <?php if(isset($action['title'])): ?> title="<?php echo e($action['title']); ?>" <?php endif; ?>>
                <!--[if BLOCK]><![endif]--><?php if(isset($action['loading'])): ?>
                    <span wire:loading.remove wire:target="<?php echo e($action['action']); ?>"><?php echo e($action['label']); ?></span>
                    <span wire:loading wire:target="<?php echo e($action['action']); ?>">
                        <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <?php echo e(isset($action['loading_label']) ? $action['loading_label'] : 'Loading...'); ?>

                    </span>
                <?php else: ?>
                    <?php echo e($action['label']); ?>

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/components/admin/action-buttons.blade.php ENDPATH**/ ?>