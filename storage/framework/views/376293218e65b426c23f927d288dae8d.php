<?php if($paginator->hasPages()): ?>
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center">
        <ul class="inline-flex items-center -space-x-px">
            
            <?php if($paginator->onFirstPage()): ?>
                <li>
                    <span class="px-3 py-1 rounded-l-md bg-gray-200 text-gray-500 cursor-not-allowed">
                        Précedent
                    </span>
                </li>
            <?php else: ?>
                <li>
                    <a href="<?php echo e($paginator->previousPageUrl()); ?>" rel="prev" class="px-3 py-1 rounded-l-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-100">
                        Précedent
                    </a>
                </li>
            <?php endif; ?>

            
            <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                
                <?php if(is_string($element)): ?>
                    <li>
                        <span class="px-3 py-1 bg-white border border-gray-300 text-gray-700"><?php echo e($element); ?></span>
                    </li>
                <?php endif; ?>

                
                <?php if(is_array($element)): ?>
                    <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $paginator->currentPage()): ?>
                            <li>
                                <span class="px-3 py-1 bg-primary-600 text-white border border-primary-600">
                                    <?php echo e($page); ?>

                                </span>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?php echo e($url); ?>" class="px-3 py-1 bg-white border border-gray-300 text-gray-700 hover:bg-gray-100">
                                    <?php echo e($page); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <?php if($paginator->hasMorePages()): ?>
                <li>
                    <a href="<?php echo e($paginator->nextPageUrl()); ?>" rel="next" class="px-3 py-1 rounded-r-md bg-white border border-gray-300 text-gray-700 hover:bg-gray-100">
                        Suivant
                    </a>
                </li>
            <?php else: ?>
                <li>
                    <span class="px-3 py-1 rounded-r-md bg-gray-200 text-gray-500 cursor-not-allowed">
                        Suivant
                    </span>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>
<?php /**PATH C:\wamp64\www\elearning-programming\resources\views/vendor/pagination/custom.blade.php ENDPATH**/ ?>