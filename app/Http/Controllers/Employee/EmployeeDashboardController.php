<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\RestockRequest;
use App\Models\GlobalBroadcast;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    /**
     * Display the Employee Dashboard with key management stats
     */
    public function dashboard()
    {
        $stats = [
            'total_products'   => Product::count(),
            'total_orders'     => Order::count(),
            'pending_orders'   => Order::where('status', 'Processing')->count(),
            'total_categories' => Category::count(),
            'pending_restocks' => RestockRequest::where('status', 'pending')->count(),
            'successful_deliveries' => Order::where('delivery_status', 'Delivered')->count(),
            'pending_returns' => Order::where('status', 'Return Requested')->count(),
            'low_stock_alerts' => Product::whereRaw('stock <= stock_threshold')->count(),
        ];

        $activeBroadcasts = GlobalBroadcast::where('is_active', true)
                                ->whereIn('target_audience', ['all', 'exact:employee'])
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();

        $recentOrders = Order::with('user')->orderBy('ordered_date', 'desc')->take(8)->get();

        return view('employee.dashboard', compact('stats', 'activeBroadcasts', 'recentOrders'));
    }
}
