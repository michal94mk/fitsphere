<div>
    <div class="container mx-auto p-2">
        <!-- Header with buttons -->
        <div class="flex justify-between items-center mb-3">
            <div></div>
        </div>





        <!-- Search and filters -->
        <div class="mb-3 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.search')); ?></label>
                    <input wire:model.live.debounce.300ms="search" type="text" id="search"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           placeholder="<?php echo e(__('admin.comment_search_placeholder')); ?>">
                </div>
                <div class="md:w-40">
                    <label for="sortField" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('admin.sort_field')); ?></label>
                    <select wire:model.live="sortField" id="sortField"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <option value="created_at"><?php echo e(__('admin.creation_date')); ?></option>
                        <option value="updated_at"><?php echo e(__('admin.update_date')); ?></option>
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

        <!-- Comments table -->
        <?php if (isset($component)) { $__componentOriginal8a75a2be9d4747e9fac92a4568c3c2d0 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a75a2be9d4747e9fac92a4568c3c2d0 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.data-table','data' => ['data' => $comments,'headers' => [
                ['label' => __('admin.user')],
                ['label' => __('admin.comment')],
                ['label' => __('admin.date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ],'emptyMessage' => __('admin.no_comments_found'),'colspan' => '4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['data' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($comments),'headers' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
                ['label' => __('admin.user')],
                ['label' => __('admin.comment')],
                ['label' => __('admin.date')],
                ['label' => __('admin.actions'), 'align' => 'text-right']
            ]),'empty-message' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('admin.no_comments_found')),'colspan' => '4']); ?>
            
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover ring-2 ring-gray-200" 
                                     src="<?php echo e($comment->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name ?? 'User')); ?>" 
                                     alt="<?php echo e($comment->user->name ?? 'User'); ?>">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900">
                                    <?php echo e($comment->user->name ?? __('admin.unknown_user')); ?>

                                </div>
                                <div class="text-xs text-gray-500 truncate max-w-48">
                                    <span class="font-medium"><?php echo e(__('admin.post')); ?>:</span> <?php echo e(Str::limit($comment->post->title ?? __('admin.deleted_post'), 25)); ?>

                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-xs text-gray-900 max-w-md leading-relaxed">
                            <?php echo e(Str::limit($comment->content, 120)); ?>

                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-500">
                        <div class="space-y-1">
                            <div><span class="font-medium"><?php echo e(__('admin.created')); ?>:</span> <?php echo e($comment->created_at->format('d.m.Y')); ?></div>
                            <div><span class="font-medium"><?php echo e(__('admin.updated')); ?>:</span> <?php echo e($comment->updated_at->format('d.m.Y')); ?></div>
                        </div>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-right">
                        <?php if (isset($component)) { $__componentOriginala07abf6c4ac26573367cdce79eb1edd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala07abf6c4ac26573367cdce79eb1edd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.action-buttons','data' => ['actions' => [
                            [
                                'type' => 'button',
                                'action' => 'confirmCommentDeletion(' . $comment->id . ')',
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
                                'type' => 'button',
                                'action' => 'confirmCommentDeletion(' . $comment->id . ')',
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
            <?php echo e($comments->links()); ?>

        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ show: <?php if ((object) ('confirmingCommentDeletion') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingCommentDeletion'->value()); ?>')<?php echo e('confirmingCommentDeletion'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('confirmingCommentDeletion'); ?>')<?php endif; ?> }"
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
                        <?php echo e(__('admin.delete_comment')); ?>

                    </h3>
                    <p class="text-sm text-gray-500">
                        <?php echo e(__('admin.confirm_delete_comment')); ?>

                    </p>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="deleteComment" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.delete')); ?>

                    </button>
                    <button type="button" wire:click="cancelDeletion" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        <?php echo e(__('admin.cancel')); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/comments-index.blade.php ENDPATH**/ ?>