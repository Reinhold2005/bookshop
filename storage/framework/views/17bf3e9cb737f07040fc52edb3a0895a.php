

<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin.components.low-stock-alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2">Welcome back, <?php echo e(Auth::user()->name); ?>! </h1>
                <p class="text-purple-100 text-lg">Here's what's happening with your bookshop today.</p>
            </div>
            <div class="text-6xl" style="color: white;">
                <i class="fas fa-store"></i>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Books</p>
                    <p class="text-3xl font-bold text-gray-800" id="totalBooks">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('admin.books.index')); ?>" class="text-blue-500 text-sm hover:underline">Manage Books →</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-800" id="totalOrders">0</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-green-500 text-sm hover:underline">Manage Orders →</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-800" id="totalUsers">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-purple-500 text-sm">Registered Users</span>
            </div>
        </div>

       <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <p class="text-3xl font-bold text-green-600">N$<?php echo e(number_format($totalRevenue, 2)); ?></p>
        </div>
        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
            <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
        </div>
    </div>
</div>
    </div>

 

    <!-- Pending Cancellations Alert -->
    <div id="cancellationAlert" class="hidden mb-8">
        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-red-800 font-semibold">Pending Cancellation Requests</h3>
                    <p class="text-red-700 text-sm" id="cancellationCount">You have pending cancellation requests.</p>
                </div>
                <div class="ml-auto">
                    <a href="<?php echo e(route('admin.orders.index', ['status' => 'cancellation_requested'])); ?>" 
                       class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                        Review Now
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h2 class="text-xl font-bold flex items-center gap-2">
                        <i class="fas fa-clock text-gray-500"></i> Recent Orders
                    </h2>
                </div>
                <div class="divide-y" id="recentOrders">
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Loading orders...
                    </div>
                </div>
                <div class="px-6 py-3 bg-gray-50 border-t">
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="text-blue-500 hover:underline text-sm">View All Orders →</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-bolt text-yellow-500"></i> Quick Actions
                </h2>
                <div class="space-y-3">
                    <a href="<?php echo e(route('admin.books.create')); ?>" 
                       class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Add New Book</p>
                            <p class="text-sm text-gray-500">Add a new book to your inventory</p>
                        </div>
                    </a>
                    
                    <a href="<?php echo e(route('admin.orders.index')); ?>" 
                       class="flex items-center gap-3 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-eye text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold">View All Orders</p>
                            <p class="text-sm text-gray-500">Manage customer orders</p>
                        </div>
                    </a>
                    
                    <a href="<?php echo e(route('admin.books.index')); ?>" 
                       class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-edit text-white"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Manage Inventory</p>
                            <p class="text-sm text-gray-500">Update book stock and prices</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Admin Tips -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-bold mb-3 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-yellow-500"></i> Admin Tips
                </h2>
                <ul class="space-y-2 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        Monitor pending cancellation requests daily
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        Update book stock when new shipments arrive
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        Process refunds within 5-7 business days
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-check-circle text-green-500 text-xs"></i>
                        Keep delivery tracking numbers updated
                    </li>
                </ul>
            </div>

  <!-- Top Selling Books -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mt-8">
    <div class="px-6 py-4 bg-gradient-to-r from-yellow-400 to-orange-500">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-trophy"></i> Top 3 Best Selling Books
        </h2>
        <p class="text-yellow-100 text-sm mt-1">Most popular books this month</p>
    </div>
    <div class="divide-y" id="topBooksContainer">
        <div class="p-6 text-center text-gray-500">
            <i class="fas fa-spinner fa-spin mr-2"></i> Loading top books...
        </div>
    </div>
</div>


        </div>
    </div>
</div>

<script>
// Load dashboard statistics via AJAX
function loadDashboardStats() {
    fetch('/admin/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('totalBooks').innerText = data.total_books;
            document.getElementById('totalOrders').innerText = data.total_orders;
            document.getElementById('totalUsers').innerText = data.total_users;
            document.getElementById('totalRevenue').innerText = '$' + data.total_revenue.toFixed(2);
            
            if (data.pending_cancellations > 0) {
                document.getElementById('cancellationAlert').classList.remove('hidden');
                document.getElementById('cancellationCount').innerHTML = 
                    'You have <strong>' + data.pending_cancellations + '</strong> pending cancellation requests.';
            }
        })
        .catch(error => console.error('Error loading stats:', error));
}

// Load recent orders
function loadRecentOrders() {
    fetch('/admin/recent-orders')
        .then(response => response.json())
        .then(orders => {
            const container = document.getElementById('recentOrders');
            if (orders.length === 0) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No recent orders</div>';
                return;
            }
            
            let html = '';
            orders.forEach(order => {
                const statusColor = {
                    'pending': 'bg-yellow-100 text-yellow-800',
                    'processing': 'bg-blue-100 text-blue-800',
                    'shipped': 'bg-purple-100 text-purple-800',
                    'delivered': 'bg-green-100 text-green-800',
                    'cancelled': 'bg-red-100 text-red-800'
                }[order.status] || 'bg-gray-100 text-gray-800';
                
                html += `
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="font-semibold">Order #${order.order_number || order.id}</p>
                                <p class="text-sm text-gray-500">${order.customer_name}</p>
                                <p class="text-xs text-gray-400">${new Date(order.created_at).toLocaleDateString()}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">$${order.total_amount}</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full ${statusColor} mt-1">
                                    ${order.status}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            document.getElementById('recentOrders').innerHTML = 
                '<div class="p-6 text-center text-gray-500">Error loading orders</div>';
        });
}

// Load top selling books 
function loadTopSellingBooks() {
    fetch('/admin/top-books')
        .then(response => response.json())
        .then(books => {
            const container = document.getElementById('topBooksContainer');
            if (books.length === 0) {
                container.innerHTML = '<div class="p-6 text-center text-gray-500">No books sold yet</div>';
                return;
            }
            
            let html = '';
            books.forEach((book, index) => {
                // Top 3 (index 0,1,2)
                if (index < 3) {
                    const medalIcon = index === 0 ? '🥇' : (index === 1 ? '🥈' : '🥉');
                    html += `
                        <div class="p-4 hover:bg-gray-50 transition">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="text-3xl">${medalIcon}</div>
                                    <div>
                                        <p class="font-semibold text-lg">${book.title}</p>
                                        <p class="text-sm text-gray-500">by ${book.author}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-green-600">N$${parseFloat(book.price).toFixed(2)}</p>
                                    <p class="text-sm text-blue-600 font-semibold">${book.sold_count} copies sold</p>
                                    <p class="text-xs text-gray-400">Stock: ${book.stock} left</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            // View more link if there are more books
            if (books.length > 3) {
                html += `
                    <div class="p-4 text-center border-t">
                        <a href="<?php echo e(route('admin.books.index')); ?>" class="text-blue-500 hover:underline">
                            View all books →
                        </a>
                    </div>
                `;
            }
            
            container.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading top books:', error);
            document.getElementById('topBooksContainer').innerHTML = 
                '<div class="p-6 text-center text-gray-500">Error loading top books</div>';
        });
}

// Call the function when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadTopSellingBooks();
});

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadRecentOrders();
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\admin\welcome.blade.php ENDPATH**/ ?>