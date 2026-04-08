<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Tales & Tomes Bookshop- <?php echo $__env->yieldContent('title', 'Your Online Bookstore'); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        margin: 0;
        padding: 0;
    }
    
    nav {
        position: relative;
        top: 0;
        left: 0;
        width: 100%;
    }
    
    .back-button, .home-button {
        transition: all 0.3s ease;
    }
    .back-button:hover, .home-button:hover {
        transform: translateY(-2px);
    }
</style>
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
    <nav class="bg-purple-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <!-- Home Button -->
                    <a href="<?php echo e(url('/')); ?>" class="home-button bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        <i class="fas fa-home"></i> Home
                    </a>
                    
                    <!-- Back Button (JavaScript) -->
                    <button onclick="goBack()" class="back-button bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                    
                    <h1 class="text-white font-bold ml-4">📚 Tales & Tomes Bookshop</h1>

                    <a href="<?php echo e(route('client.orders.index')); ?>" class="text-white font-semibold hover:text-blue-500">My Orders</a>
                </div>
                <div class="flex items-center space-x-12">
                    <a href="<?php echo e(route('books.index')); ?>" class="text-white hover:text-blue-500 text-lg font-semibold">Books</a>
                    <a href="<?php echo e(route('cart.index')); ?>" class="text-white hover:text-blue-500 test-lg font-semibold">🛒 Cart</a>
                    <a href="<?php echo e(route('wishlist.index')); ?>" class="text-white hover:text-blue-500 text-lg font-semibold">Wishlist</a>
                    <?php if(auth()->guard()->check()): ?>
                        <span class="text-white">Hi, <?php echo e(Auth::user()->name); ?></span>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                    <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Login</a>
                        <a href="<?php echo e(route('register')); ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Cart Sidebar Component -->
<?php if(auth()->guard()->check()): ?>
    <?php echo $__env->make('components.cart-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

    <!-- Back Button JavaScript -->
    <script>
        function goBack() {
            window.history.back();
        }
        
        // keyboard shortcut (Alt + Left Arrow)
        document.addEventListener('keydown', function(e) {
            if (e.altKey && e.key === 'ArrowLeft') {
                window.history.back();
            }
        });
    </script>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
<footer class="bg-gray-900 text-white mt-8">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About Section -->
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-book"></i> Tales & Tomes Bookshop
                </h3>
                <p class="text-gray-400 text-sm">
                    Your favorite online bookstore. Discover thousands of books at amazing prices.
                </p>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-facebook-f text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition">
                        <i class="fab fa-whatsapp text-xl"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="<?php echo e(route('books.index')); ?>" class="hover:text-white transition">Browse Books</a></li>
                    <li><a href="<?php echo e(route('cart.index')); ?>" class="hover:text-white transition">My Cart</a></li>
                    <li><a href="<?php echo e(route('wishlist.index')); ?>" class="hover:text-white transition">My Wishlist</a></li>
                    <li><a href="<?php echo e(route('client.orders.index')); ?>" class="hover:text-white transition">My Orders</a></li>
                </ul>
            </div>
            
            <!-- Contact Information -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                <ul class="space-y-3 text-gray-400">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-blue-400"></i>
                        <span>2732 Patrick Street, Windhoek, Namibia</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone text-blue-400"></i>
                        <span>+264 81 821 8552</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-blue-400"></i>
                        <span>info@bookshop.com</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-clock text-blue-400"></i>
                        <span>Mon-Fri: 9AM - 6PM</span>
                    </li>
                </ul>
            </div>
            
            <!-- Newsletter -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                <p class="text-gray-400 text-sm mb-3">Subscribe to get special offers & updates</p>
                <form class="flex flex-col gap-2">
                    <input type="email" placeholder="Your email address" 
                           class="px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white focus:outline-none focus:border-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-500 text-sm">
            <p>&copy; <?php echo e(date('Y')); ?> Tales & Tomes Bookshop. All rights reserved. | Designed with <i class="fas fa-heart text-red-500"></i> for book lovers</p>
        </div>
    </div>
</footer>
</body>
</html><?php /**PATH C:\Test\bookshop\resources\views\app.blade.php ENDPATH**/ ?>