<div class="max-w-md mx-auto py-8 bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center"><?php echo e(__('auth.reset_password')); ?></h2>

    <form wire:submit.prevent="resetPassword">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('auth.email')); ?></label>
            <input 
                type="email" 
                wire:model="email" 
                class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800"
                placeholder="<?php echo e(__('auth.email_placeholder')); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                <span class="text-red-500 text-sm"><?php echo e($message); ?></span> 
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('auth.new_password')); ?></label>
            <input 
                type="password" 
                wire:model="password" 
                class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800"
                placeholder="<?php echo e(__('auth.password_placeholder')); ?>">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                <span class="text-red-500 text-sm"><?php echo e($message); ?></span> 
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('auth.confirm_new_password')); ?></label>
            <input 
                type="password" 
                wire:model="password_confirmation" 
                class="w-full border border-gray-800 rounded-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-gray-800"
                placeholder="<?php echo e(__('auth.confirm_password_placeholder')); ?>">
        </div>

        <button 
            type="submit" 
            class="w-full bg-green-500 hover:bg-orange-600 transition duration-500 text-white font-semibold py-2 px-4 rounded-lg shadow">
            <?php echo e(__('auth.reset_password_button')); ?>

        </button>
    </form>
    
    <div class="mt-6 text-center">
        <a href="<?php echo e(route('login')); ?>" wire:navigate wire:prefetch class="text-blue-600 hover:text-blue-800 font-medium">
            <?php echo e(__('auth.back_to_login')); ?>

        </a>
    </div>
</div><?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/auth/reset-password.blade.php ENDPATH**/ ?>