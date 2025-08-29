<div>
    <div class="container mx-auto p-2">
        <!-- Header with buttons -->
        <div class="flex justify-between items-center mb-3">
            <!--[if BLOCK]><![endif]--><?php if($role !== 'all'): ?>
                <div class="text-sm font-normal bg-blue-100 text-blue-800 rounded-full px-3 py-1"><?php echo e(ucfirst($role)); ?></div>
            <?php else: ?>
                <div></div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php if (isset($component)) { $__componentOriginalb71cc3ce235f29fc5e10ecb6e3fe4662 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb71cc3ce235f29fc5e10ecb6e3fe4662 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.add-button','data' => ['route' => route('admin.users.create'),'label' => __('admin.add')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.add-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.users.create')),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('admin.add'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb71cc3ce235f29fc5e10ecb6e3fe4662)): ?>
<?php $attributes = $__attributesOriginalb71cc3ce235f29fc5e10ecb6e3fe4662; ?>
<?php unset($__attributesOriginalb71cc3ce235f29fc5e10ecb6e3fe4662); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb71cc3ce235f29fc5e10ecb6e3fe4662)): ?>
<?php $component = $__componentOriginalb71cc3ce235f29fc5e10ecb6e3fe4662; ?>
<?php unset($__componentOriginalb71cc3ce235f29fc5e10ecb6e3fe4662); ?>
<?php endif; ?>
        </div>





        <!-- Search and filters -->
        <div class="mb-3 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.search')); ?></label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="<?php echo e(__('admin.search_placeholder')); ?>">
                </div>
                <div class="md:w-40">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.role')); ?></label>
                    <select wire:model.live="role" id="role"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="all"><?php echo e(__('admin.all')); ?></option>
                        <option value="user"><?php echo e(__('admin.user')); ?></option>
                        <option value="admin"><?php echo e(__('admin.admin')); ?></option>
                    </select>
                </div>
                <div class="md:w-40">
                    <label for="sortField" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.sort_field')); ?></label>
                    <select wire:model.live="sortField" id="sortField"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="created_at"><?php echo e(__('admin.created_at')); ?></option>
                        <option value="name"><?php echo e(__('admin.user_name')); ?></option>
                        <option value="email"><?php echo e(__('admin.email')); ?></option>
                        <option value="role"><?php echo e(__('admin.role')); ?></option>
                        <option value="id"><?php echo e(__('admin.user_id')); ?></option>
                    </select>
                </div>
                <div class="md:w-40">
                    <label for="sortDirection" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.sort_direction')); ?></label>
                    <select wire:model.live="sortDirection" id="sortDirection"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="desc"><?php echo e(__('admin.descending')); ?></option>
                        <option value="asc"><?php echo e(__('admin.ascending')); ?></option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Users table -->
        <?php if (isset($component)) { $__componentOriginal8a75a2be9d4747e9fac92a4568c3c2d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a75a2be9d4747e9fac92a4568c3c2d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.data-table','data' => ['data' => $users,'headers' => [
                ['label' => __('admin.user_name')],
                ['label' => __('admin.user_status') . ' / ' . __('admin.user_role')],
                ['label' => __('admin.comment_date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ],'emptyMessage' => __('admin.no_users_found'),'colspan' => '4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($users),'headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['label' => __('admin.user_name')],
                ['label' => __('admin.user_status') . ' / ' . __('admin.user_role')],
                ['label' => __('admin.comment_date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ]),'empty-message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('admin.no_users_found')),'colspan' => '4']); ?>
            
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <!--[if BLOCK]><![endif]--><?php if($user->profile_photo_url): ?>
                                    <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200" 
                                         src="<?php echo e($user->profile_photo_url); ?>" 
                                         alt="<?php echo e($user->name); ?>">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 ring-2 ring-gray-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    <?php echo e($user->name); ?>

                                </div>
                                <div class="text-xs text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <span class="font-medium">ID:</span> <?php echo e($user->id); ?>

                                    </div>
                                    <div class="truncate max-w-48"><?php echo e($user->email); ?></div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="space-y-1">
                            <!--[if BLOCK]><![endif]--><?php if($user->email_verified_at): ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    <?php echo e(__('admin.verified')); ?>

                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <?php echo e(__('admin.not_verified')); ?>

                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <div class="flex gap-1 flex-wrap">
                                <?php
                                    $roles = explode(',', $user->role);
                                ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                        <?php echo e(trim($role) === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                           (trim($role) === 'trainer' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')); ?>">
                                        <?php echo e(ucfirst(trim($role))); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php if($user->provider): ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-indigo-100 text-indigo-800 items-center">
                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                            <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        </svg>
                                        <?php echo e(ucfirst($user->provider)); ?>

                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                        <div class="space-y-1">
                            <div><span class="font-medium"><?php echo e(__('admin.created')); ?>:</span> <?php echo e($user->created_at ? $user->created_at->format('d.m.Y') : 'N/A'); ?></div>
                            <div><span class="font-medium"><?php echo e(__('admin.updated')); ?>:</span> <?php echo e($user->updated_at ? $user->updated_at->format('d.m.Y') : 'N/A'); ?></div>
                            <!--[if BLOCK]><![endif]--><?php if($user->email_verified_at): ?>
                                <div><span class="font-medium"><?php echo e(__('admin.verified_short')); ?>:</span> <?php echo e($user->email_verified_at->format('d.m.Y')); ?></div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right">
                        <?php if (isset($component)) { $__componentOriginala07abf6c4ac26573367cdce79eb1edd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala07abf6c4ac26573367cdce79eb1edd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.action-buttons','data' => ['actions' => [
                            [
                                'type' => 'link',
                                'url' => route('admin.users.edit', $user->id),
                                'navigate' => true,
                                'style' => 'primary',
                                'label' => __('admin.edit'),
                                'title' => __('admin.edit')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.users.show', $user->id),
                                'navigate' => true,
                                'style' => 'info',
                                'label' => __('admin.show'),
                                'title' => __('admin.show')
                            ],
                            [
                                'type' => 'button',
                                'action' => 'confirmUserDeletion(' . $user->id . ')',
                                'style' => 'danger',
                                'label' => __('admin.delete'),
                                'title' => __('admin.delete')
                            ]
                        ]]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.action-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                            [
                                'type' => 'link',
                                'url' => route('admin.users.edit', $user->id),
                                'navigate' => true,
                                'style' => 'primary',
                                'label' => __('admin.edit'),
                                'title' => __('admin.edit')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.users.show', $user->id),
                                'navigate' => true,
                                'style' => 'info',
                                'label' => __('admin.show'),
                                'title' => __('admin.show')
                            ],
                            [
                                'type' => 'button',
                                'action' => 'confirmUserDeletion(' . $user->id . ')',
                                'style' => 'danger',
                                'label' => __('admin.delete'),
                                'title' => __('admin.delete')
                            ]
                        ])]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala07abf6c4ac26573367cdce79eb1edd5)): ?>
