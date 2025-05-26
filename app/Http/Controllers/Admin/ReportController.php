<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function sales(Request $request)
    {
        $period = $request->period ?? 'month';
        $startDate = null;
        $endDate = null;

        // Determine the start and end dates based on the selected period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
                $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
                break;
        }

        // Get sales data
        $salesData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get total sales during the period
        $totalSales = $salesData->sum('total_sales');
        $totalOrders = $salesData->sum('order_count');
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Get top selling products
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price_at_order) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return view('admin.reports.sales', compact(
            'salesData',
            'period',
            'startDate',
            'endDate',
            'totalSales',
            'totalOrders',
            'averageOrderValue',
            'topProducts'
        ));
    }

    public function products(Request $request)
    {
        $period = $request->period ?? 'month';
        $startDate = null;
        $endDate = null;

        // Determine the start and end dates based on the selected period
        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
                $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
                break;
        }

        // Get top selling products
        $topSellingProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price_at_order) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_quantity')
            ->limit(20)
            ->get();

        // Get most profitable products
        $mostProfitableProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('COUNT(DISTINCT orders.id) as order_count'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price_at_order) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderByDesc('total_revenue')
            ->limit(20)
            ->get();

        // Get unsold products (stale inventory)
        $unsoldProducts = Product::whereNotIn('id', function ($query) use ($startDate, $endDate) {
            $query->select('product_id')
                ->from('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->whereBetween('orders.created_at', [$startDate, $endDate])
                ->where('orders.status', '!=', 'cancelled');
        })
            ->where('stock_quantity', '>', 0)
            ->select('id', 'name', 'price', 'stock_quantity', 'created_at')
            ->orderBy('created_at')
            ->limit(20)
            ->get();

        return view('admin.reports.products', compact(
            'topSellingProducts',
            'mostProfitableProducts',
            'unsoldProducts',
            'period',
            'startDate',
            'endDate'
        ));
    }

    public function customers(Request $request)
    {
        $period = $request->period ?? 'month';
        $startDate = null;
        $endDate = null;

        // Determine the start and end dates based on the selected period
        switch ($period) {
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'quarter':
                $startDate = Carbon::now()->startOfQuarter();
                $endDate = Carbon::now()->endOfQuarter();
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                $endDate = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
                $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();
                break;
        }

        // Top customers by value (amount spent)
        $topCustomersByValue = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total_amount) as total_spent')
            )
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(20)
            ->get();

        // Top customers by orders (number of orders)
        $topCustomersByOrders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total_amount) as total_spent'),
                DB::raw('AVG(orders.total_amount) as average_order_value')
            )
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email')
            ->orderByDesc('order_count')
            ->limit(20)
            ->get();

        // New customers
        $newCustomers = User::whereBetween('created_at', [$startDate, $endDate])
            ->select('id', 'first_name', 'last_name', 'email', 'created_at')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        // Overall customer statistics
        $totalCustomers = User::count();
        $newCustomersCount = $newCustomers->count();
        $activeCustomers = DB::table('orders')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->distinct('user_id')
            ->count('user_id');

        return view('admin.reports.customers', compact(
            'topCustomersByValue',
            'topCustomersByOrders',
            'newCustomers',
            'totalCustomers',
            'newCustomersCount',
            'activeCustomers',
            'period',
            'startDate',
            'endDate'
        ));
    }

    public function inventory()
    {
        // Get inventory status
        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity')
            ->get();

        $outOfStockProducts = Product::where('stock_quantity', 0)
            ->orderBy('name')
            ->get();

        $highStockProducts = Product::where('stock_quantity', '>', 50)
            ->orderByDesc('stock_quantity')
            ->get();

        // Inventory statistics
        $totalProducts = Product::count();
        $totalValue = Product::sum(DB::raw('price * stock_quantity'));
        $averageStock = Product::avg('stock_quantity');

        return view('admin.reports.inventory', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'highStockProducts',
            'totalProducts',
            'totalValue',
            'averageStock'
        ));
    }

    public function export(Request $request, $type)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->subMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // Export data based on the requested type
        switch ($type) {
            case 'sales':
                return $this->exportSalesReport($startDate, $endDate);
            case 'products':
                return $this->exportProductsReport($startDate, $endDate);
            case 'customers':
                return $this->exportCustomersReport($startDate, $endDate);
            case 'inventory':
                return $this->exportInventoryReport();
            default:
                return redirect()->back()->with('error', 'Invalid report type');
        }
    }

    private function exportSalesReport($startDate, $endDate)
    {
        // Implement sales report export
        // You can use a library like maatwebsite/excel
        // ...

        return redirect()->back()->with('success', 'Sales report exported successfully');
    }

    private function exportProductsReport($startDate, $endDate)
    {
        // Implement products report export
        // ...

        return redirect()->back()->with('success', 'Products report exported successfully');
    }

    private function exportCustomersReport($startDate, $endDate)
    {
        // Implement customers report export
        // ...

        return redirect()->back()->with('success', 'Customers report exported successfully');
    }

    private function exportInventoryReport()
    {
        // Implement inventory report export
        // ...

        return redirect()->back()->with('success', 'Inventory report exported successfully');
    }
}