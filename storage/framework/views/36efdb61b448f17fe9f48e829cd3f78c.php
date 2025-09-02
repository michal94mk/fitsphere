<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['headers' => [], 'data' => [], 'emptyMessage' => 'No data found', 'colspan' => 4]));

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

foreach (array_filter((['headers' => [], 'data' => [], 'emptyMessage' => 'No data found', 'colspan' => 4]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="overflow-x-auto bg-white shadow-md rounded-lg" style="scrollbar-width: thin; scrollbar-color: #d1d5db #f3f4f6;">
    <style>
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
    <table class="w-full divide-y divide-gray-200" style="min-width: 800px;">
        <thead class="bg-gray-50">
            <tr>
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider <?php echo e($header['align'] ?? 'text-left'); ?>">
                        <?php echo e($header['label']); ?>

                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php echo e($slot); ?>

            
            <!--[if BLOCK]><![endif]--><?php if($data->isEmpty()): ?>
                <tr>
                    <td colspan="<?php echo e($colspan); ?>" class="px-4 py-8 whitespace-nowrap text-sm text-gray-500 text-center">
                        <?php echo e($emptyMessage); ?>

                    </td>
                </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/components/admin/data-table.blade.php ENDPATH**/ ?>