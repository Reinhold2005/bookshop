@extends('app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Order #{{ $order->order_number ?? $order->id }}</h1>
        <a href="{{ route('client.orders.index') }}" class="text-blue-500 hover:underline">← Back to Orders</a>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Status Timeline -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Order Status</h2>
                <div class="relative">
                    <div class="flex justify-between">
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Order Placed</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 {{ in_array($order->status, ['processing', 'shipped', 'out_for_delivery', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-cog text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Processing</p>
                        </div>
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 {{ in_array($order->status, ['shipped', 'out_for_delivery', 'delivered']) ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Shipped</p>
                        </div>
                        <div class="text-center flex-1">
                            <div class="w-10 h-10 {{ $order->status == 'delivered' ? 'bg-green-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-home text-white"></i>
                            </div>
                            <p class="text-sm font-semibold">Delivered</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Order Items</h2>
                @foreach($order->items as $item)
                <div class="flex justify-between items-center py-3 border-b">
                    <div>
                        <p class="font-semibold">{{ $item->book->title }}</p>
                        <p class="text-sm text-gray-500">by {{ $item->book->author }}</p>
                        <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold">N${{ number_format($item->price, 2) }}</p>
                        <p class="text-sm text-gray-500">Subtotal: N${{ number_format($item->price * $item->quantity, 2) }}</p>
                    </div>
                </div>
                @endforeach
                
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>N${{ number_format($order->subtotal ?? $order->total_amount - ($order->delivery_fee ?? 0), 2) }}</span>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span>Delivery Fee:</span>
                        <span>N${{ number_format($order->delivery_fee ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between mt-2 pt-2 border-t font-bold">
                        <span>Total:</span>
                        <span class="text-green-600">N${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Delivery Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Delivery Information</h2>
                <p class="text-sm text-gray-600 mb-1">Method:</p>
                <p class="font-semibold capitalize mb-3">{{ str_replace('_', ' ', $order->delivery_method) }}</p>
                
                <p class="text-sm text-gray-600 mb-1">Address:</p>
                <p class="text-sm">{{ $order->delivery_address }}</p>
                <p class="text-sm">{{ $order->city }}, {{ $order->postal_code }}</p>
                <p class="text-sm">Phone: {{ $order->phone }}</p>
                
                @if($order->tracking_number)
                <div class="mt-3 pt-3 border-t">
                    <p class="text-sm text-gray-600 mb-1">Tracking Number:</p>
                    <p class="font-mono text-sm">{{ $order->tracking_number }}</p>
                </div>
                @endif
                
                @if($order->estimated_delivery)
                <div class="mt-3 pt-3 border-t">
                    <p class="text-sm text-gray-600 mb-1">Estimated Delivery:</p>
                    <p class="text-sm font-semibold text-green-600">{{ \Carbon\Carbon::parse($order->estimated_delivery)->format('F d, Y') }}</p>
                </div>
                @endif
            </div>
            
            <!-- Cancellation Section -->
            @if($isEligibleForCancellation)
            <div class="bg-white rounded-lg shadow p-6 border-2 border-red-200">
                <h2 class="text-xl font-bold mb-4 text-red-600">Cancel Order</h2>
                <p class="text-sm text-gray-600 mb-4">
                    You can cancel this order within 7 days of purchase. 
                    Refunds will be processed within 5-7 business days.
                </p>
                
                <button onclick="openCancelModal()" class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                    Request Cancellation
                </button>
            </div>
            @elseif($order->status == 'cancellation_requested')
            <div class="bg-white rounded-lg shadow p-6 border-2 border-orange-200">
                <h2 class="text-xl font-bold mb-4 text-orange-600">Cancellation Requested</h2>
                <p class="text-sm text-gray-600">
                    Your cancellation request has been submitted. 
                    Refund of N${{ number_format($order->refund_amount, 2) }} will be processed within 5-7 business days.
                </p>
                @if($order->cancellation_reason)
                <p class="text-sm text-gray-500 mt-3">
                    <strong>Reason:</strong> {{ $order->cancellation_reason }}
                </p>
                @endif
            </div>
            @elseif(in_array($order->status, ['cancelled']))
            <div class="bg-white rounded-lg shadow p-6 border-2 border-gray-200">
                <h2 class="text-xl font-bold mb-4 text-gray-600">Order Cancelled</h2>
                <p class="text-sm text-gray-600">
                    This order has been cancelled.
                    @if($order->refund_status == 'completed')
                    Refund of N${{ number_format($order->refund_amount, 2) }} has been processed.
                    @endif
                </p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Cancellation Modal -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-xl font-bold mb-4">Request Cancellation</h3>
            <form action="{{ route('client.orders.cancel', $order) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Reason for cancellation</label>
                    <textarea name="cancellation_reason" rows="4" required 
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                              placeholder="Please tell us why you want to cancel this order..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                        Close
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        Submit Cancellation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCancelModal() {
    document.getElementById('cancelModal').classList.remove('hidden');
    document.getElementById('cancelModal').classList.add('flex');
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.add('hidden');
    document.getElementById('cancelModal').classList.remove('flex');
}
</script>
@endsection