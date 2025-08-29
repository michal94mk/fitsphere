<div>
    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
        <div class="max-w-xl">
            <!--[if BLOCK]><![endif]--><?php if(session('status')): ?>
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md font-medium">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <!--[if BLOCK]><![endif]--><?php if(session('error')): ?>
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md font-medium">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900"><?php echo e(__('profile.change_password')); ?></h2>
                    <p class="mt-1 text-sm text-gray-600">
                        <?php echo e(__('profile.password_security_notice')); ?>

                    </p>
                </header>

                <form wire:submit.prevent="updatePassword" class="mt-6 space-y-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700"><?php echo e(__('profile.current_password')); ?></label>
                        <input type="password" id="current_password" wire:model="current_password"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-600 text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700"><?php echo e(__('profile.new_password')); ?></label>
                        <input type="password" id="new_password" wire:model="new_password"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                        <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-red-600 text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700"><?php echo e(__('profile.confirm_new_password')); ?></label>
                        <input type="password" id="new_password_confirmation" wire:model="new_password_confirmation"
                            class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <?php echo e(__('profile.save')); ?>

                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/user/profile/update-password.blade.php ENDPATH**/ ?>