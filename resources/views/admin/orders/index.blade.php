@extends('admin.layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Manage Orders</h1>
    <!-- Quick Filter Buttons -->
<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.orders.index') }}" 
       class="px-4 py-2 rounded {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-blue-600 transition">
        All Orders
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-yellow-600 transition">
        Pending
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'processing' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-blue-600 transition">
        Processing
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'shipped' ? 'bg-purple-500 text-white' : 'bg-gray-200 text-gray-700' }} hover:bg-purple-600 transition">
        Shipped
    </a>
    <a href="{{ route('admin.orders.index', ['status' => 'cancellation_requested']) }}" 
       class="px-4 py-2 rounded {{ request('status') == 'cancellation_requested' ? 'bg-red-500 text-white' : 'bg-red-100 text-red-700' }} hover:bg-red-600 hover:text-white transition font-semibold">
        Cancellation Requests
        @if($stats['cancellation_requests'] ?? 0 > 0)
        <span class="ml-1 bg-red-600 text-white px-2 py-0.5 rounded-full text-xs">{{ $stats['cancellation_requests'] }}</span>
        @endif
    </a>
</div>
    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['total_orders'] }}</div>
            <div class="text-xs text-gray-500">Total Orders</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
            <div class="text-xs text-gray-500">Pending</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $stats['processing'] }}</div>
            <div class="text-xs text-gray-500">Processing</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-purple-600">{{ $stats['shipped'] }}</div>
            <div class="text-xs text-gray-500">Shipped</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $stats['delivered'] }}</div>
            <div class="text-xs text-gray-500">Delivered</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ $stats['cancelled'] }}</div>
            <div class="text-xs text-gray-500">Cancelled</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-green-600">N${{ number_format($stats['total_revenue'], 2) }}</div>
            <div class="text-xs text-gray-500">Revenue</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4 text-center">
            <div class="text-2xl font-bold text-orange-600">{{ $stats['pending_payment'] }}</div>
            <div class="text-xs text-gray-500">Pending Payment</div>
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left">Order #</th>
                    <th class="px-6 py-3 text-left">Customer</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Delivery</th>
                    <th class="px-6 py-3 text-left">Est. Delivery</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Payment</th>
                    <th class="px-6 py-3 text-left">Date</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-sm">#{{ $order->order_number ?? $order->id }}</td>
                    <td class="px-6 py-4">
                        {{ $order->user->name }}<br>
                        <span class="text-xs text-gray-500">{{ $order->user->email }}</span>
                    </td>
                    <td class="px-6 py-4 font-bold">N${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $order->delivery_badge }}">
                            {{ ucfirst($order->delivery_method) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($order->estimated_delivery)
                            {{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d, Y') }}
                        @else
                            <span class="text-gray-400">Not set</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $order->status_badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs {{ $order->payment_status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection