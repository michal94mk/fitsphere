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
        <!--[if BLOCK]><![endif]--><?php if($action['type'] === 'link'): ?>
            <a href="<?php echo e($action['url']); ?>" 
               <?php if(isset($action['navigate']) && $action['navigate']): ?> wire:navigate <?php endif; ?>
               class="<?php echo e($buttonStyles[$action['style']] ?? $buttonStyles['secondary']); ?>"
               <?php if(isset($action['title'])): ?> title="<?php echo e($action['title']); ?>" <?php endif; ?>>
                <?php echo e($action['label']); ?>

            </a>
        <?php elseif($action['type'] === 'button'): ?>
            <button wire:click="<?php echo e($action['action']); ?>" 
                    <?php if(isset($action['loading'])): ?> wire:loading.attr="disabled" <?php endif; ?>
                    class="<?php echo e($buttonStyles[$action['style']] ?? $buttonStyles['secondary']); ?>"
                    <?php if(isset($action['title'])): ?> title="<?php echo e($action['title']); ?>" <?php endif; ?>>
                <?php echo e($action['label']); ?>

            </button>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/components/admin/action-buttons.blade.php ENDPATH**/ ?>