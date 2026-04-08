

<?php $__env->startSection('title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order #<?php echo e($order->order_number ?? $order->id); ?></h1>
        <a href="<?php echo e(route('client.orders.index')); ?>" class="text-blue-500 hover:underline">← Back to Orders</a>
    </div>
    
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Order Status</h2>
                <div class="relative">
                    <div class="flex justify-between">
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Order Placed</p>
                            <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('M d, Y')); ?></p>
                        </div>
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 <?php echo e(in_array($order->status, ['processing', 'shipped', 'out_for_delivery', 'delivered']) ? 'bg-green-500' : 'bg-gray-300'); ?> rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-cog text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Processing</p>
                        </div>
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 <?php echo e(in_array($order->status, ['shipped', 'out_for_delivery', 'delivered']) ? 'bg-green-500' : 'bg-gray-300'); ?> rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Shipped</p>
                        </div>
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 <?php echo e($order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300'); ?> rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-home text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Delivered</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Order Items</h2>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex justify-between items-center py-3 border-b">
                    <div>
                        <p class="font-semibold"><?php echo e($item->book->title); ?></p>
                        <p class="text-sm text-gray-500">by <?php echo e($item->book->author); ?></p>
                        <p class="text-sm text-gray-500">Quantity: <?php echo e($item->quantity); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">N$<?php echo e(number_format($item->price, 2)); ?></p>
                        <p class="text-sm text-gray-500">Subtotal: N$<?php echo e(number_format($item->price * $item->quantity, 2)); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>N$<?php echo e(number_format($order->subtotal ?? $order->total_amount - ($order->delivery_fee ?? 0), 2)); ?></span>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span>Delivery Fee:</span>
                        <span>N$<?php echo e(number_format($order->delivery_fee ?? 0, 2)); ?></span>
                    </div>
                    <div class="flex justify-between mt-2 pt-2 border-t font-bold">
                        <span>Total:</span>
                        <span class="text-green-600">N$<?php echo e(number_format($order->total_amount, 2)); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Delivery Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Delivery Information</h2>
                <p class="text-sm text-gray-600 mb-1">Method:</p>
                <p class="font-semibold capitalize mb-3"><?php echo e(str_replace('_', ' ', $order->delivery_method)); ?></p>
                
                <p class="text-sm text-gray-600 mb-1">Address:</p>
                <p class="text-sm"><?php echo e($order->delivery_address); ?></p>
                <p class="text-sm"><?php echo e($order->city); ?>, <?php echo e($order->postal_code); ?></p>
                <p class="text-sm">Phone: <?php echo e($order->phone); ?></p>
                
                <?php if($order->tracking_number): ?>
                <div class="mt-3 pt-3 border-t">
                    <p class="text-sm text-gray-600 mb-1">Tracking Number:</p>
                    <p class="font-mono text-sm"><?php echo e($order->tracking_number); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if($order->estimated_delivery): ?>
                <div class="mt-3 pt-3 border-t">
                    <p class="text-sm text-gray-600 mb-1">Estimated Delivery:</p>
                    <p class="text-sm font-semibold text-green-600"><?php echo e(\Carbon\Carbon::parse($order->estimated_delivery)->format('F d, Y')); ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Cancellation Section -->
            <?php if($isEligibleForCancellation): ?>
            <div class="bg-white rounded-lg shadow p-6 border-2 border-red-200">
                <h2 class="text-xl font-bold mb-4 text-red-600">Cancel Order</h2>
                <p class="text-sm text-gray-600 mb-4">
                    You can cancel this order within 7 days of purchase. 
                    Refunds will be processed within 5-7 business days.
                </p>
                
                <button onclick="openCancelModal()" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Request Cancellation
                </button>
            </div>
            <?php elseif($order->status == 'cancellation_requested'): ?>
            <div class="bg-white rounded-lg shadow p-6 border-2 border-orange-200">
                <h2 class="text-xl font-bold mb-4 text-orange-600">Cancellation Requested</h2>
                <p class="text-sm text-gray-600">
                    Your cancellation request has been submitted. 
                    Refund of N$<?php echo e(number_format($order->refund_amount, 2)); ?> will be processed within 5-7 business days.
                </p>
                <?php if($order->cancellation_reason): ?>
                <p class="text-sm text-gray-500 mt-3">
                    <strong>Reason:</strong> <?php echo e($order->cancellation_reason); ?>

                </p>
                <?php endif; ?>
            </div>
            <?php elseif(in_array($order->status, ['cancelled'])): ?>
            <div class="bg-white rounded-lg shadow p-6 border-2 border-gray-200">
                <h2 class="text-xl font-bold mb-4 text-gray-600">Order Cancelled</h2>
                <p class="text-sm text-gray-600">
                    This order has been cancelled.
                    <?php if($order->refund_status == 'completed'): ?>
                    Refund of N$<?php echo e(number_format($order->refund_amount, 2)); ?> has been processed.
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-4">Request Cancellation</h3>
            <form action="<?php echo e(route('client.orders.cancel', $order)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Reason for cancellation</label>
                    <textarea name="cancellation_reason" rows="4" required 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                              placeholder="Please tell us why you want to cancel this order..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Close
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Submit Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\client\orders\show.blade.php ENDPATH**/ ?>