

<?php $__env->startSection('title', 'Book Collection'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex gap-8">
        <!-- Sidebar - Categories -->
        <div class="w-64 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-lg sticky top-4">
                <div class="p-4 border-b bg-gradient-to-r from-blue-500 to-purple-600 rounded-t-lg">
                    <h2 class="text-white font-bold text-lg flex items-center gap-2">
                        <i class="fas fa-filter"></i> Categories
                    </h2>
                </div>
                
                <div class="p-3">
                    <!-- All Books Link -->
                    <a href="<?php echo e(route('books.index')); ?>" 
                       class="flex items-center justify-between p-3 rounded-lg mb-1 <?php echo e(!request('category') ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:bg-gray-50 text-gray-700'); ?> transition">
                        <span><i class="fas fa-book mr-2"></i> All Books</span>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded-full"><?php echo e($totalBooks ?? $books->total()); ?></span>
                    </a>
                    
                    <!-- Category List -->
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('books.index', ['category' => $category->category])); ?>" 
                       class="flex items-center justify-between p-3 rounded-lg mb-1 <?php echo e(request('category') == $category->category ? 'bg-blue-50 text-blue-600 font-semibold' : 'hover:bg-gray-50 text-gray-700'); ?> transition">
                        <span>
                            <?php if($category->category == 'Fiction'): ?>
                                <i class="fas fa-book-open mr-2"></i>
                            <?php elseif($category->category == 'Science Fiction'): ?>
                                <i class="fas fa-rocket mr-2"></i>
                            <?php elseif($category->category == 'Fantasy'): ?>
                                <i class="fas fa-dragon mr-2"></i>
                            <?php elseif($category->category == 'Romance'): ?>
                                <i class="fas fa-heart mr-2"></i>
                            <?php elseif($category->category == 'Classic'): ?>
                                <i class="fas fa-crown mr-2"></i>
                            <?php elseif($category->category == 'Dystopian'): ?>
                                <i class="fas fa-city mr-2"></i>
                            <?php elseif($category->category == 'Poetry'): ?>
                                <i class="fas fa-feather-alt mr-2"></i>
                            <?php elseif($category->category == 'Mystery'): ?>
                                <i class="fas fa-search mr-2"></i>
                            <?php elseif($category->category == 'Biography'): ?>
                                <i class="fas fa-user mr-2"></i>
                            <?php else: ?>
                                <i class="fas fa-tag mr-2"></i>
                            <?php endif; ?>
                            <?php echo e($category->category); ?>

                        </span>
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded-full"><?php echo e($category->count); ?></span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            
            <!-- Additional Info Box -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg mt-6 p-4 text-white text-center">
                <i class="fas fa-gem text-3xl mb-2"></i>
                <h3 class="font-bold">Premium Collection</h3>
                <p class="text-sm opacity-90 mt-1">Discover our curated selection of finest books</p>
            </div>
        </div>
        
        <!-- Main Content - Books Grid -->
        <div class="flex-1">
            <!-- Header with search and results count -->
            <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
                <div class="flex flex-wrap justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            <?php if(request('category')): ?>
                                <?php echo e(request('category')); ?>

                            <?php else: ?>
                                All Books
                            <?php endif; ?>
                        </h1>
                        <p class="text-gray-500 text-sm mt-1">
                            <i class="fas fa-book"></i> <?php echo e($books->total()); ?> Books Found
                        </p>
                    </div>
                    
                    <!-- Sort Options -->
                    <div class="flex gap-2">
                        <select class="px-3 py-2 border rounded-lg text-sm" onchange="window.location.href=this.value">
                            <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'newest'])); ?>">Newest First</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price_low'])); ?>">Price: Low to High</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price_high'])); ?>">Price: High to Low</option>
                            <option value="<?php echo e(request()->fullUrlWithQuery(['sort' => 'title_asc'])); ?>">Title: A to Z</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Search Form -->
            <form method="GET" action="<?php echo e(route('books.index')); ?>" class="mb-6">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               placeholder="Search by Title, Author, or Category..."
                               class="w-full pl-10 pr-4 py-3 border rounded-lg focus:outline-none focus:border-blue-500">
                        <?php if(request('category')): ?>
                            <input type="hidden" name="category" value="<?php echo e(request('category')); ?>">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                        Search
                    </button>
                    <?php if(request('search') || request('category')): ?>
                        <a href="<?php echo e(route('books.index')); ?>" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                            Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
            
            <!-- Books Grid -->
            <?php if($books->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="relative">
<!-- Wishlist Heart Button -->
<button onclick="toggleWishlist(<?php echo e($book->id); ?>)" 
        class="wishlist-btn-<?php echo e($book->id); ?> absolute top-2 right-2 z-10 bg-white rounded-full p-2 shadow-md hover:scale-110 transition">
    <i class="<?php echo e(Auth::check() && App\Models\Wishlist::where('user_id', Auth::id())->where('book_id', $book->id)->exists() ? 'fas fa-heart text-red-500' : 'far fa-heart text-red-500'); ?> text-xl"></i>
</button>
                        
                        <div class="bg-gradient-to-br from-blue-400 to-purple-500 p-6 text-white text-center">
                            <i class="fas fa-book-open text-5xl"></i>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-1 line-clamp-1"><?php echo e($book->title); ?></h3>
                        <p class="text-gray-600 text-sm mb-2">by <?php echo e($book->author); ?></p>
                        
                        <!-- Rating Stars -->
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center">
                                <?php
                                    $rating = $book->average_rating ?? 0;
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5;
                                ?>
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $fullStars): ?>
                                        <i class="fas fa-star text-yellow-400 text-sm"></i>
                                    <?php elseif($i == $fullStars + 1 && $halfStar): ?>
                                        <i class="fas fa-star-half-alt text-yellow-400 text-sm"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-yellow-400 text-sm"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <span class="text-xs text-gray-500">(<?php echo e($book->total_ratings ?? 0); ?> reviews)</span>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <!--  <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full"><?php echo e($book->category); ?></span>  -->
                            <span class="bg-green-100 text-green-600 text-xs px-2 py-1 rounded-full">
                                <i class="fas fa-box"></i> Instock
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-green-600">N$<?php echo e(number_format($book->price, 2)); ?></span>
                        </div>
                        
                        <!-- Rating Dropdown -->
                        <div class="mb-3">
                            <select onchange="rateBook(<?php echo e($book->id); ?>, this.value)" 
                                    class="w-full text-sm border rounded-lg px-3 py-2 bg-gray-50 focus:outline-none focus:border-blue-500">
                                <option value="">⭐ Rate this book...</option>
                                <option value="5" <?php echo e(($book->getUserRatingAttribute() == 5) ? 'selected' : ''); ?>>★★★★★ (5 - Excellent)</option>
                                <option value="4" <?php echo e(($book->getUserRatingAttribute() == 4) ? 'selected' : ''); ?>>★★★★☆ (4 - Very Good)</option>
                                <option value="3" <?php echo e(($book->getUserRatingAttribute() == 3) ? 'selected' : ''); ?>>★★★☆☆ (3 - Good)</option>
                                <option value="2" <?php echo e(($book->getUserRatingAttribute() == 2) ? 'selected' : ''); ?>>★★☆☆☆ (2 - Fair)</option>
                                <option value="1" <?php echo e(($book->getUserRatingAttribute() == 1) ? 'selected' : ''); ?>>★☆☆☆☆ (1 - Poor)</option>
                            </select>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="<?php echo e(route('books.show', $book)); ?>"
                               class="flex-1 bg-blue-500 text-white text-center px-3 py-2 rounded-lg hover:bg-blue-600 transition">
                                <i class="fas fa-info-circle"></i> Details
                            </a>
                            <form action="<?php echo e(route('cart.add', $book)); ?>" method="POST" class="flex-1">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition">
                                    <i class="fas fa-cart-plus"></i> Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                <?php echo e($books->links()); ?>

            </div>
            <?php else: ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No books found in this category.</p>
                <a href="<?php echo e(route('books.index')); ?>" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded">Browse All Books</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Toggle Wishlist (Like/Unlike)
function toggleWishlist(bookId) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/wishlist/toggle/${bookId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const heartIcon = document.querySelector(`.wishlist-btn-${bookId} i`);
            if (data.liked) {
                heartIcon.classList.remove('far');
                heartIcon.classList.add('fas');
                showNotification('❤️ Book added to wishlist!', 'success');
            } else {
                heartIcon.classList.remove('fas');
                heartIcon.classList.add('far');
                showNotification('💔 Book removed from wishlist', 'info');
            }
            // Optional: Reload to update wishlist count
            setTimeout(() => location.reload(), 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Please login to like books', 'error');
    });
}

// Show Notification Toast
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 'bg-blue-500'
    }`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'} mr-2"></i> ${message}`;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\books\index.blade.php ENDPATH**/ ?>