<?php $attributes = $__attributesOriginala07abf6c4ac26573367cdce79eb1edd5; ?>
<?php unset($__attributesOriginala07abf6c4ac26573367cdce79eb1edd5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala07abf6c4ac26573367cdce79eb1edd5)): ?>
<?php $component = $__componentOriginala07abf6c4ac26573367cdce79eb1edd5; ?>
<?php unset($__componentOriginala07abf6c4ac26573367cdce79eb1edd5); ?>
<?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a75a2be9d4747e9fac92a4568c3c2d0)): ?>
<?php $attributes = $__attributesOriginal8a75a2be9d4747e9fac92a4568c3c2d0; ?>
<?php unset($__attributesOriginal8a75a2be9d4747e9fac92a4568c3c2d0); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a75a2be9d4747e9fac92a4568c3c2d0)): ?>
<?php $component = $__componentOriginal8a75a2be9d4747e9fac92a4568c3c2d0; ?>
<?php unset($__componentOriginal8a75a2be9d4747e9fac92a4568c3c2d0); ?>
<?php endif; ?>

        <!-- Pagination -->
        <div class="mt-6">
            <?php echo e($users->links()); ?>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: <?php if ((object) ('confirmingUserDeletion') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingUserDeletion'->value()); ?>')<?php echo e('confirmingUserDeletion'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingUserDeletion'); ?>')<?php endif; ?> }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        <div class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-auto"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2" id="modal-title">
                        <?php echo e(__('admin.delete_confirmation')); ?>

                    </h3>
                    <p class="text-sm text-gray-500">
                        <?php echo e(__('admin.delete_confirmation_message')); ?>

                    </p>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deleteUser" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.delete')); ?>

                    </button>
                    <button type="button" wire:click="cancelDeletion" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.cancel')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/users-index.blade.php ENDPATH**/ ?>