

<?php $__env->startSection('title', $book->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-8">
    <a href="<?php echo e(route('books.index')); ?>" class="text-blue-500 hover:underline mb-4 inline-block">← Back to Books</a>
    
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-4"><?php echo e($book->title); ?></h1>
        <p class="text-xl text-gray-700 mb-2">by <?php echo e($book->author); ?></p>
        <p class="text-gray-600 mb-4">
            <span class="bg-gray-200 px-2 py-1 rounded"><?php echo e($book->category); ?></span>
        </p>
        
       <div class="border-t border-b py-4 my-4">
            <p class="text-gray-700 leading-relaxed"><?php echo e($book->description ?? 'No description available.'); ?></p>
        </div> 

        <!-- Rating Section -->
<div class="mb-4">
    <div class="flex items-center gap-2 mb-2">
        <div class="flex items-center">
            <?php for($i = 1; $i <= 5; $i++): ?>
                <i class="fas fa-star text-2xl <?php echo e($i <= round($book->average_rating) ? 'text-yellow-400' : 'text-gray-300'); ?>"></i>
            <?php endfor; ?>
        </div>
        <span class="text-gray-600">(<?php echo e($book->total_ratings); ?> reviews)</span>
    </div>
    
    <div class="mt-3">
        <label class="block text-gray-700 mb-2">Rate this book:</label>
        <select onchange="rateBook(<?php echo e($book->id); ?>, this.value)" 
                class="px-4 py-2 border rounded-lg">
            <option value="">Select rating...</option>
            <option value="5" <?php echo e(($book->getUserRatingAttribute() == 5) ? 'selected' : ''); ?>>★★★★★ (5 - Excellent)</option>
            <option value="4" <?php echo e(($book->getUserRatingAttribute() == 4) ? 'selected' : ''); ?>>★★★★☆ (4 - Very Good)</option>
            <option value="3" <?php echo e(($book->getUserRatingAttribute() == 3) ? 'selected' : ''); ?>>★★★☆☆ (3 - Good)</option>
            <option value="2" <?php echo e(($book->getUserRatingAttribute() == 2) ? 'selected' : ''); ?>>★★☆☆☆ (2 - Fair)</option>
            <option value="1" <?php echo e(($book->getUserRatingAttribute() == 1) ? 'selected' : ''); ?>>★☆☆☆☆ (1 - Poor)</option>
        </select>
    </div>
</div>
        
        <div class="mt-4">
            <p class="text-3xl text-green-600 font-bold mb-2">N$<?php echo e(number_format($book->price, 2)); ?></p>
            <p class="text-gray-600 mb-4">📚 Instock</p>
            
            <div class="flex gap-3">
                <form action="<?php echo e(route('cart.add', $book)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600">
                        🛒 Add to Cart
                    </button>
                </form>
                
                <form action="<?php echo e(route('wishlist.add', $book)); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-lg hover:bg-red-600">
                        ❤️ Add to Wishlist
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\books\show.blade.php ENDPATH**/ ?>