<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller

{
  public function dashboard()
{
    // Calculate revenue 
    $totalRevenue = Order::where('payment_status', 'paid')
                         ->where('status', '!=', 'cancelled')
                         ->sum('total_amount');
    
    return view('admin.welcome', compact('totalRevenue'));
}
public function getStats()
{
    // Calculate total revenue from all orders that are paid and not cancelled
    $totalRevenue = Order::where('payment_status', 'paid')
                         ->whereNotIn('status', ['cancelled', 'cancellation_requested'])
                         ->sum('total_amount');
    
    $stats = [
        'total_books' => Book::count(),
        'total_orders' => Order::count(),
        'total_users' => User::count(),
        'total_revenue' => $totalRevenue,
        'pending_cancellations' => Order::where('status', 'cancellation_requested')->count()
    ];
    
    return response()->json($stats);
}

public function getTopSellingBooks()
{
    $topBooks = Book::orderBy('sold_count', 'desc')
                    ->take(3)  
                    ->get(['id', 'title', 'author', 'price', 'sold_count', 'stock']);
    
    return response()->json($topBooks);
}
public function getRecentOrders()
{
    $orders = Order::with('user')
                   ->latest()
                   ->take(5)
                   ->get()
                   ->map(function($order) {
                       return [
                           'id' => $order->id,
                           'order_number' => $order->order_number,
                           'customer_name' => $order->user->name,
                           'total_amount' => $order->total_amount,
                           'status' => $order->status,
                           'created_at' => $order->created_at
                       ];
                   });
    
    return response()->json($orders);
}
}

