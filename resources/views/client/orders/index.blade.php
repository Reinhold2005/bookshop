@extends('app')

@section('title', 'My Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">My Orders</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if($orders->count() > 0)
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Order #</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Delivery Method</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono">#{{ $order->order_number ?? $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 font-bold">N${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4 capitalize">{{ str_replace('_', ' ', $order->delivery_method) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs 
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status == 'out_for_delivery') bg-orange-100 text-orange-800
                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                            @elseif($order->status == 'cancellation_requested') bg-orange-100 text-orange-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('client.orders.show', $order) }}" class="text-blue-500 hover:underline">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-12 text-center">
        <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-500 text-lg">You haven't placed any orders yet.</p>
        <a href="{{ route('books.index') }}" class="inline-block mt-4 bg-blue-500 text-white px-6 py-2 rounded">Start Shopping</a>
    </div>
    @endif
</div>
@endsection