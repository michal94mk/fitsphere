<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?> - <?php echo $__env->yieldContent('title', 'Strona główna'); ?></title>
    <meta name="description" content="<?php echo e($description ?? 'Blog o zdrowiu i fitness. Trenuj, jedz zdrowo i dbaj o siebie.'); ?>">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🏋️</text></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    
    <!-- Custom styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="font-sans antialiased <?php echo $__env->yieldContent('body-class', 'bg-gray-100'); ?>">
    <?php echo $__env->yieldContent('body'); ?>
    
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    
    <!-- Asynchronous language change handling -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Store current language in localStorage
            localStorage.setItem('app_locale', '<?php echo e(app()->getLocale()); ?>');
            
            // Listen for language change events
            Livewire.on('language-changed', ({ locale }) => {
                // Update language in localStorage
                localStorage.setItem('app_locale', locale);
                
                // Update lang attribute in html tag
                document.documentElement.lang = locale.replace('_', '-');
                
                // Refresh all Livewire components on the page
                Livewire.all().forEach(component => {
                    if (component.$wire.$refresh) {
                        component.$wire.$refresh();
                    }
                });
            });
        });
    </script>
</body>
</html> <?php /**PATH C:\Laravel\fitsphere\resources\views/layouts/app.blade.php ENDPATH**/ ?>