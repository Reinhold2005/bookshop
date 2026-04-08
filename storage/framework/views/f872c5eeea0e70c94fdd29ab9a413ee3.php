

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-blue-600"><?php echo e($totalBooks); ?></div>
            <div class="text-gray-600">Total Books</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-green-600"><?php echo e($totalUsers); ?></div>
            <div class="text-gray-600">Total Users</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 bg-orange-50">
            <div class="text-3xl font-bold text-orange-600"><?php echo e($pendingCancellations); ?></div>
            <div class="text-gray-600">Pending Cancellations</div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6 bg-purple-50">
            <div class="text-3xl font-bold text-purple-600"><?php echo e($pendingRefunds); ?></div>
            <div class="text-gray-600">Pending Refunds</div>
        </div>
    </div>
    
    <!-- Pending Cancellations Section -->
    <?php if($pendingCancellations > 0): ?>
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg shadow p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-red-700 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle"></i> Urgent: Cancellation Requests
                <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs"><?php echo e($pendingCancellations); ?> pending</span>
            </h2>
            <a href="<?php echo e(route('admin.orders.index', ['status' => 'cancellation_requested'])); ?>" class="text-red-600 hover:underline text-sm">
                View All →
            </a>
        </div>
        
        <div class="space-y-3">
            <?php $__currentLoopData = $recentCancellations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-lg p-4 border border-red-200">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="font-mono text-sm bg-red-100 text-red-800 px-2 py-1 rounded">#<?php echo e($order->order_number ?? $order->id); ?></span>
                            <span class="text-sm text-gray-500"><?php echo e($order->created_at->diffForHumans()); ?></span>
                        </div>
                        <p class="font-semibold"><?php echo e($order->user->name); ?></p>
                        <p class="text-sm text-gray-600"><?php echo e($order->user->email); ?></p>
                        <p class="text-sm text-gray-500 mt-2">
                            <strong>Reason:</strong> <?php echo e(Str::limit($order->cancellation_reason, 100)); ?>

                        </p>
                        <p class="text-sm font-semibold mt-1">Total: N$<?php echo e(number_format($order->total_amount, 2)); ?></p>
                    </div>
                    <div class="flex gap-2">
                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>" 
                           class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                            Review Request
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="flex gap-4">
        <a href="<?php echo e(route('admin.books.index')); ?>" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
            Manage Books
        </a>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
            Manage Orders
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>