

<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">🛒 Checkout</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
            <form id="checkoutForm" method="POST" action="<?php echo e(route('checkout.session')); ?>">
                <?php echo csrf_field(); ?>
                
                <!-- Delivery Information -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-blue-500"></i> Delivery Information
                    </h2>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Full Delivery Address *</label>
                        <textarea name="delivery_address" rows="3" required 
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                                  placeholder="Street address, building name, apartment number"></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-2">City *</label>
                            <input type="text" name="city" required 
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Postal Code *</label>
                            <input type="text" name="postal_code" required 
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-gray-700 mb-2">Phone Number *</label>
                        <input type="tel" name="phone" required 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                               placeholder="Your contact number for delivery updates">
                    </div>
                </div>
                
                <!-- Delivery Method -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-truck text-green-500"></i> Delivery Method
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php $__currentLoopData = $deliveryOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition delivery-option" data-price="<?php echo e($option['price']); ?>" data-days="<?php echo e($option['days']); ?>">
                            <input type="radio" name="delivery_method" value="<?php echo e($key); ?>" required class="mr-2">
                            <i class="fas <?php echo e($option['icon']); ?> text-blue-500 mr-2"></i>
                            <strong><?php echo e($option['name']); ?></strong>
                            <div class="text-sm text-gray-600 mt-1">+N$<?php echo e(number_format($option['price'], 2)); ?></div>
                            <div class="text-xs text-green-600 mt-1">
                                <i class="fas fa-clock"></i> Est: <?php echo e($option['days']); ?>

                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div id="deliveryEstimate" class="mt-3 text-sm text-gray-500"></div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-purple-500"></i> Payment Method
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="stripe" required class="mr-2">
                            <i class="fab fa-cc-stripe text-blue-500 mr-2"></i>
                            <strong>Credit/Debit Card</strong>
                            <div class="text-xs text-gray-500 mt-1">Secure payment via Stripe</div>
                        </label>
                        
                        <label class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="cash_on_delivery" class="mr-2">
                            <i class="fas fa-money-bill-wave text-green-500 mr-2"></i>
                            <strong>Cash on Delivery</strong>
                            <div class="text-xs text-gray-500 mt-1">Pay when you receive the order</div>
                        </label>
                        
                        <label class="border rounded-lg p-4 cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="bank_transfer" class="mr-2">
                            <i class="fas fa-university text-red-500 mr-2"></i>
                            <strong>Bank Transfer</strong>
                            <div class="text-xs text-gray-500 mt-1">Direct bank transfer</div>
                        </label>
                    </div>
                </div>
                
                <!-- Hidden fields for JS calculations -->
                <input type="hidden" id="deliveryPrice" value="0">
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-receipt"></i> Order Summary
                </h2>
                
                <div class="border-b pb-3 mb-3">
                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex justify-between mb-2 text-sm">
                        <span><?php echo e($item->book->title); ?> (x<?php echo e($item->quantity); ?>)</span>
                        <span>N$<?php echo e(number_format($item->book->price * $item->quantity, 2)); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>N$<?php echo e(number_format($total, 2)); ?></span>
                    </div>
                    <div class="flex justify-between" id="deliveryFeeRow">
                        <span>Delivery Fee:</span>
                        <span id="deliveryFee">N$0.00</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t pt-2 mt-2">
                        <span>Total:</span>
                        <span id="totalAmount">N$<?php echo e(number_format($total, 2)); ?></span>
                    </div>
                </div>
                
                <div id="deliveryInfo" class="mt-4 p-3 bg-blue-50 rounded-lg hidden">
                    <div class="flex items-center gap-2 text-blue-600">
                        <span id="deliveryDays"></span>
                    </div>
                </div>
                
                <button type="submit" form="checkoutForm" class="w-full mt-6 bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition text-lg font-semibold">
                    Place Order
                </button>
                
                <p class="text-xs text-gray-500 text-center mt-4">
                    By placing your order, you agree to our terms and conditions
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Calculate delivery fee and update total
document.querySelectorAll('input[name="delivery_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const selectedLabel = this.closest('.delivery-option');
        const price = parseFloat(selectedLabel.dataset.price);
        const days = selectedLabel.dataset.days;
        const subtotal = <?php echo e($total); ?>;
        const newTotal = subtotal + price;
        
        document.getElementById('deliveryFee').textContent = 'N$' + price.toFixed(2);
        document.getElementById('totalAmount').textContent = 'N$' + newTotal.toFixed(2);
        document.getElementById('deliveryPrice').value = price;
        
        document.getElementById('deliveryInfo').classList.remove('hidden');
        document.getElementById('deliveryDays').innerHTML = '<i class="fas fa-truck"></i> Estimated delivery: ' + days;
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\checkout\index.blade.php ENDPATH**/ ?>