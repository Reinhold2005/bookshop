

<?php $__env->startSection('title', 'Sales Report'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">📊 Sales Report</h1>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="<?php echo e(route('admin.reports.sales')); ?>" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Report Period</label>
                <select name="period" class="px-4 py-2 border rounded-lg">
                    <option value="daily" <?php echo e($period == 'daily' ? 'selected' : ''); ?>>Daily</option>
                    <option value="monthly" <?php echo e($period == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Date</label>
                <input type="date" name="start_date" value="<?php echo e($start_date); ?>" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Date</label>
                <input type="date" name="end_date" value="<?php echo e($end_date); ?>" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Apply Filter
                </button>
            </div>
            <div>
                <a href="<?php echo e(route('admin.reports.export', request()->all())); ?>" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 inline-block">
                    Export CSV
                </a>
            </div>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <p class="text-3xl font-bold text-green-600">N$<?php echo e(number_format($total_revenue, 2)); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Orders</p>
            <p class="text-3xl font-bold text-blue-600"><?php echo e($total_orders); ?></p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Average Order Value</p>
            <p class="text-3xl font-bold text-purple-600">N$<?php echo e(number_format($average_order_value, 2)); ?></p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Selling Books -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-xl font-bold">🏆 Top Selling Books</h2>
            </div>
            <div class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $top_books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold"><?php echo e($book->title); ?></p>
                            <p class="text-sm text-gray-500">by <?php echo e($book->author); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-green-600 font-bold">N$<?php echo e(number_format($book->total_revenue, 2)); ?></p>
                            <p class="text-sm text-blue-600"><?php echo e($book->total_sold); ?> copies sold</p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-6 text-center text-gray-500">No sales data available</div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Sales by Category -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-xl font-bold">📚 Sales by Category</h2>
            </div>
            <div class="divide-y">
                <?php $__empty_1 = true; $__currentLoopData = $sales_by_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold"><?php echo e($category->category); ?></p>
                            <p class="text-sm text-gray-500"><?php echo e($category->total_sold); ?> units sold</p>
                        </div>
                        <div>
                            <p class="text-green-600 font-bold">N$<?php echo e(number_format($category->total_revenue, 2)); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-6 text-center text-gray-500">No sales data available</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Sales Over Time -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-xl font-bold">📈 Sales Over Time</h2>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $sales_over_time; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex justify-between items-center border-b pb-2">
                    <div>
                        <?php if($period == 'daily'): ?>
                            <p class="font-semibold"><?php echo e(\Carbon\Carbon::parse($sale->date)->format('M d, Y')); ?></p>
                        <?php else: ?>
                            <p class="font-semibold"><?php echo e(\Carbon\Carbon::create()->month($sale->month)->format('F')); ?> <?php echo e($sale->year); ?></p>
                        <?php endif; ?>
                        <p class="text-sm text-gray-500"><?php echo e($sale->orders); ?> orders</p>
                    </div>
                    <div class="text-right">
                        <p class="text-green-600 font-bold">N$<?php echo e(number_format($sale->revenue, 2)); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center text-gray-500">No sales data available</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Top Customers -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-xl font-bold">👥 Top Customers</h2>
        </div>
        <div class="divide-y">
            <?php $__empty_1 = true; $__currentLoopData = $top_customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 hover:bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold"><?php echo e($customer->user->name ?? 'Guest'); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($customer->order_count); ?> orders</p>
                    </div>
                    <div>
                        <p class="text-green-600 font-bold">N$<?php echo e(number_format($customer->total_spent, 2)); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-6 text-center text-gray-500">No customer data available</div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Orders -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-xl font-bold">🛒 Recent Orders</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">Order #</th>
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-right">Amount</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__empty_1 = true; $__currentLoopData = $recent_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-sm">#<?php echo e($order->order_number ?? $order->id); ?></td>
                        <td class="px-6 py-4"><?php echo e($order->user->name ?? 'Guest'); ?></td>
                        <td class="px-6 py-4 text-right font-semibold">N$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs <?php echo e($order->status_badge); ?>">
                                <?php echo e(ucfirst($order->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm"><?php echo e($order->created_at->format('M d, Y')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Test\bookshop\resources\views\admin\reports\sales.blade.php ENDPATH**/ ?>