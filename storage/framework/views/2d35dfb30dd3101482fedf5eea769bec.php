

<?php $__env->startSection('title', 'Shopping Cart'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">🛒 Shopping Cart</h1>
    
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    
    <?php if($cartItems->count() > 0): ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="flex flex-col md:flex-row">
                        <!-- Book Image/Icon -->
                        <div class="w-full md:w-32 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center p-6 md:p-0">
                            <i class="fas fa-book-open text-5xl text-white"></i>
                        </div>
                        
                        <!-- Book Details -->
                        <div class="flex-1 p-4">
                            <div class="flex flex-col md:flex-row justify-between">
                                <div class="flex-1">
                                    <h3 class="font-bold text-xl mb-1"><?php echo e($item->book->title); ?></h3>
                                    <p class="text-gray-600 text-sm mb-1">by <?php echo e($item->book->author); ?></p>
                                    <p class="text-gray-500 text-xs mb-2"><?php echo e($item->book->category); ?></p>
                                    <p class="text-green-600 font-bold text-2xl mb-3">$<?php echo e(number_format($item->book->price, 2)); ?></p>
                                   <span class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">
                                <i class="fas fa-box"></i> Instock
                                </span>
                                </div>
                                
                                <!-- Quantity Controls -->
                                <div class="mt-4 md:mt-0 md:text-right">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-gray-600 text-sm">Quantity:</span>
                                        <div class="flex items-center border rounded-lg overflow-hidden">
                                            <button onclick="updateQuantity(<?php echo e($item->id); ?>, <?php echo e($item->quantity - 1); ?>)" 
                                                    class="w-8 h-8 bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition"
                                                    <?php echo e($item->quantity <= 1 ? 'disabled' : ''); ?>>
                                                <i class="fas fa-minus text-gray-600"></i>
                                            </button>
                                            <span id="qty-<?php echo e($item->id); ?>" class="w-10 text-center font-semibold"><?php echo e($item->quantity); ?></span>
                                            <button onclick="updateQuantity(<?php echo e($item->id); ?>, <?php echo e($item->quantity + 1); ?>)" 
                                                    class="w-8 h-8 bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition"
                                                    <?php echo e($item->quantity >= $item->book->stock ? 'disabled' : ''); ?>>
                                                <i class="fas fa-plus text-gray-600"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-2">
                                        Subtotal: <span class="font-bold text-green-600" id="subtotal-<?php echo e($item->id); ?>">
                                            N$<?php echo e(number_format($item->book->price * $item->quantity, 2)); ?>

                                        </span>
                                    </p>
                                    
                                    <div class="flex gap-2 justify-end">
                                        <form action="<?php echo e(route('cart.remove', $item)); ?>" method="POST" onsubmit="return confirm('Remove this item from cart?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                    
                    <div class="space-y-3 border-b pb-4">
                        <?php $total = 0; ?>
                        <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $itemTotal = $item->book->price * $item->quantity; $total += $itemTotal; ?>
                            <div class="flex justify-between text-sm">
                                <span><?php echo e($item->book->title); ?> (x<?php echo e($item->quantity); ?>)</span>
                                <span>N$<?php echo e(number_format($itemTotal, 2)); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <div class="space-y-2 mt-4">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span class="font-semibold" id="cartTotal">N$<?php echo e(number_format($total, 2)); ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Shipping:</span>
                            <span>Calculated at checkout</span>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4 mt-4">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-green-600" id="grandTotal">N$<?php echo e(number_format($total, 2)); ?></span>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('checkout.index')); ?>" 
                       class="mt-6 w-full bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 transition text-center block font-semibold">
                        Proceed to Checkout →
                    </a>
                    
                    <a href="<?php echo e(route('books.index')); ?>" 
                       class="mt-3 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition text-center block">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
        
        <!-- JavaScript for AJAX Quantity Updates -->
        <script>
        function updateQuantity(cartId, newQuantity) {
            if (newQuantity < 1) {
                // If quantity becomes 0, ask user to remove
                if (confirm('Remove this item from cart?')) {
                    removeItem(cartId);
                }
                return;
            }
            
            // Send AJAX request to update quantity
            fetch('/cart/update-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cart_id: cartId,
                    quantity: newQuantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to refresh the cart
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update quantity. Please try again.');
            });
        }
        
        function removeItem(cartId) {
            fetch('/cart/remove-ajax', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    cart_id: cartId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to remove item. Please try again.');
            });
        }
        </script>
    <?php else: ?>
        <div class="text-center py-12 bg-white rounded-lg shadow">
            <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">Your cart is empty.</p>
            <a href="<?php echo e(route('books.index')); ?>" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                Browse Books
            </a>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\cart\index.blade.php ENDPATH**/ ?>