@extends('admin.layouts.app')

@section('title', 'Sales Report')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">📊 Sales Report</h1>
    
    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Report Period</label>
                <select name="period" class="px-4 py-2 border rounded-lg">
                    <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $start_date }}" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $end_date }}" class="px-4 py-2 border rounded-lg">
            </div>
            <div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Apply Filter
                </button>
            </div>
            <div>
                <a href="{{ route('admin.reports.export', request()->all()) }}" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 inline-block">
                    Export CSV
                </a>
            </div>
        </form>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <p class="text-3xl font-bold text-green-600">N${{ number_format($total_revenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Total Orders</p>
            <p class="text-3xl font-bold text-blue-600">{{ $total_orders }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-500 text-sm">Average Order Value</p>
            <p class="text-3xl font-bold text-purple-600">N${{ number_format($average_order_value, 2) }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Selling Books -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-xl font-bold">🏆 Top Selling Books</h2>
            </div>
            <div class="divide-y">
                @forelse($top_books as $book)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $book->title }}</p>
                            <p class="text-sm text-gray-500">by {{ $book->author }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-green-600 font-bold">N${{ number_format($book->total_revenue, 2) }}</p>
                            <p class="text-sm text-blue-600">{{ $book->total_sold }} copies sold</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">No sales data available</div>
                @endforelse
            </div>
        </div>
        
        <!-- Sales by Category -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-xl font-bold">📚 Sales by Category</h2>
            </div>
            <div class="divide-y">
                @forelse($sales_by_category as $category)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold">{{ $category->category }}</p>
                            <p class="text-sm text-gray-500">{{ $category->total_sold }} units sold</p>
                        </div>
                        <div>
                            <p class="text-green-600 font-bold">N${{ number_format($category->total_revenue, 2) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">No sales data available</div>
                @endforelse
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
                @forelse($sales_over_time as $sale)
                <div class="flex justify-between items-center border-b pb-2">
                    <div>
                        @if($period == 'daily')
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</p>
                        @else
                            <p class="font-semibold">{{ \Carbon\Carbon::create()->month($sale->month)->format('F') }} {{ $sale->year }}</p>
                        @endif
                        <p class="text-sm text-gray-500">{{ $sale->orders }} orders</p>
                    </div>
                    <div class="text-right">
                        <p class="text-green-600 font-bold">N${{ number_format($sale->revenue, 2) }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500">No sales data available</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Top Customers -->
    <div class="bg-white rounded-lg shadow mt-8">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-xl font-bold">👥 Top Customers</h2>
        </div>
        <div class="divide-y">
            @forelse($top_customers as $customer)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $customer->user->name ?? 'Guest' }}</p>
                        <p class="text-sm text-gray-500">{{ $customer->order_count }} orders</p>
                    </div>
                    <div>
                        <p class="text-green-600 font-bold">N${{ number_format($customer->total_spent, 2) }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">No customer data available</div>
            @endforelse
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
                    @forelse($recent_orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-sm">#{{ $order->order_number ?? $order->id }}</td>
                        <td class="px-6 py-4">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="px-6 py-4 text-right font-semibold">N${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs {{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection