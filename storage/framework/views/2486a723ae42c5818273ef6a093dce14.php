 <?php $__env->slot('header', null, []); ?> 
    <?php echo e(__('admin.manage_post_translations')); ?>

 <?php $__env->endSlot(); ?>

<div class="space-y-6" x-data="{ showDeleteModal: false, translationToDelete: null }">
    <!-- Flash Messages -->
    <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <!-- Informacje o artykule -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4"><?php echo e(__('admin.post_information')); ?></h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 text-sm mb-1"><?php echo e(__('admin.original_title')); ?>:</p>
                <p class="font-medium"><?php echo e($post->title); ?></p>
            </div>
            
            <div>
                <p class="text-gray-500 text-sm mb-1"><?php echo e(__('admin.post_slug')); ?>:</p>
                <p class="font-medium"><?php echo e($post->slug); ?></p>
            </div>
            
            <div class="md:col-span-2">
                <p class="text-gray-500 text-sm mb-1"><?php echo e(__('admin.post_excerpt')); ?>:</p>
                <p><?php echo e($post->excerpt); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Lista istniejących tłumaczeń -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4"><?php echo e(__('admin.existing_translations')); ?></h2>
        
        <!--[if BLOCK]><![endif]--><?php if(count($translations) > 0): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(__('admin.language')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(__('admin.post_title')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(__('admin.modified_date')); ?>

                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <?php echo e(__('admin.actions')); ?>

                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $translations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $translation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xl"><?php echo e($translation['locale'] == 'pl' ? '🇵🇱' : '🇬🇧'); ?></span>
                                        <span><?php echo e($translation['locale'] == 'pl' ? __('admin.polish') : __('admin.english')); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo e($translation['title']); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e(date('d.m.Y H:i', strtotime($translation['updated_at']))); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <button wire:click="editTranslation(<?php echo e($translation['id']); ?>)" 
                                                class="px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-blue-100 text-blue-700 hover:bg-blue-200">
                                            <?php echo e(__('admin.edit')); ?>

                                        </button>
                                        <button @click="showDeleteModal = true; translationToDelete = <?php echo e($translation['id']); ?>"
                                                class="px-2 py-1 rounded-md text-xs font-medium transition duration-200 bg-red-100 text-red-700 hover:bg-red-200">
                                            <?php echo e(__('admin.delete')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="bg-yellow-50 p-4 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800"><?php echo e(__('admin.no_translations')); ?></h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p><?php echo e(__('admin.no_translations_message')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
    
    <!-- Formularz tłumaczenia -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <h2 class="text-xl font-semibold mb-4">
            <?php echo e($editingTranslationId ? __('admin.edit_translation') : __('admin.add_translation')); ?>

        </h2>
        
        <form wire:submit.prevent="saveTranslation" class="space-y-4">
            <?php if(session()->has('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?php echo e(session('error')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <!-- Wybór języka -->
            <div>
                <label for="locale" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.translation_language')); ?></label>
                <select id="locale" wire:model="locale" <?php if($editingTranslationId): ?> disabled <?php endif; ?> class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="pl"><?php echo e(__('admin.polish')); ?> 🇵🇱</option>
                    <option value="en"><?php echo e(__('admin.english')); ?> 🇬🇧</option>
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['locale'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Tytuł -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_title')); ?></label>
                <input type="text" id="title" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Fragment -->
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_excerpt')); ?> (<?php echo e(__('admin.optional')); ?>)</label>
                <textarea id="excerpt" wire:model="excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Treść -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_content')); ?></label>
                <textarea id="content" wire:model="content" rows="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            
            <!-- Przyciski -->
            <div class="flex justify-end space-x-3 pt-5">
                <?php if (isset($component)) { $__componentOriginal9722d8890a2151e41969192a057a6c04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9722d8890a2151e41969192a057a6c04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['type' => 'button','wire:click' => 'cancelEdit','style' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','wire:click' => 'cancelEdit','style' => 'secondary']); ?>
                    <?php echo e(__('admin.cancel')); ?>

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
                    <?php echo e($editingTranslationId ? __('admin.update_translation') : __('admin.add_translation')); ?>

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
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-cloak x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true" @click="showDeleteModal = false"></div>
            
            <!-- Centering trick -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal panel -->
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                <?php echo e(__('admin.confirm_delete')); ?>

                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    <?php echo e(__('admin.confirm_delete_translation')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-4 py-3 bg-gray-50 sm:px-6 flex justify-end space-x-3">
                    <?php if (isset($component)) { $__componentOriginal9722d8890a2151e41969192a057a6c04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9722d8890a2151e41969192a057a6c04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['type' => 'button','@click' => 'showDeleteModal = false','style' => 'secondary']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => 'showDeleteModal = false','style' => 'secondary']); ?>
                        <?php echo e(__('admin.cancel')); ?>

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
                    <?php if (isset($component)) { $__componentOriginal9722d8890a2151e41969192a057a6c04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9722d8890a2151e41969192a057a6c04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['type' => 'button','@click' => '$wire.deleteTranslation(translationToDelete); showDeleteModal = false','style' => 'danger']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','@click' => '$wire.deleteTranslation(translationToDelete); showDeleteModal = false','style' => 'danger']); ?>
                        <?php echo e(__('admin.delete')); ?>

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
        </div>
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/post-translations.blade.php ENDPATH**/ ?>