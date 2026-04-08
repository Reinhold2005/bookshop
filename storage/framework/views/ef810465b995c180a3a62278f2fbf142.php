<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Admin Panel - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .back-button, .home-button {
            transition: all 0.3s ease;
        }
        .back-button:hover, .home-button:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Admin Navigation Bar -->
    <nav class="bg-purple-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <!-- Left side - Dashboard and Back buttons -->
                <div class="flex items-center space-x-4">
                    <!-- Dashboard Button -->
                   
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                    
                    
                    <!-- Back Button -->
                    <button onclick="goBack()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                   
                    <h1 class="text-xl font-bold text-white ml-4">Admin Panel</h1>
                    
                    <!-- View Site Button -->
                    <a href="<?php echo e(url('/')); ?>" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition flex items-center gap-1" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Site
                    </a>
                </div>
<a href="<?php echo e(route('admin.reports.sales')); ?>" class="text-white hover:text-purple-300 text-lg font-semibold">
    Sales Reports
</a>
                
                <!-- Manage Books, Manage Orders, Welcome, Logout -->
                <div class="flex items-center space-x-6">
                   
                    <a href="<?php echo e(route('admin.books.index')); ?>" class="text-white hover:text-purple-300 text-lg font-semibold">
                        Manage Books
                    </a>
                    
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-white hover:text-purple-300 text-lg font-semibold">
                        Manage Orders
                    </a>
                   
                    <span class="text-purple-200">Welcome, <?php echo e(Auth::user()->name); ?></span>
                    <form method="POST" action="<?php echo e(route('admin.logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition flex items-center gap-1">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Back Button JavaScript -->
    <script>
        function goBack() {
            window.history.back();
        }
        
        // Keyboard shortcut: Alt + Left Arrow
        document.addEventListener('keydown', function(e) {
            if (e.altKey && e.key === 'ArrowLeft') {
                window.history.back();
            }
        });
    </script>

    <?php if(isset($pendingCancellations) && $pendingCancellations > 0): ?>
    <script>
        // Play notification sound for new cancellations
        function playNotificationSound() {
            var audio = new Audio('https://www.soundjay.com/misc/sounds/bell-ringing-05.mp3');
            audio.play().catch(e => console.log('Audio not supported'));
        }
        
        // Only play once per session
        if (!sessionStorage.getItem('notificationPlayed')) {
            playNotificationSound();
            sessionStorage.setItem('notificationPlayed', 'true');
        }
    </script>
    <?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
<footer class="bg-gray-800 shadow-lg mt-8 py-4">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-center text-gray-400 text-sm">
            <div class="flex items-center gap-4 mb-2 md:mb-0">
                <i class="fas fa-copyright"></i>
                <p>&copy; <?php echo e(date('Y')); ?> Tales & Tomes Bookshop Admin Panel. All rights reserved.</p>
            </div>
            <div class="flex items-center gap-6">
                <a href="mailto:admin@bookshop.com" class="hover:text-white transition flex items-center gap-2">
                    <i class="fas fa-envelope"></i> admin@bookshop.com
                </a>
                <a href="tel:+264812345678" class="hover:text-white transition flex items-center gap-2">
                    <i class="fas fa-phone"></i> +264 81 821 8552
                </a>
                <a href="#" class="hover:text-white transition">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="hover:text-white transition">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="hover:text-white transition">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
</footer>
</body>
</html><?php /**PATH C:\Test\bookshop\resources\views\admin\layouts\app.blade.php ENDPATH**/ ?>