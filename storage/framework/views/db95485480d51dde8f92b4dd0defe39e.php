

<?php $__env->startSection('title', 'Order Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order #<?php echo e($order->order_number ?? $order->id); ?></h1>
        <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-blue-500 hover:underline">← Back to Orders</a>
    </div>
    
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <!-- Cancellation Request Alert -->
    <?php if($order->status == 'cancellation_requested'): ?>
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-3">
                    <i class="text-red-500 text-2xl"></i>
                    <h3 class="text-xl font-bold text-red-700">Cancellation Request Pending</h3>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Requested on:</p>
                        <p class="font-semibold"><?php echo e($order->cancellation_requested_at ? \Carbon\Carbon::parse($order->cancellation_requested_at)->setTimezone('Africa/Windhoek')->format('F d, Y h:i A') : 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Refund Amount:</p>
                        <p class="font-semibold text-red-600 text-lg">N$<?php echo e(number_format($order->refund_amount ?? $order->total_amount, 2)); ?></p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <p class="text-sm text-gray-700 mb-1 font-semibold">Reason provided by customer:</p>
                    <div class="bg-white p-3 rounded border border-red-200">
                        <p class="text-gray-700"><?php echo e($order->cancellation_reason ?: 'No reason provided'); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-2 ml-4">
                <form action="<?php echo e(route('admin.orders.approve-cancellation', $order)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition">
                        <i class="fas fa-check"></i> Approve Cancellation
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content - Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-blue-500"></i> Customer Information
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-500 text-sm">Name</p>
                        <p class="font-semibold"><?php echo e($order->user->name); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Email</p>
                        <p class="font-semibold"><?php echo e($order->user->email); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Phone</p>
                        <p class="font-semibold"><?php echo e($order->phone ?? 'Not provided'); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Order Date</p>
                        <p class="font-semibold"><?php echo e($order->created_at->format('F d, Y h:i A')); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Delivery Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-truck text-green-500"></i> Delivery Information
                </h2>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-gray-500 text-sm">Delivery Method</p>
                        <p class="font-semibold capitalize"><?php echo e($order->formatted_delivery_method ?? ucfirst($order->delivery_method)); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Delivery Fee</p>
                        <p class="font-semibold">N$<?php echo e(number_format($order->delivery_fee ?? 0, 2)); ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-bold">Estimated Delivery</p>
                        <p class="font-semibold text-green-600">
                            <?php if($order->estimated_delivery): ?>
                                <?php echo e(\Carbon\Carbon::parse($order->estimated_delivery)->format('F d, Y')); ?>

                            <?php else: ?>
                                Not set
                            <?php endif; ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Tracking Number</p>
                        <p class="font-semibold font-mono"><?php echo e($order->tracking_number ?? 'Not generated'); ?></p>
                    </div>
                </div>
                
                <div class="border-t pt-4">
                    <p class="text-gray-500 text-sm mb-1">Delivery Address</p>
                    <p class="font-semibold"><?php echo e($order->delivery_address ?? 'Not provided'); ?></p>
                    <p class="text-gray-600 mt-1">
                        <?php echo e($order->city ?? ''); ?><?php echo e($order->city && $order->postal_code ? ', ' : ''); ?><?php echo e($order->postal_code ?? ''); ?>

                    </p>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-shopping-cart text-purple-500"></i> Order Items
                </h2>
                
                <?php if($order->items && $order->items->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">Book</th>
                                <th class="px-4 py-3 text-center">Quantity</th>
                                <th class="px-4 py-3 text-right">Price</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b">
                                <td class="px-4 py-3">
                                    <strong><?php echo e($item->book->title); ?></strong><br>
                                    <span class="text-sm text-gray-500">by <?php echo e($item->book->author); ?></span>
                                 </td>
                                <td class="px-4 py-3 text-center"><?php echo e($item->quantity); ?></td>
                                <td class="px-4 py-3 text-right">N$<?php echo e(number_format($item->price, 2)); ?></td>
                                <td class="px-4 py-3 text-right">N$<?php echo e(number_format($item->price * $item->quantity, 2)); ?></td>
                             </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold">Subtotal:</td>
                                <td class="px-4 py-3 text-right">N$<?php echo e(number_format($order->subtotal ?? $order->total_amount - ($order->delivery_fee ?? 0), 2)); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold">Delivery Fee:</td>
                                <td class="px-4 py-3 text-right">N$<?php echo e(number_format($order->delivery_fee ?? 0, 2)); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-lg">Total:</td>
                                <td class="px-4 py-3 text-right font-bold text-lg text-green-600">N$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-gray-500 text-center py-4">No items found for this order.</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Right Column - Management Controls -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Order Status Management -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-tasks text-orange-500"></i> Order Status
                </h2>
                <form action="<?php echo e(route('admin.orders.status', $order)); ?>" method="POST" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <select name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="pending" <?php echo e($order->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="processing" <?php echo e($order->status == 'processing' ? 'selected' : ''); ?>>Processing</option>
                        <option value="shipped" <?php echo e($order->status == 'shipped' ? 'selected' : ''); ?>>Shipped</option>
                        <option value="out_for_delivery" <?php echo e($order->status == 'out_for_delivery' ? 'selected' : ''); ?>>Out for Delivery</option>
                        <option value="delivered" <?php echo e($order->status == 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                        <option value="cancellation_requested" <?php echo e($order->status == 'cancellation_requested' ? 'selected' : ''); ?>>Cancellation Requested</option>
                        <option value="cancelled" <?php echo e($order->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                        Update Status
                    </button>
                </form>
            </div>
            
            <!-- Payment Status Management -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-credit-card text-green-500"></i> Payment Status
                </h2>
                <form action="<?php echo e(route('admin.orders.payment', $order)); ?>" method="POST" class="space-y-3">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <select name="payment_status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="pending" <?php echo e($order->payment_status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="paid" <?php echo e($order->payment_status == 'paid' ? 'selected' : ''); ?>>Paid</option>
                        <option value="failed" <?php echo e($order->payment_status == 'failed' ? 'selected' : ''); ?>>Failed</option>
                        <option value="refunded" <?php echo e($order->payment_status == 'refunded' ? 'selected' : ''); ?>>Refunded</option>
                    </select>
                    <p class="text-sm text-gray-500">Method: <?php echo e($order->formatted_payment_method ?? ucfirst($order->payment_method)); ?></p>
                    <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                        Update Payment
                    </button>
                </form>
            </div>
            
<!-- Delivery Management -->
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-calendar-alt text-purple-500"></i> Delivery Management
    </h2>
    <form action="<?php echo e(route('admin.orders.delivery', $order)); ?>" method="POST" class="space-y-3">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Estimated Delivery Date</label>
            <input type="date" name="estimated_delivery" value="<?php echo e($order->estimated_delivery ? \Carbon\Carbon::parse($order->estimated_delivery)->format('Y-m-d') : ''); ?>" 
                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Tracking Number</label>
            <div class="flex gap-2">
                <input type="text" name="tracking_number" value="<?php echo e($order->tracking_number); ?>" 
                       class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                       placeholder="Enter tracking number or click Generate">
                <?php if(!$order->tracking_number): ?>
                <button type="button" onclick="generateTrackingNumber(<?php echo e($order->id); ?>)" 
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                    Generate
                </button>
                <?php else: ?>
                <button type="button" onclick="generateTrackingNumber(<?php echo e($order->id); ?>)" 
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    Regenerate
                </button>
                <?php endif; ?>
            </div>
        </div>
        <div>
            <label class="block text-sm text-gray-600 mb-1">Admin Notes (Internal)</label>
            <textarea name="admin_notes" rows="3" 
                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                      placeholder="Add internal notes about this order..."><?php echo e($order->admin_notes); ?></textarea>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="flex-1 bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
<script>
function generateTrackingNumber(orderId) {
    console.log('Generate button clicked for order:', orderId);
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found!');
        showNotification('CSRF token not found!', 'error');
        return;
    }
    
    const url = `/admin/orders/${orderId}/tracking`;
    console.log('Sending request to:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers.get('content-type'));
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // tracking number input field
            const trackingInput = document.querySelector('input[name="tracking_number"]');
            if (trackingInput) {
                trackingInput.value = data.tracking_number;
            }
            
            // Change the button text
            const generateBtn = document.querySelector('button[onclick*="generateTrackingNumber"]');
            if (generateBtn) {
                generateBtn.textContent = 'Regenerate';
                generateBtn.classList.remove('bg-gray-500');
                generateBtn.classList.add('bg-blue-500');
            }
            
            // Show success message
            showNotification(data.message, 'success');
        } else {
            showNotification(data.error || 'Failed to generate tracking number', 'error');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        showNotification('Error: ' + error.message, 'error');
    });
}

function showNotification(message, type) {
    // Remove existing notifications
    const oldNotif = document.querySelector('.notification-toast');
    if (oldNotif) oldNotif.remove();
    
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
            
            <!-- Order Summary Card -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow p-6 text-white">
                <h3 class="font-bold mb-2">Order Summary</h3>
                <div class="space-y-1 text-sm">
                    <p>Order   &nbsp;&nbsp;&nbsp;#: <?php echo e($order->order_number ?? $order->id); ?></p>
                    <p>Placed  &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo e($order->created_at->format('M d, Y')); ?></p>
                    <p>Total&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;: N$<?php echo e(number_format($order->total_amount, 2)); ?></p>
                    <p>Delivery &nbsp;&nbsp;: <?php echo e(ucfirst($order->delivery_method)); ?></p>
                    <?php if($order->actual_delivery_date): ?>
                        <p>Delivered&nbsp;: <?php echo e(\Carbon\Carbon::parse($order->actual_delivery_date)->format('M d, Y')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\admin\orders\show.blade.php ENDPATH**/ ?>