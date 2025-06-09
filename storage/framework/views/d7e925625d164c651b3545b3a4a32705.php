<div class="bg-white overflow-hidden shadow rounded-lg">
    <div class="px-4 py-4 border-b">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Ostatnia aktywność</h3>
    </div>
    <div class="px-4 py-3">
        <!--[if BLOCK]><![endif]--><?php if($loading): ?>
            <div class="flex justify-center py-4">
                <svg class="animate-spin h-6 w-6 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        <?php elseif(count($activities) === 0): ?>
            <div class="text-center py-4 text-gray-500">
                Brak aktywności do wyświetlenia
            </div>
        <?php else: ?>
            <div class="flow-root">
                <ul class="-mb-8">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="relative pb-8">
                                <!--[if BLOCK]><![endif]--><?php if($index !== count($activities) - 1): ?>
                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center ring-8 ring-white">
                                            <!--[if BLOCK]><![endif]--><?php if($activity['type'] === 'post'): ?>
                                                <svg class="h-5 w-5 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                                </svg>
                                            <?php elseif($activity['type'] === 'comment'): ?>
                                                <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                            <?php elseif($activity['type'] === 'user'): ?>
                                                <svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            <?php else: ?>
                                                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php echo e($activity['user']); ?>

                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">
                                                <?php echo e(\Carbon\Carbon::parse($activity['time'])->diffForHumans()); ?>

                                            </p>
                                        </div>
                                        <div class="mt-2 text-sm text-gray-700">
                                            <!--[if BLOCK]><![endif]--><?php if($activity['type'] === 'post'): ?>
                                                <!--[if BLOCK]><![endif]--><?php if($activity['action'] === 'created'): ?>
                                                    <p>Utworzył post <strong><?php echo e($activity['name']); ?></strong></p>
                                                <?php else: ?>
                                                    <p>Zaktualizował post <strong><?php echo e($activity['name']); ?></strong></p>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            <?php elseif($activity['type'] === 'comment'): ?>
                                                <p>Dodał komentarz: <strong><?php echo e($activity['name']); ?></strong></p>
                                            <?php elseif($activity['type'] === 'user'): ?>
                                                <p>Użytkownik <strong><?php echo e($activity['name']); ?></strong> zarejestrował się</p>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div> <?php /**PATH C:\Laravel\fitsphere\resources\views/livewire/admin/components/activity-log.blade.php ENDPATH**/ ?>