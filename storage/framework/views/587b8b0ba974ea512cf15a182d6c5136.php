 <?php $__env->slot('header', null, []); ?> 
    <?php echo e(__('admin.trainer_management')); ?>

 <?php $__env->endSlot(); ?>

<div>
    <div class="container mx-auto p-6">
        <!-- Header with Add Button -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900"><?php echo e(__('admin.trainer_management')); ?></h1>
            <?php if (isset($component)) { $__componentOriginalb71cc3ce235f29fc5e10ecb6e3fe4662 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb71cc3ce235f29fc5e10ecb6e3fe4662 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.add-button','data' => ['route' => route('admin.trainers.create'),'label' => __('admin.add')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.add-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['route' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.trainers.create')),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('admin.add'))]); ?>
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
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.search')); ?></label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="<?php echo e(__('admin.trainer_search_placeholder')); ?>">
                </div>
                <div class="md:w-40">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.trainer_status')); ?></label>
                    <select wire:model.live="status" id="status" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value=""><?php echo e(__('admin.all')); ?></option>
                        <option value="approved"><?php echo e(__('admin.trainer_approved')); ?></option>
                        <option value="pending"><?php echo e(__('admin.trainer_pending')); ?></option>
                    </select>
                </div>
                <div class="md:w-40">
                    <label for="sortField" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.sort_field')); ?></label>
                    <select wire:model.live="sortField" id="sortField" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="created_at"><?php echo e(__('admin.trainer_join_date')); ?></option>
                        <option value="name"><?php echo e(__('admin.trainer_name')); ?></option>
                        <option value="email"><?php echo e(__('admin.email')); ?></option>
                        <option value="specialization"><?php echo e(__('admin.trainer_specialization')); ?></option>
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

        <!-- Trainers table -->
        <?php if (isset($component)) { $__componentOriginal8a75a2be9d4747e9fac92a4568c3c2d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a75a2be9d4747e9fac92a4568c3c2d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.data-table','data' => ['data' => $trainers,'headers' => [
                ['label' => __('admin.trainers')],
                ['label' => __('admin.trainer_info_title')],
                ['label' => __('admin.trainer_date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ],'emptyMessage' => __('admin.no_trainers_found'),'colspan' => '4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($trainers),'headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['label' => __('admin.trainers')],
                ['label' => __('admin.trainer_info_title')],
                ['label' => __('admin.trainer_date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ]),'empty-message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('admin.no_trainers_found')),'colspan' => '4']); ?>
            
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $trainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200" 
                                     src="<?php echo e($trainer->image ? asset('storage/' . $trainer->image) : 'https://ui-avatars.com/api/?name='.urlencode($trainer->name)); ?>" 
                                     alt="<?php echo e($trainer->name); ?>">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    <?php echo e($trainer->name); ?>

                                </div>
                                <div class="text-xs text-gray-500 truncate max-w-48">
                                    <?php echo e($trainer->email); ?>

                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="space-y-1">
                            <div class="text-xs text-gray-900"><span class="font-medium"><?php echo e(__('admin.trainer_specialization')); ?>:</span> <?php echo e($trainer->specialization); ?></div>
                            <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full 
                                <?php echo e($trainer->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                <?php echo e($trainer->is_approved ? __('admin.trainer_approved') : __('admin.trainer_pending')); ?>

                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                        <div class="space-y-1">
                            <div><span class="font-medium"><?php echo e(__('admin.created')); ?>:</span> <?php echo e($trainer->created_at->format('d.m.Y')); ?></div>
                            <div><span class="font-medium"><?php echo e(__('admin.updated')); ?>:</span> <?php echo e($trainer->updated_at->format('d.m.Y')); ?></div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right">
                        <?php if (isset($component)) { $__componentOriginala07abf6c4ac26573367cdce79eb1edd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala07abf6c4ac26573367cdce79eb1edd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.action-buttons','data' => ['actions' => array_filter(array_merge([
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.edit', $trainer->id),
                                'navigate' => true,
                                'style' => 'primary',
                                'label' => __('admin.edit'),
                                'title' => __('admin.edit')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.translations', $trainer->id),
                                'navigate' => true,
                                'style' => 'success',
                                'label' => __('admin.trainer_translations'),
                                'title' => __('admin.trainer_translations')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.show', $trainer->id),
                                'navigate' => true,
                                'style' => 'info',
                                'label' => __('admin.show'),
                                'title' => __('admin.show')
                            ]
                        ], [
                            !$trainer->is_approved ? [
                                'type' => 'button',
                                'action' => 'confirmTrainerApproval(' . $trainer->id . ')',
                                'style' => 'success',
                                'label' => __('admin.trainer_approve_button'),
                                'title' => __('admin.approve'),
                                'loading' => true,
                                'loading_label' => __('admin.approving') . '...'
                            ] : null,
                            [
                                'type' => 'button',
                                'action' => 'confirmTrainerDeletion(' . $trainer->id . ')',
                                'loading' => true,
                                'loading_label' => __('admin.deleting') . '...',
                                'style' => 'danger',
                                'label' => __('admin.delete'),
                                'title' => __('admin.delete')
                            ]
                        ]))]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.action-buttons'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['actions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(array_filter(array_merge([
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.edit', $trainer->id),
                                'navigate' => true,
                                'style' => 'primary',
                                'label' => __('admin.edit'),
                                'title' => __('admin.edit')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.translations', $trainer->id),
                                'navigate' => true,
                                'style' => 'success',
                                'label' => __('admin.trainer_translations'),
                                'title' => __('admin.trainer_translations')
                            ],
                            [
                                'type' => 'link',
                                'url' => route('admin.trainers.show', $trainer->id),
                                'navigate' => true,
                                'style' => 'info',
                                'label' => __('admin.show'),
                                'title' => __('admin.show')
                            ]
                        ], [
                            !$trainer->is_approved ? [
                                'type' => 'button',
                                'action' => 'confirmTrainerApproval(' . $trainer->id . ')',
                                'style' => 'success',
                                'label' => __('admin.trainer_approve_button'),
                                'title' => __('admin.approve'),
                                'loading' => true,
                                'loading_label' => __('admin.approving') . '...'
                            ] : null,
                            [
                                'type' => 'button',
                                'action' => 'confirmTrainerDeletion(' . $trainer->id . ')',
                                'loading' => true,
                                'loading_label' => __('admin.deleting') . '...',
                                'style' => 'danger',
                                'label' => __('admin.delete'),
                                'title' => __('admin.delete')
                            ]
                        ])))]); ?>
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
            <?php echo e($trainers->links()); ?>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: <?php if ((object) ('confirmingTrainerDeletion') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingTrainerDeletion'->value()); ?>')<?php echo e('confirmingTrainerDeletion'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingTrainerDeletion'); ?>')<?php endif; ?> }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                <?php echo e(__('admin.trainer_delete_confirmation')); ?>

                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    <?php echo e(__('admin.trainer_delete_confirmation_message')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deleteTrainer" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.delete')); ?>

                    </button>
                    <button type="button" wire:click="cancelDeletion" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.cancel')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Confirmation Modal -->
    <div x-data="{ show: <?php if ((object) ('confirmingTrainerApproval') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingTrainerApproval'->value()); ?>')<?php echo e('confirmingTrainerApproval'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingTrainerApproval'); ?>')<?php endif; ?> }"
         x-show="show"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-green-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                <?php echo e(__('admin.trainer_approve_confirmation')); ?>

                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    <?php echo e(__('admin.trainer_approve_confirmation_message')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="approveTrainer" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-green-600 border border-transparent rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.approve')); ?>

                    </button>
                    <button type="button" wire:click="cancelApproval" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.cancel')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/trainers-index.blade.php ENDPATH**/ ?>