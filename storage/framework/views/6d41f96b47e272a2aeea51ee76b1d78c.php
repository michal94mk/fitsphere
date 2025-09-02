<div>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6"><?php echo e(__('admin.add_post')); ?></h1>

        <!--[if BLOCK]><![endif]--><?php if(session()->has('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

        <form wire:submit.prevent="store" enctype="multipart/form-data">
            <div class="space-y-4 bg-white p-6 rounded-lg shadow">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_title')); ?></label>
                    <input type="text" id="title" wire:model="title" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_slug')); ?></label>
                    <div class="flex space-x-2">
                        <input type="text" id="slug" wire:model.defer="slug" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <button type="button" onclick="document.getElementById('slug').value = document.getElementById('title').value.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, ''); Livewire.dispatch('input', { target: document.getElementById('slug') })" class="mt-1 px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded text-sm transition">
                            <?php echo e(__('admin.generate')); ?>

                        </button>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_excerpt')); ?></label>
                    <textarea id="excerpt" wire:model.defer="excerpt" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    <p class="mt-1 text-sm text-gray-500"><?php echo e(__('admin.excerpt_description')); ?></p>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['excerpt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_content')); ?></label>
                    <textarea id="content" wire:model.defer="content" rows="10" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_category')); ?></label>
                    <select id="category_id" wire:model.defer="category_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""><?php echo e(__('admin.select_category')); ?></option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700"><?php echo e(__('admin.post_status')); ?></label>
                    <select id="status" wire:model.defer="status" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="draft"><?php echo e(__('admin.status_draft')); ?></option>
                        <option value="published"><?php echo e(__('admin.status_published')); ?></option>
                    </select>
                    <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                                    <!-- Post image upload -->
                    <?php if (isset($component)) { $__componentOriginalbcb09694e99f778f583a494749959515 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbcb09694e99f778f583a494749959515 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.image-upload','data' => ['title' => 'post_image','currentImage' => null,'newImage' => $image,'inputName' => 'image','wireModel' => 'image','removeMethod' => 'removeImage','altText' => 'Post preview']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.image-upload'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'post_image','current-image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'new-image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($image),'input-name' => 'image','wire-model' => 'image','remove-method' => 'removeImage','alt-text' => 'Post preview']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbcb09694e99f778f583a494749959515)): ?>
<?php $attributes = $__attributesOriginalbcb09694e99f778f583a494749959515; ?>
<?php unset($__attributesOriginalbcb09694e99f778f583a494749959515); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbcb09694e99f778f583a494749959515)): ?>
<?php $component = $__componentOriginalbcb09694e99f778f583a494749959515; ?>
<?php unset($__componentOriginalbcb09694e99f778f583a494749959515); ?>
<?php endif; ?>

                <div class="px-6 py-3 bg-gray-50 flex flex-col sm:flex-row justify-between space-y-3 sm:space-y-0">
                    <div class="w-full sm:w-auto">
                        <?php if (isset($component)) { $__componentOriginal9722d8890a2151e41969192a057a6c04 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9722d8890a2151e41969192a057a6c04 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['style' => 'secondary','href' => route('admin.posts.index'),'navigate' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['style' => 'secondary','href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.posts.index')),'navigate' => true]); ?>
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.form-button','data' => ['type' => 'submit','style' => 'success','loading' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.form-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','style' => 'success','loading' => true]); ?>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <?php echo e(__('admin.create')); ?>

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
        </form>
    </div>
</div><?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/posts-create.blade.php ENDPATH**/ ?>