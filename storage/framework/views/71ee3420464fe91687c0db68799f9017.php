<?php $__env->startSection('title', $title ?? 'Zdrowie & Fitness Blog'); ?>

<?php $__env->startSection('head'); ?>
    <meta name="description" content="Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body-class', 'bg-gray-100 text-gray-900 min-h-screen flex flex-col font-sans antialiased'); ?>

<?php $__env->startSection('body'); ?>
    <!-- Subscription Modal -->
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('subscription-modal', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2514280-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

    <!-- Header with navigation -->
    <header>
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('navigation', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2514280-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </header>

    <main class="flex-grow">
        <?php echo e($slot); ?>

    </main>

    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('footer', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-2514280-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </footer>
    
    <!-- Scroll-to-top button -->
    <?php echo $__env->make('partials.scroll-to-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Laravel\fitsphere\resources\views/layouts/blog.blade.php ENDPATH**/ ?>