<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.book')
                       ->where('user_id', Auth::id())
                       ->latest()
                       ->paginate(10);
        
        return view('client.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load('items.book');
        
        // Check if order is eligible for cancellation
        $isEligibleForCancellation = $this->isEligibleForCancellation($order);
        
        return view('client.orders.show', compact('order', 'isEligibleForCancellation'));
    }
    
   public function requestCancellation(Request $request, Order $order)
{
    // Check if this is an AJAX request by checking the header
    $isAjax = $request->header('X-Requested-With') === 'XMLHttpRequest' || $request->ajax() || $request->wantsJson();
    
    // Ensure user owns this order
    if ($order->user_id !== Auth::id()) {
        if ($isAjax) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return redirect()->back()->with('error', 'Unauthorized');
    }
    
    // Check if order can be cancelled
    if (!$this->isEligibleForCancellation($order)) {
        $message = 'This order cannot be cancelled. It may already be shipped, delivered, or past the cancellation window.';
        if ($isAjax) {
            return response()->json(['error' => $message], 400);
        }
        return redirect()->back()->with('error', $message);
    }
    
    $request->validate([
        'cancellation_reason' => 'required|string|min:5|max:500'
    ]);
    
    // Calculate refund amount
    $refundAmount = floatval($order->total_amount);
    
    // Update the order
    $order->status = 'cancellation_requested';
    $order->cancellation_reason = $request->cancellation_reason;
    $order->cancellation_requested_at = now();
    $order->refund_status = 'pending';
    $order->refund_amount = $refundAmount;
    $order->save();
    
    if ($isAjax) {
        return response()->json([
            'success' => true,
            'message' => 'Cancellation request submitted. Refund of $' . number_format($refundAmount, 2) . ' will be processed.',
            'refund_amount' => $refundAmount
        ]);
    }
    
    return redirect()->back()->with('success', 'Cancellation request submitted. Refund of $' . number_format($refundAmount, 2) . ' will be processed within 5-7 business days.');
}

private function isEligibleForCancellation($order)
{
    // Cannot cancel if already delivered or cancelled
    if (in_array($order->status, ['delivered', 'cancelled', 'cancellation_requested'])) {
        return false;
    }
    
    // Cannot cancel if shipped and more than 24 hours
    if ($order->status === 'shipped') {
        $shippedAt = $order->updated_at;
        if ($shippedAt && $shippedAt->diffInHours(now()) > 24) {
            return false;
        }
    }
    
    // Check if within 7 days of order placement
    if ($order->created_at->diffInDays(now()) > 7) {
        return false;
    }
    
    return true;
}
}