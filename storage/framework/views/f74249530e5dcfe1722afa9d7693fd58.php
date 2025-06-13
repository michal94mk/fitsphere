<div>
    <!-- Quick Actions -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-3"><?php echo e(__('admin.quick_actions')); ?></h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="<?php echo e(route('admin.posts.create')); ?>" wire:navigate class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-indigo-50 transition">
                <div class="p-3 bg-indigo-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900"><?php echo e(__('admin.new_post')); ?></span>
            </a>
            <a href="<?php echo e(route('admin.categories.create')); ?>" wire:navigate class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-green-50 transition">
                <div class="p-3 bg-green-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900"><?php echo e(__('admin.new_category')); ?></span>
            </a>
            <a href="<?php echo e(route('admin.trainers.create')); ?>" wire:navigate class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-blue-50 transition">
                <div class="p-3 bg-blue-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900"><?php echo e(__('admin.new_trainer')); ?></span>
            </a>
            <a href="<?php echo e(route('admin.users.create')); ?>" wire:navigate class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow hover:bg-purple-50 transition">
                <div class="p-3 bg-purple-100 rounded-full mb-2">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900"><?php echo e(__('admin.new_user')); ?></span>
            </a>
        </div>
    </div>

    <!-- Statistics cards with improved design -->
    <div class="mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-3"><?php echo e(__('admin.statistics')); ?></h2>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="p-5 flex-grow">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0 p-3 mr-4 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500"><?php echo e(__('admin.users')); ?></div>
                            <div class="text-2xl font-semibold text-gray-900"><?php echo e($stats['users']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 px-5 py-2 mt-auto">
                    <a href="<?php echo e(route('admin.users.index')); ?>" wire:navigate class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center">
                        <?php echo e(__('admin.view_all_users')); ?>

                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="p-5 flex-grow">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0 p-3 mr-4 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500"><?php echo e(__('admin.trainers')); ?></div>
                            <div class="text-2xl font-semibold text-gray-900"><?php echo e($stats['trainers']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="bg-green-50 px-5 py-2 mt-auto">
                    <a href="<?php echo e(route('admin.trainers.index')); ?>" wire:navigate class="text-xs text-green-600 hover:text-green-800 font-medium flex items-center">
                        <?php echo e(__('admin.view_all_trainers')); ?>

                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="p-5 flex-grow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 p-3 mr-4 bg-purple-100 rounded-full">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500"><?php echo e(__('admin.posts')); ?></div>
                            <div class="text-2xl font-semibold text-gray-900"><?php echo e($stats['posts']); ?></div>
                            <div class="flex flex-wrap text-xs text-gray-500 mt-1 gap-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    <?php echo e($stats['publishedPosts']); ?> <?php echo e(__('admin.published')); ?>

                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                    <?php echo e($stats['draftPosts']); ?> <?php echo e(__('admin.drafts')); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 px-5 py-2 mt-auto">
                    <a href="<?php echo e(route('admin.posts.index')); ?>" wire:navigate class="text-xs text-purple-600 hover:text-purple-800 font-medium flex items-center">
                        <?php echo e(__('admin.view_all_posts')); ?>

                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div class="overflow-hidden bg-white rounded-lg shadow hover:shadow-md transition-shadow flex flex-col h-full">
                <div class="p-5 flex-grow">
                    <div class="flex items-center h-full">
                        <div class="flex-shrink-0 p-3 mr-4 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-500"><?php echo e(__('admin.comments')); ?></div>
                            <div class="text-2xl font-semibold text-gray-900"><?php echo e($stats['comments']); ?></div>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 px-5 py-2 mt-auto">
                    <a href="<?php echo e(route('admin.comments.index')); ?>" wire:navigate class="text-xs text-yellow-600 hover:text-yellow-800 font-medium flex items-center">
                        <?php echo e(__('admin.view_all_comments')); ?>

                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Two column layout -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Pending Approvals -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900"><?php echo e(__('admin.pending_trainers')); ?></h3>
                <!--[if BLOCK]><![endif]--><?php if($stats['pendingTrainers'] > 0): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <?php echo e($stats['pendingTrainers']); ?> <?php echo e(__('admin.pending')); ?>

                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <?php echo e(__('admin.no_pending')); ?>

                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div class="px-4 py-3">
                <!--[if BLOCK]><![endif]--><?php if(count($pendingTrainers) > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $pendingTrainers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500">
                                                <span class="text-lg font-medium leading-none text-white"><?php echo e(substr($trainer->name, 0, 1)); ?></span>
                                            </span>
                                        </div>
                                        <div class="ml-4 truncate">
                                            <div class="text-sm font-medium text-gray-900 truncate"><?php echo e($trainer->name); ?></div>
                                            <div class="text-sm text-gray-500 truncate"><?php echo e($trainer->email); ?></div>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 space-x-2">
                                        <button wire:click="approveTrainer(<?php echo e($trainer->id); ?>)" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <?php echo e(__('admin.approve')); ?>

                                        </button>
                                        <a href="<?php echo e(route('admin.trainers.edit', $trainer->id)); ?>" wire:navigate class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <?php echo e(__('admin.details')); ?>

                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                    <!--[if BLOCK]><![endif]--><?php if($stats['pendingTrainers'] > count($pendingTrainers)): ?>
                        <div class="mt-4 text-center">
                            <a href="<?php echo e(route('admin.trainers.index')); ?>?filter=pending" wire:navigate class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                <?php echo e(__('admin.view_all')); ?> (<?php echo e($stats['pendingTrainers']); ?>)
                            </a>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900"><?php echo e(__('admin.no_pending_trainers')); ?></h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php echo e(__('admin.all_requests_processed')); ?>

                        </p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Draft Posts -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900"><?php echo e(__('admin.drafts')); ?></h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    <?php echo e($stats['draftPosts']); ?> <?php echo e(__('admin.drafts')); ?>

                </span>
            </div>
            <div class="px-4 py-3">
                <!--[if BLOCK]><![endif]--><?php if(count($draftPosts) > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $draftPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?php echo e($post->getTranslatedTitle()); ?>

                                        </p>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span><?php echo e($post->user ? $post->user->name : 'System'); ?> · </span>
                                            <span><?php echo e($post->created_at->diffForHumans()); ?></span>
                                        </div>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 space-x-2">
                                        <a href="<?php echo e(route('admin.posts.edit', $post->id)); ?>" wire:navigate class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <?php echo e(__('admin.edit')); ?>

                                        </a>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                    <!--[if BLOCK]><![endif]--><?php if($stats['draftPosts'] > count($draftPosts)): ?>
                        <div class="mt-4 text-center">
                            <a href="<?php echo e(route('admin.posts.index')); ?>?status=draft" wire:navigate class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                <?php echo e(__('admin.view_all')); ?> (<?php echo e($stats['draftPosts']); ?>)
                            </a>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900"><?php echo e(__('admin.no_drafts')); ?></h3>
                        <p class="mt-1 text-sm text-gray-500">
                            <?php echo e(__('admin.all_posts_published')); ?>

                        </p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Popular Posts -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b">
                <h3 class="text-lg font-medium leading-6 text-gray-900"><?php echo e(__('admin.popular_posts')); ?></h3>
            </div>
            <div class="px-4 py-3">
                <!--[if BLOCK]><![endif]--><?php if(count($popularPosts) > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $popularPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="py-3">
                                <div class="flex items-center">
                                    <!--[if BLOCK]><![endif]--><?php if($post->image): ?>
                                        <div class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden bg-gray-100">
                                            <img src="<?php echo e(asset('storage/' . $post->image)); ?>" alt="<?php echo e($post->title); ?>" class="h-10 w-10 object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="flex-shrink-0 h-10 w-10 rounded-md overflow-hidden bg-indigo-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900"><?php echo e($post->getTranslatedTitle()); ?></p>
                                            <div class="ml-2 flex items-center text-sm text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                                                <svg class="flex-shrink-0 h-4 w-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <?php echo e($post->view_count); ?>

                                            </div>
                                        </div>
                                        <div class="flex items-center mt-1">
                                            <span class="text-xs text-gray-500"><?php echo e($post->created_at->format('d.m.Y')); ?></span>
                                            <span class="mx-1 text-gray-500">•</span>
                                            <span class="text-xs text-gray-500"><?php echo e($post->comments()->count()); ?> <?php echo e(__('admin.comments')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                <?php else: ?>
                    <div class="text-center py-4 text-gray-500"><?php echo e(__('admin.no_popular_posts')); ?></div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Recent Users -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="px-4 py-4 border-b">
                <h3 class="text-lg font-medium leading-6 text-gray-900"><?php echo e(__('admin.new_users')); ?></h3>
            </div>
            <div class="px-4 py-3">
                <!--[if BLOCK]><![endif]--><?php if(count($recentUsers) > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="py-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center overflow-hidden">
                                        <!--[if BLOCK]><![endif]--><?php if($user->profile_photo_path): ?>
                                            <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>" class="h-10 w-10 object-cover">
                                        <?php else: ?>
                                            <span class="text-lg font-medium text-gray-500"><?php echo e(substr($user->name, 0, 1)); ?></span>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900"><?php echo e($user->name); ?></p>
                                                <p class="text-sm text-gray-500"><?php echo e($user->email); ?></p>
                                            </div>
                                                                                            <a href="<?php echo e(route('admin.users.edit', $user->id)); ?>" wire:navigate class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200">
                                                <?php echo e(__('admin.edit')); ?>

                                            </a>
                                        </div>
                                        <div class="mt-1 flex items-center">
                                            <div class="flex gap-1 flex-wrap">
                                                <?php
                                                    $roles = explode(',', $user->role);
                                                ?>
                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                        <?php echo e(trim($role) === 'admin' ? 'bg-purple-100 text-purple-800' : 
                                                           (trim($role) === 'trainer' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')); ?>">
                                                        <?php echo e(ucfirst(trim($role))); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                            <span class="text-xs text-gray-500 ml-2"><?php echo e(__('admin.joined')); ?> <?php echo e($user->created_at->diffForHumans()); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                <?php else: ?>
                    <div class="text-center py-4 text-gray-500"><?php echo e(__('admin.no_new_users')); ?></div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>

    <!-- Activity Log -->
    <div class="mt-6">
        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('admin.components.activity-log', []);

$__html = app('livewire')->mount($__name, $__params, 'lw-398518011-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/dashboard.blade.php ENDPATH**/ ?>