<?php $__env->startSection('title', 'Add New Book'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">➕ Add New Book</h1>

    <form action="<?php echo e(route('admin.books.store')); ?>" method="POST" class="bg-white rounded-lg shadow p-6">
        <?php echo csrf_field(); ?>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Title</label>
            <input type="text" name="title" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Author</label>
            <input type="text" name="author" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="4" required class="w-full px-3 py-2 border rounded"></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Price</label>
            <input type="number" name="price" step="0.01" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Stock</label>
            <input type="number" name="stock" required class="w-full px-3 py-2 border rounded">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Category</label>
            <input type="text" name="category" required class="w-full px-3 py-2 border rounded">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Save Book</button>
        <a href="<?php echo e(route('admin.books.index')); ?>" class="ml-2 text-gray-500 hover:underline">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\admin\books\create.blade.php ENDPATH**/ ?>