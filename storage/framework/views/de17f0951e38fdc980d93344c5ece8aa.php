
<div>
    <!--[if BLOCK]><![endif]--><?php if($show): ?>
        <div 
            x-data="{ show: true }" 
            x-show="show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mb-6"
        >
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div 
                    wire:key="message-<?php echo e($index); ?>"
                    class="p-4 <?php echo e($message['type'] === 'success' ? 'bg-green-100 text-green-700' : ($message['type'] === 'error' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')); ?> rounded-lg mb-2 flex justify-between items-center shadow-sm"
                >
                    <div class="flex items-center">
                        <!--[if BLOCK]><![endif]--><?php if($message['type'] === 'success'): ?>
                            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        <?php elseif($message['type'] === 'error'): ?>
                            <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        <?php else: ?>
                            <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <span class="text-sm font-medium"><?php echo e($message['message']); ?></span>
                    </div>
                    <button 
                        wire:click="removeMessage(<?php echo e($index); ?>)"
                        type="button" 
                        class="inline-flex rounded-md p-1.5 <?php echo e($message['type'] === 'success' ? 'text-green-500 hover:bg-green-100' : ($message['type'] === 'error' ? 'text-red-500 hover:bg-red-100' : 'text-blue-500 hover:bg-blue-100')); ?>"
                    >
                        <span class="sr-only">Dismiss</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <!--[if BLOCK]><![endif]--><?php if(count($messages) > 1): ?>
                <div class="text-right">
                    <button 
                        wire:click="hideMessages"
                        class="text-sm text-gray-500 hover:text-gray-700"
                    >
                        <?php echo e(__('admin.hide_all_messages')); ?>

                    </button>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/flash-messages.blade.php ENDPATH**/ ?>