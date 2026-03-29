<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Display the Admin Dashboard with key stats
     */
    public function dashboard($locale)
    {
        // Intelligent Caching Layer: 5 minute TTL for heavy metrics
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_users' => User::count(),
                'pending_franchises' => User::where('user_type', 'business')->where('is_approved', 0)->count(),
                'total_products' => Product::count(),
                'total_orders' => Order::count(),
                'revenue_total' => Order::where('payment_status', 'Paid')->sum('total_amount'),
                'successful_deliveries' => Order::where('delivery_status', 'Delivered')->count(),
                'pending_returns' => Order::where('status', 'Return Requested')->count(),
                'low_stock_alerts' => Product::whereRaw('stock <= stock_threshold')->count(),
            ];
        });

        // Data Visualization Synthesis: Last 7 Days Revenue Trend
        $revenueTrend = Cache::remember('admin_revenue_trend', 3600, function () {
            $trend = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('M d');
                $sum = Order::where('payment_status', 'Paid')
                            ->whereDate('created_at', Carbon::now()->subDays($i))
                            ->sum('total_amount');
                $trend[$date] = $sum;
            }
            return $trend;
        });

        $activeBroadcasts = Cache::remember('admin_active_broadcasts', 3600, function () {
            return \App\Models\GlobalBroadcast::where('is_active', true)
                                ->whereIn('target_audience', ['all', 'exact:employee'])
                                ->orderBy('created_at', 'desc')
                                ->get();
        });

        // 🟢 System Telemetry: Real-time pulse of the sanctuary infrastructure
        $telemetry = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_os' => PHP_OS_FAMILY,
            'memory_limit' => ini_get('memory_limit'),
            'memory_usage' => number_format(memory_get_usage(true) / 1024 / 1024, 2) . ' MB',
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug') ? 'Enabled (Dev Mode)' : 'Disabled (Secure)',
            'database' => config('database.default'),
            'timezone' => config('app.timezone'),
        ];

        return view('admin.dashboard', compact('stats', 'activeBroadcasts', 'revenueTrend', 'telemetry'));

    }

    /**
     * Franchise / Business Account Approvals Management
     */
    public function franchiseManagement($locale)
    {
        $businessAccounts = User::where('user_type', 'business')->orderBy('is_approved', 'asc')->get();
        return view('admin.users.franchise_approvals', compact('businessAccounts'));
    }

    /**
     * Generic User Management
     */
    public function userManagement($locale)
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('admin.users.list', compact('users'));
    }

    /**
     * Approve a Franchise Account (as requested in the Objective)
     */
    public function approveFranchise($locale, $id)
    {
        if (auth()->user()->hasRole('employee')) {
            return back()->withErrors(['message' => 'Employees are not authorized to approve business accounts.']);
        }

        $user = User::findOrFail($id);
        
        if ($user->user_type === 'business') {
            $user->is_approved = 1;
            $user->save();

            // Notify User (Logic to be added: SMS/Email)
            return back()->with('success', "Account for {$user->name} has been approved.");
        }

        return back()->with('error', "User is not a business account.");
    }

    /**
     * Block or Unblock a seeker account
     */
    public function toggleBlock($locale, $id)
    {
        $user = User::findOrFail($id);
        
        // Prevent blocking yourself (the current super admin)
        if ($user->id === auth()->id()) {
            return back()->with('error', "Master Registry Error: You cannot block your own identity.");
        }

        // Super Admin / Admin / Franchise protection from Employee
        if (auth()->user()->hasRole('employee')) {
            if ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('franchise')) {
                return back()->withErrors(['message' => 'Auth Error: Employees lack clearance to modify elevated entities.']);
            }
        }

        $user->is_blocked = !$user->is_blocked;
        $user->save();

        $status = $user->is_blocked ? 'suspended' : 'restored';
        return back()->with('success', "Seeker identity {$user->name} has been {$status} in the registry.");
    }
}
