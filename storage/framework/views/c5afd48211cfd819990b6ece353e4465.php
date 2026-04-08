

<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-96">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">📚 Welcome Back</h1>
        
        <?php if($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <p><?php echo e($error); ?></p>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                Login
            </button>
        </form>
        
        <p class="mt-4 text-center text-gray-600">
            Don't have an account? 
            <a href="<?php echo e(route('register')); ?>" class="text-blue-500 hover:underline">Register here</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\auth\login.blade.php ENDPATH**/ ?>