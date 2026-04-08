<?php if(isset($breadcrumbs)): ?>
<div class="bg-white rounded-lg shadow p-3 mb-4">
    <div class="flex items-center space-x-2 text-sm">
        <a href="<?php echo e(url('/')); ?>" class="text-blue-500 hover:underline">Home</a>
        <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <?php if($loop->last): ?>
                <span class="text-gray-600"><?php echo e($breadcrumb['name']); ?></span>
            <?php else: ?>
                <a href="<?php echo e($breadcrumb['url']); ?>" class="text-blue-500 hover:underline"><?php echo e($breadcrumb['name']); ?></a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php endif; ?><?php /**PATH C:\Test\bookshop\resources\views\components\breadcrumb.blade.php ENDPATH**/ ?>