

<?php $__env->startSection('title', 'Register'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-96">
        <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">📚 Create Account</h1>
        
        <?php if($errors->any()): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="<?php echo e(route('register')); ?>">
            <?php echo csrf_field(); ?>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" value="<?php echo e(old('email')); ?>" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="password" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                Register
            </button>
        </form>
        
        <p class="mt-4 text-center text-gray-600">
            Already have an account? 
            <a href="<?php echo e(route('login')); ?>" class="text-blue-500 hover:underline">Login here</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\auth\register.blade.php ENDPATH**/ ?>