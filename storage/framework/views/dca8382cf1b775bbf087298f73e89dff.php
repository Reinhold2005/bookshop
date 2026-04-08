<?php
    $lowStockBooks = App\Models\Book::whereColumn('stock', '<=', 'low_stock_threshold')
                                     ->where('stock', '>', 0)
                                     ->orderBy('stock', 'asc')
                                     ->get();
    $outOfStockBooks = App\Models\Book::where('stock', 0)->get();
    $totalLowStock = $lowStockBooks->count();
    $totalOutOfStock = $outOfStockBooks->count();
?>

<?php if($totalLowStock > 0 || $totalOutOfStock > 0): ?>
<div class="mb-6">
    <?php if($totalOutOfStock > 0): ?>
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 mb-3">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-times-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-red-800 font-semibold">Out of Stock Alert!</h3>
                    <p class="text-red-700 text-sm">
                        <strong><?php echo e($totalOutOfStock); ?></strong> Book(s) are completely out of stock.
                    </p>
                </div>
            </div>
            <a href="<?php echo e(route('admin.books.index', ['filter' => 'out_of_stock'])); ?>" 
               class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition text-sm">
                View Out of Stock
            </a>
        </div>
    </div>
    <?php endif; ?>

    <?php if($totalLowStock > 0): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4">
        <div class="flex items-start justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-yellow-800 font-semibold">Low Stock Alert!</h3>
                    <p class="text-yellow-700 text-sm">
                        <strong><?php echo e($totalLowStock); ?></strong> Book(s) are running low on stock.
                    </p>
                </div>
            </div>
            <a href="<?php echo e(route('admin.books.index', ['filter' => 'low_stock'])); ?>" 
               class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition text-sm">
                View Low Stock Books
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?><?php /**PATH C:\Test\bookshop\resources\views\admin\components\low-stock-alert.blade.php ENDPATH**/ ?>