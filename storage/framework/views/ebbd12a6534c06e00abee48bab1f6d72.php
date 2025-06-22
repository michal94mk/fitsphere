 <?php $__env->slot('header', null, []); ?> 
    <?php echo e(__('admin.edit_trainer')); ?>

 <?php $__env->endSlot(); ?>

<div>
    <div class="container mx-auto p-6">

        <!-- Header -->
        <div class="mb-4">
            <h1 class="text-2xl font-bold"><?php echo e(__('admin.edit_trainer')); ?></h1>
        </div>
        
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <form wire:submit="save">
                <div class="p-6 space-y-6">
                    <!-- User information section -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2"><?php echo e(__('admin.user_info')); ?></h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.user_name')); ?></label>
                                <input type="text" id="name" wire:model="name" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.email')); ?></label>
                                <input type="email" id="email" wire:model="email" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password change section (optional) -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2"><?php echo e(__('admin.change_password')); ?></h2>
                        <div class="mt-4">
                            <label class="inline-flex items-center mb-4">
                                <input type="checkbox" wire:model.live="changePassword" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700"><?php echo e(__('admin.change_password_checkbox')); ?></span>
                            </label>
                            
                            <!--[if BLOCK]><![endif]--><?php if($changePassword): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.new_password')); ?></label>
                                    <input type="password" id="password" wire:model="password" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.confirm_new_password')); ?></label>
                                    <input type="password" id="password_confirmation" wire:model="password_confirmation" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                    
                    <!-- Trainer-specific information -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2"><?php echo e(__('admin.trainer_profile')); ?></h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label for="specialization" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.trainer_specialization')); ?></label>
                                <input type="text" id="specialization" wire:model="specialization" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['specialization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            
                            <div>
                                <label for="experience" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.trainer_experience_years')); ?></label>
                                <input type="number" id="experience" wire:model="experience" min="0" max="100" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['experience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.trainer_description')); ?></label>
                                <textarea id="description" rows="2" wire:model="description" 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>

                            <div class="md:col-span-2">
                                <label for="biography" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.trainer_biography')); ?></label>
                                <textarea id="biography" rows="4" wire:model="biography" 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['biography'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile photo upload -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2"><?php echo e(__('admin.profile_image')); ?></h2>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <!--[if BLOCK]><![endif]--><?php if($photo): ?>
                                        <img src="<?php echo e($photo->temporaryUrl()); ?>" alt="Profile preview" class="h-24 w-24 rounded-lg object-cover">
                                    <?php elseif($existing_photo): ?>
                                        <img src="<?php echo e($existing_photo); ?>" alt="<?php echo e($name); ?>" class="h-24 w-24 rounded-lg object-cover">
                                    <?php else: ?>
                                        <div class="h-24 w-24 rounded-lg bg-gray-200 flex items-center justify-center">
                                            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                                <div class="ml-5">
                                    <div class="flex items-center">
                                        <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                            <span><?php echo e($existing_photo ? __('admin.change_image') : __('admin.upload_image')); ?></span>
                                            <input id="photo" wire:model.live="photo" type="file" class="sr-only" accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500"><?php echo e(__('admin.image_requirements')); ?></p>
                                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm block mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                                    
                                    <!--[if BLOCK]><![endif]--><?php if($photo || $existing_photo): ?>
                                        <button type="button" wire:click="removePhoto" class="mt-2 text-sm text-red-600 hover:text-red-900">
                                            <?php echo e(__('admin.remove_image')); ?>

                                        </button>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Approval status -->
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 border-b pb-2"><?php echo e(__('admin.trainer_status')); ?></h2>
                        <div class="mt-4">
                            <label class="inline-flex items-center">
                                <input type="checkbox" wire:model="is_approved" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700"><?php echo e(__('admin.approved_trainer')); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-3 bg-gray-50 flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0">
                    <div class="w-full sm:w-auto">
                        <?php if (isset($component)) { $__componentOriginal9722d8890a2151e41969192a057a6c04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9722d8890a2151e41969192a057a6c04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['style' => 'secondary','href' => route('admin.trainers.index'),'navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['style' => 'secondary','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.trainers.index')),'navigate' => true]); ?>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <?php echo e(__('admin.back_to_list')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9722d8890a2151e41969192a057a6c04)): ?>
<?php $attributes = $__attributesOriginal9722d8890a2151e41969192a057a6c04; ?>
<?php unset($__attributesOriginal9722d8890a2151e41969192a057a6c04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9722d8890a2151e41969192a057a6c04)): ?>
<?php $component = $__componentOriginal9722d8890a2151e41969192a057a6c04; ?>
<?php unset($__componentOriginal9722d8890a2151e41969192a057a6c04); ?>
<?php endif; ?>
                    </div>
                    <div class="w-full sm:w-auto">
                        <?php if (isset($component)) { $__componentOriginal9722d8890a2151e41969192a057a6c04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9722d8890a2151e41969192a057a6c04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['type' => 'submit','style' => 'primary','loading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','style' => 'primary','loading' => true]); ?>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <?php echo e(__('admin.save_changes')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9722d8890a2151e41969192a057a6c04)): ?>
<?php $attributes = $__attributesOriginal9722d8890a2151e41969192a057a6c04; ?>
<?php unset($__attributesOriginal9722d8890a2151e41969192a057a6c04); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9722d8890a2151e41969192a057a6c04)): ?>
<?php $component = $__componentOriginal9722d8890a2151e41969192a057a6c04; ?>
<?php unset($__componentOriginal9722d8890a2151e41969192a057a6c04); ?>
<?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/trainers-edit.blade.php ENDPATH**/ ?>