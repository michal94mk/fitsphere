
<div x-data="{ open: false }" 
     class="relative flex items-center">
    
    
    <button @click="open = !open" 
            type="button"
            class="flex items-center text-gray-300 px-1 py-1 rounded hover:bg-gray-700 transition-colors"
            :class="{ 'bg-gray-700': open }">
        
        <span class="sr-only"><?php echo e(__('common.switch_language')); ?></span>
        
        
        <div class="flex items-center">
            <!--[if BLOCK]><![endif]--><?php if($currentLocale === 'pl'): ?>
                <span class="flag-icon text-lg">🇵🇱</span>
            <?php else: ?>
                <span class="flag-icon text-lg">🇬🇧</span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        
        
        <svg xmlns="http://www.w3.org/2000/svg" 
             :class="{ 'transform rotate-180': open }"
             class="h-4 w-4 transition-transform duration-200" 
             viewBox="0 0 20 20" 
             fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    
    
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-100" 
         x-transition:enter-start="transform opacity-0 scale-95" 
         x-transition:enter-end="transform opacity-100 scale-100" 
         x-transition:leave="transition ease-in duration-75" 
         x-transition:leave-start="transform opacity-100 scale-100" 
         x-transition:leave-end="transform opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute z-50 top-full right-0 mt-1 bg-gray-800 rounded shadow-lg overflow-hidden border border-gray-700 min-w-[100px]">
        
        
        <a href="<?php echo e(request()->fullUrlWithQuery(['locale' => 'pl'])); ?>"
          wire:navigate
          class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 transition-colors <?php echo e($currentLocale === 'pl' ? 'bg-gray-700' : ''); ?>">
            <span class="flag-icon text-lg">🇵🇱</span>
            <span class="ml-2 text-xs">Polski</span>
        </a>
        
        
        <a href="<?php echo e(request()->fullUrlWithQuery(['locale' => 'en'])); ?>"
          wire:navigate
          class="flex items-center px-3 py-2 text-gray-300 hover:bg-gray-700 transition-colors <?php echo e($currentLocale === 'en' ? 'bg-gray-700' : ''); ?>">
            <span class="flag-icon text-lg">🇬🇧</span>
            <span class="ml-2 text-xs">English</span>
        </a>
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/language-switcher.blade.php ENDPATH**/ ?>