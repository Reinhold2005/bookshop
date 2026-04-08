<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Prepare report data
        $reportData = $this->getReportData($period, $startDate, $endDate);
        
        return view('admin.reports.sales', $reportData);
    }
    
    public function getSalesData(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $data = $this->getReportData($period, $startDate, $endDate);
        
        return response()->json($data);
    }
    
    private function getReportData($period, $startDate = null, $endDate = null)
    {
        // Build date range query
        $query = Order::where('payment_status', 'paid')
                      ->whereNotIn('status', ['cancelled', 'cancellation_requested']);
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // Summary statistics
        $totalRevenue = $query->sum('total_amount');
        $totalOrders = $query->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
                               ->groupBy('status')
                               ->get();
        
        // Top selling books
        $topBooks = OrderItem::select(
                                'books.id',
                                'books.title',
                                'books.author',
                                'books.price',
                                DB::raw('SUM(order_items.quantity) as total_sold'),
                                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                            )
                            ->join('books', 'order_items.book_id', '=', 'books.id')
                            ->groupBy('books.id', 'books.title', 'books.author', 'books.price')
                            ->orderBy('total_sold', 'desc')
                            ->limit(10)
                            ->get();
        
        // Sales by category
        $salesByCategory = OrderItem::select(
                                    'books.category',
                                    DB::raw('SUM(order_items.quantity) as total_sold'),
                                    DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
                                )
                                ->join('books', 'order_items.book_id', '=', 'books.id')
                                ->groupBy('books.category')
                                ->orderBy('total_revenue', 'desc')
                                ->get();
        
        // Daily/Monthly sales data for charts
        if ($period == 'daily') {
            $salesOverTime = Order::select(
                                    DB::raw('DATE(created_at) as date'),
                                    DB::raw('COUNT(*) as orders'),
                                    DB::raw('SUM(total_amount) as revenue')
                                )
                                ->where('payment_status', 'paid')
                                ->whereNotIn('status', ['cancelled', 'cancellation_requested'])
                                ->groupBy(DB::raw('DATE(created_at)'))
                                ->orderBy('date', 'desc')
                                ->limit(30)
                                ->get();
        } else {
            $salesOverTime = Order::select(
                                    DB::raw('YEAR(created_at) as year'),
                                    DB::raw('MONTH(created_at) as month'),
                                    DB::raw('COUNT(*) as orders'),
                                    DB::raw('SUM(total_amount) as revenue')
                                )
                                ->where('payment_status', 'paid')
                                ->whereNotIn('status', ['cancelled', 'cancellation_requested'])
                                ->groupBy('year', 'month')
                                ->orderBy('year', 'desc')
                                ->orderBy('month', 'desc')
                                ->limit(12)
                                ->get();
        }
        
        // Recent orders
        $recentOrders = Order::with('user')
                             ->where('payment_status', 'paid')
                             ->whereNotIn('status', ['cancelled', 'cancellation_requested'])
                             ->latest()
                             ->limit(10)
                             ->get();
        
        // Best customers
        $topCustomers = Order::select('user_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total_amount) as total_spent'))
                             ->with('user')
                             ->where('payment_status', 'paid')
                             ->whereNotIn('status', ['cancelled', 'cancellation_requested'])
                             ->groupBy('user_id')
                             ->orderBy('total_spent', 'desc')
                             ->limit(5)
                             ->get();
        
        return [
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'orders_by_status' => $ordersByStatus,
            'top_books' => $topBooks,
            'sales_by_category' => $salesByCategory,
            'sales_over_time' => $salesOverTime,
            'recent_orders' => $recentOrders,
            'top_customers' => $topCustomers,
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
    }
    
    public function exportSales(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $data = $this->getReportData($period, $startDate, $endDate);
        
        // Create CSV export
        $fileName = 'sales_report_' . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Sales Report - ' . date('Y-m-d')]);
            fputcsv($file, []);
            
            // Summary
            fputcsv($file, ['SUMMARY']);
            fputcsv($file, ['Total Revenue', 'N$' . number_format($data['total_revenue'], 2)]);
            fputcsv($file, ['Total Orders', $data['total_orders']]);
            fputcsv($file, ['Average Order Value', 'N$' . number_format($data['average_order_value'], 2)]);
            fputcsv($file, []);
            
            // Top Selling Books
            fputcsv($file, ['TOP SELLING BOOKS']);
            fputcsv($file, ['Title', 'Author', 'Quantity Sold', 'Revenue']);
            foreach ($data['top_books'] as $book) {
                fputcsv($file, [
                    $book->title,
                    $book->author,
                    $book->total_sold,
                    'N$' . number_format($book->total_revenue, 2)
                ]);
            }
            fputcsv($file, []);
            
            // Sales by Category
            fputcsv($file, ['SALES BY CATEGORY']);
            fputcsv($file, ['Category', 'Units Sold', 'Revenue']);
            foreach ($data['sales_by_category'] as $category) {
                fputcsv($file, [
                    $category->category,
                    $category->total_sold,
                    'N$' . number_format($category->total_revenue, 2)
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}