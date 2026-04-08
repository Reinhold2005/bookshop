

<?php $__env->startSection('title', 'Manage Books'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">📚 Manage Books</h1>
        <a href="<?php echo e(route('admin.books.create')); ?>" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            + Add New Book
        </a>
    </div>

    <!-- Filter Buttons -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="<?php echo e(route('admin.books.index')); ?>" 
       class="px-4 py-2 rounded <?php echo e(!request('filter') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'); ?>">
        All Books
    </a>
    <a href="<?php echo e(route('admin.books.index', ['filter' => 'low_stock'])); ?>" 
       class="px-4 py-2 rounded <?php echo e(request('filter') == 'low_stock' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700'); ?>">
        ⚠️ Low Stock
    </a>
    <a href="<?php echo e(route('admin.books.index', ['filter' => 'out_of_stock'])); ?>" 
       class="px-4 py-2 rounded <?php echo e(request('filter') == 'out_of_stock' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700'); ?>">
        ❌ Out of Stock
    </a>
</div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Title</th>
                    <th class="px-6 py-3 text-left">Author</th>
                    <th class="px-6 py-3 text-left">Price</th>
                    <th class="px-6 py-3 text-left">Stock</th>
                    <th class="px-6 py-3 text-left">Sold</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-b">
                    <td class="px-6 py-4"><?php echo e($book->id); ?></td>
                    <td class="px-6 py-4"><?php echo e($book->title); ?></td>
                    <td class="px-6 py-4"><?php echo e($book->author); ?></td>
                    <td class="px-6 py-4">N$<?php echo e(number_format($book->price, 2)); ?></td>
                    <td class="px-6 py-4">
    <?php if($book->stock == 0): ?>
        <span class="px-2 py-1 rounded text-xs bg-red-500 text-white">
            Out of Stock
        </span>
    <?php elseif($book->stock <= $book->low_stock_threshold): ?>
        <span class="px-2 py-1 rounded text-xs bg-yellow-500 text-white">
            Low Stock (<?php echo e($book->stock); ?>)
        </span>
    <?php else: ?>
        <span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">
            In Stock (<?php echo e($book->stock); ?>)
        </span>
    <?php endif; ?>
</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold <?php echo e($book->sold_count > 0 ? 'text-blue-600' : 'text-gray-400'); ?>">
                            <?php echo e($book->sold_count); ?>

                        </span>
                        <?php if($book->sold_count > 0): ?>
                            <span class="text-xs text-gray-500 block">copies sold</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4">
                        <a href="<?php echo e(route('admin.books.edit', $book)); ?>" class="text-blue-500 hover:underline">Edit</a>
                        <form action="<?php echo e(route('admin.books.destroy', $book)); ?>" method="POST" class="inline ml-2">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Delete this book?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($books->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\admin\books\index.blade.php ENDPATH**/ ?>