<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
   public function index(Request $request)
{
    $query = Order::with('user');
    
    // Filter by status if provided
    if ($request->has('status')) {
        $query->where('status', $request->status);
    }
    
    $orders = $query->latest()->paginate(15);
    
    $stats = [
        'total_orders' => Order::count(),
        'pending' => Order::where('status', 'pending')->count(),
        'processing' => Order::where('status', 'processing')->count(),
        'shipped' => Order::where('status', 'shipped')->count(),
        'delivered' => Order::where('status', 'delivered')->count(),
        'cancelled' => Order::where('status', 'cancelled')->count(),
        'cancellation_requests' => Order::where('status', 'cancellation_requested')->count(),
         'total_revenue' => Order::where('payment_status', 'paid')
                               ->whereNotIn('status', ['cancelled', 'cancellation_requested'])
                               ->sum('total_amount'),
        'pending_payment' => Order::where('payment_status', 'pending')->count()
    ];
    
    return view('admin.orders.index', compact('orders', 'stats'));
}
    
    public function show(Order $order)
    {
        $order->load('items.book', 'user');
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,out_for_delivery,delivered,cancelled'
        ]);
        
        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);
        
        // If delivered, set actual delivery date
        if ($request->status == 'delivered' && !$order->actual_delivery_date) {
            $order->update(['actual_delivery_date' => now()]);
        }
        
        return redirect()->back()->with('success', "Order status updated from {$oldStatus} to {$order->status}!");
    }
    
    public function updateDelivery(Request $request, Order $order)
    {
        $request->validate([
            'estimated_delivery' => 'nullable|date',
            'tracking_number' => 'nullable|string|max:100',
            'admin_notes' => 'nullable|string'
        ]);
        
        $order->update([
            'estimated_delivery' => $request->estimated_delivery,
            'tracking_number' => $request->tracking_number,
            'admin_notes' => $request->admin_notes
        ]);
        
        return redirect()->back()->with('success', 'Delivery information updated!');
    }
    
    public function updatePayment(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);
        
        $order->update(['payment_status' => $request->payment_status]);
        
        return redirect()->back()->with('success', 'Payment status updated!');
    }
    
    public function generateTrackingNumber(Order $order)
{
    // Generate a unique tracking number
    $trackingNumber = 'TRK-' . strtoupper(uniqid()) . '-' . $order->id;
    
    $order->update(['tracking_number' => $trackingNumber]);
    
    // Always return JSON for this endpoint
    return response()->json([
        'success' => true,
        'tracking_number' => $trackingNumber,
        'message' => "Tracking number generated: {$trackingNumber}"
    ]);
}

    public function processRefund(Request $request, Order $order)
{
    $request->validate([
        'refund_status' => 'required|in:pending,processing,completed,failed',
        'refund_amount' => 'required|numeric|min:0|max:' . $order->total_amount,
        'refund_notes' => 'nullable|string'
    ]);
    
    $order->update([
        'refund_status' => $request->refund_status,
        'refund_amount' => $request->refund_amount,
        'refund_notes' => $request->refund_notes,
        'refund_processed_at' => $request->refund_status == 'completed' ? now() : null
    ]);
    
    if ($request->refund_status == 'completed') {
        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);
    }
    
    return redirect()->back()->with('success', 'Refund status updated!');
}

public function approveCancellation(Order $order)
{
    if ($order->status != 'cancellation_requested') {
        return redirect()->back()->with('error', 'No pending cancellation request for this order.');
    }
    
    // Calculate refund amount
    $refundAmount = $order->total_amount;
    if ($order->status === 'processing') {
        $refundAmount = $order->total_amount * 0.9; // 10% restocking fee
    }
    
    $order->update([
        'status' => 'cancelled',
        'cancelled_at' => now(),
        'refund_status' => 'processing',
        'refund_amount' => $refundAmount
    ]);
    
    return redirect()->back()->with('success', "Cancellation approved. Refund of \${$refundAmount} will be processed.");
}
}