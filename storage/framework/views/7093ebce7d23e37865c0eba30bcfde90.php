

<?php $__env->startSection('title', 'Payment Cancelled'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 py-8 text-center">
    <div class="bg-white rounded-lg shadow p-8">
        <div class="text-red-500 text-6xl mb-4">✗</div>
        <h1 class="text-3xl font-bold mb-4">Payment Cancelled</h1>
        <p class="text-gray-600 mb-6">Your payment was cancelled. No charges were made.</p>
        <a href="<?php echo e(route('cart.index')); ?>" class="inline-block bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Return to Cart
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\checkout\cancel.blade.php ENDPATH**/ ?>