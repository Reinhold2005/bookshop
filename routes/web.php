<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
    return view('welcome');
});

    Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

    Route::post('/wishlist/toggle/{book}', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle');

    Route::middleware(['auth'])->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/session', [App\Http\Controllers\CheckoutController::class, 'createCheckoutSession'])->name('checkout.session');
    Route::get('/checkout/success', [App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [App\Http\Controllers\CheckoutController::class, 'cancel'])->name('checkout.cancel');
    
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{cart}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    
    Route::post('/wishlist/add/{book}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');

    Route::post('/rating/rate/{book}', [App\Http\Controllers\RatingController::class, 'rate'])->name('rating.rate');

    Route::post('/wishlist/move-to-cart/{wishlist}', [App\Http\Controllers\WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

   // AJAX Cart Routes (no parameters in URL)
    Route::get('/cart/ajax', [App\Http\Controllers\CartController::class, 'ajaxCart'])->name('cart.ajax');
    Route::get('/cart/count', [App\Http\Controllers\CartController::class, 'getCartCount'])->name('cart.count');
    Route::post('/cart/update-ajax', [App\Http\Controllers\CartController::class, 'updateAjax'])->name('cart.update.ajax');
    Route::post('/cart/remove-ajax', [App\Http\Controllers\CartController::class, 'removeAjax'])->name('cart.remove.ajax');
});

// Admin Authentication Routes
    Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');
});

// Admin Protected Routes - using FULL middleware class path (NO alias)
Route::middleware([\App\Http\Middleware\AdminAuthMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('books', App\Http\Controllers\Admin\BookController::class);
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::put('/orders/{order}/payment', [App\Http\Controllers\Admin\OrderController::class, 'updatePayment'])->name('orders.payment');
    Route::put('/orders/{order}/delivery', [App\Http\Controllers\Admin\OrderController::class, 'updateDelivery'])->name('orders.delivery');
    Route::post('/orders/{order}/tracking', [App\Http\Controllers\Admin\OrderController::class, 'generateTrackingNumber'])->name('admin.orders.tracking');

    Route::get('/stats', [App\Http\Controllers\Admin\AdminController::class, 'getStats'])->name('admin.stats');
    Route::get('/recent-orders', [App\Http\Controllers\Admin\AdminController::class, 'getRecentOrders'])->name('admin.recent-orders');

    Route::get('/top-books', [App\Http\Controllers\Admin\AdminController::class, 'getTopSellingBooks'])->name('admin.top-books');

    // Sales Reports
    Route::get('/reports/sales', [App\Http\Controllers\Admin\ReportController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/sales-data', [App\Http\Controllers\Admin\ReportController::class, 'getSalesData'])->name('reports.sales-data');
    Route::get('/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'exportSales'])->name('reports.export');

});

// Client Order Routes
Route::middleware(['auth'])->prefix('my-orders')->name('client.orders.')->group(function () {
    Route::get('/', [App\Http\Controllers\Client\OrderController::class, 'index'])->name('index');
    Route::get('/{order}', [App\Http\Controllers\Client\OrderController::class, 'show'])->name('show');
    Route::post('/{order}/cancel', [App\Http\Controllers\Client\OrderController::class, 'requestCancellation'])->name('cancel');
});

// Admin Refund Routes (add to admin group)
Route::put('/orders/{order}/refund', [App\Http\Controllers\Admin\OrderController::class, 'processRefund'])->name('admin.orders.refund');
Route::post('/orders/{order}/approve-cancellation', [App\Http\Controllers\Admin\OrderController::class, 'approveCancellation'])->name('admin.orders.approve-cancellation');

Route::get('/test-cancel/{order}', function ($orderId) {
    $order = App\Models\Order::find($orderId);
    if (!$order) {
        return 'Order not found';
    }
    
    $user = auth()->user();
    if (!$user) {
        return 'Please login first';
    }
    
    if ($order->user_id !== $user->id) {
        return 'You don\'t own this order';
    }
    
    return 'Order found. User: ' . $user->name . ', Order Status: ' . $order->status;
});



require __DIR__.'/auth.php';