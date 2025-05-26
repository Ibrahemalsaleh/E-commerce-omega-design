<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * عرض لوحة التحكم الرئيسية للمشرف
     */
    public function index()
    {
        // إحصائيات عامة
        $ordersCount = Order::count();
        $productsCount = Product::count();
        $usersCount = User::count();
        $unreadMessages = ContactMessage::where('status', 'unread')->count();
        
        // إجمالي المبيعات
        $totalSales = Order::sum('total_amount');
        
        // المبيعات الشهرية للرسم البياني
        $monthlySales = [];
        $months = [];
        
        // الحصول على بيانات الستة أشهر الماضية
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('F');
            
            $monthlySales[] = Order::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_amount');
        }
        
        // أحدث الطلبات
        $latestOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // حالات الطلبات للرسم البياني
        $orderStatusStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        // بيانات المبيعات الشهرية للرسم البياني
        $salesChart = [
            'labels' => $months,
            'data' => $monthlySales
        ];
        
        // المنتجات الأكثر مبيعًا
        $bestSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * products.price) as total_sales')
            )
            ->groupBy('products.id', 'products.name', 'products.price')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'ordersCount',
            'productsCount',
            'usersCount',
            'totalSales',
            'monthlySales',
            'months',
            'latestOrders',
            'orderStatusStats',
            'unreadMessages',
            'salesChart',
            'bestSellingProducts'
        ));
    }
    
}