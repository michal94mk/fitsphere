<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'button', 'style' => 'primary', 'size' => 'normal', 'href' => null, 'navigate' => false, 'loading' => false]));

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

foreach (array_filter((['type' => 'button', 'style' => 'primary', 'size' => 'normal', 'href' => null, 'navigate' => false, 'loading' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
$baseClasses = 'inline-flex items-center border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring transition ease-in-out duration-150';

$sizeClasses = [
    'small' => 'px-3 py-1.5',
    'normal' => 'px-4 py-2',
    'large' => 'px-6 py-3'
];

$styleClasses = [
    'primary' => 'bg-blue-600 border-transparent text-white hover:bg-blue-700 active:bg-blue-900 focus:border-blue-900 focus:ring-blue-300 disabled:opacity-25',
    'secondary' => 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 active:bg-gray-100 focus:border-blue-300 focus:ring-blue-200 disabled:opacity-25',
    'success' => 'bg-green-600 border-transparent text-white hover:bg-green-700 active:bg-green-900 focus:border-green-900 focus:ring-green-300 disabled:opacity-25',
    'danger' => 'bg-red-600 border-transparent text-white hover:bg-red-700 active:bg-red-900 focus:border-red-900 focus:ring-red-300 disabled:opacity-25',
    'warning' => 'bg-yellow-600 border-transparent text-white hover:bg-yellow-700 active:bg-yellow-900 focus:border-yellow-900 focus:ring-yellow-300 disabled:opacity-25',
    'info' => 'bg-indigo-600 border-transparent text-white hover:bg-indigo-700 active:bg-indigo-900 focus:border-indigo-900 focus:ring-indigo-300 disabled:opacity-25'
];

$classes = $baseClasses . ' ' . ($sizeClasses[$size] ?? $sizeClasses['normal']) . ' ' . ($styleClasses[$style] ?? $styleClasses['primary']);
?>

<!--[if BLOCK]><![endif]--><?php if($href): ?>
    <a href="<?php echo e($href); ?>" 
       <?php if($navigate): ?> wire:navigate <?php endif; ?>
       <?php echo e($attributes->merge(['class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button type="<?php echo e($type); ?>" 
            <?php if($loading): ?> wire:loading.attr="disabled" <?php endif; ?>
            <?php echo e($attributes->merge(['class' => $classes])); ?>>
        <?php echo e($slot); ?>

    </button>
<?php endif; ?><!--[if ENDBLOCK]><![endif]--> <?php /**PATH C:\Laravel\fitsphere\resources\views/components/admin/form-button.blade.php ENDPATH**/ ?>