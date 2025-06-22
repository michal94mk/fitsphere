
<div>
    <!--[if BLOCK]><![endif]--><?php if($show && count($messages) > 0): ?>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div 
                wire:key="flash-message-<?php echo e($index); ?>"
                x-data="{ show: true }" 
                x-show="show"
                x-transition:enter="transform ease-out duration-300 transition"
                x-transition:enter-start="translate-y-[-100%] opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="w-full <?php if($message['type'] === 'success'): ?> bg-green-100 border-green-500 text-green-700 <?php elseif($message['type'] === 'error'): ?> bg-red-100 border-red-500 text-red-700 <?php elseif($message['type'] === 'warning'): ?> bg-yellow-100 border-yellow-500 text-yellow-700 <?php else: ?> bg-blue-100 border-blue-500 text-blue-700 <?php endif; ?> border-b-2 shadow-md"
            >
                <div class="container mx-auto px-4 py-3 flex items-center justify-center relative">
                    <div class="flex items-center">
                        <!--[if BLOCK]><![endif]--><?php if($message['type'] === 'success'): ?>
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        <?php elseif($message['type'] === 'error'): ?>
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                        <?php elseif($message['type'] === 'warning'): ?>
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        <?php else: ?>
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <span class="text-sm font-medium"><?php echo e($message['message']); ?></span>
                    </div>
                </div>
                
                
                <div x-init="setTimeout(() => { $wire.removeMessage(<?php echo e($index); ?>) }, 3000)"></div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>

 <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/flash-messages.blade.php ENDPATH**/ ?>