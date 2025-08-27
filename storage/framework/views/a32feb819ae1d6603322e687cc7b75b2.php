<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800"><?php echo e(__('admin.user_details')); ?></h2>
            <p class="text-gray-600"><?php echo e(__('admin.view_user_information')); ?></p>
        </div>
        <div>
            <?php if (isset($component)) { $__componentOriginal9722d8890a2151e41969192a057a6c04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9722d8890a2151e41969192a057a6c04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['style' => 'secondary','href' => route('admin.users.index'),'navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['style' => 'secondary','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.users.index')),'navigate' => true]); ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
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
    </div>
    
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <div class="flex items-center">
                <div class="h-20 w-20 rounded-full bg-gray-300 flex items-center justify-center mr-4">
                    <svg class="h-10 w-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900"><?php echo e($user->name); ?></h3>
                    <div class="flex gap-1 flex-wrap mt-1">
                        <?php
                            $roles = explode(',', $user->role);
                        ?>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo e(trim($role) === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                   (trim($role) === 'trainer' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')); ?>">
                                <?php echo e(ucfirst(trim($role))); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('admin.email')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($user->email); ?></dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('admin.role')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex gap-1 flex-wrap">
                            <?php
                                $roles = explode(',', $user->role);
                            ?>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    <?php echo e(trim($role) === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                       (trim($role) === 'trainer' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')); ?>">
                                    <?php echo e(ucfirst(trim($role))); ?>

                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('admin.user_account_status')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <!--[if BLOCK]><![endif]--><?php if($user->email_verified_at): ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <?php echo e(__('admin.verified')); ?>

                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <?php echo e(__('admin.unverified')); ?>

                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('admin.registration_date')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($user->created_at->format('d.m.Y H:i')); ?>

                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('admin.last_login')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($user->last_login_at ? $user->last_login_at->format('d.m.Y H:i') : __('admin.never')); ?>

                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('admin.user_statistics')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            <div><?php echo e(__('admin.posts')); ?>: <span class="font-semibold"><?php echo e($user->posts->count()); ?></span></div>
                            <div><?php echo e(__('admin.comments')); ?>: <span class="font-semibold"><?php echo e($user->comments->count()); ?></span></div>
                        </div>
                    </dd>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500"><?php echo e(__('admin.last_updated')); ?></dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($user->updated_at->format('d.m.Y H:i')); ?>

                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
<?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/users-show.blade.php ENDPATH**/ ?>