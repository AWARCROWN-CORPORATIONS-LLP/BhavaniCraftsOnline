<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\QuickBill;
use Carbon\Carbon;
use Illuminate\Support\Str;

class QuickBillingController extends Controller
{
    /**
     * Dashboard: Shows sales history, stats, and entry form
     */
    public function index()
    {
        $bills = QuickBill::orderBy('created_at', 'desc')->paginate(20);
        
        $todayStats = QuickBill::whereDate('created_at', Carbon::today())
            ->where('payment_status', 'paid')
            ->selectRaw('payment_method, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        $todaySales = $todayStats->sum('total');
        $cashSales = $todayStats->where('payment_method', 'cash')->first()->total ?? 0;
        $onlineSales = $todayStats->where('payment_method', 'online')->first()->total ?? 0;

        $dailyStats = QuickBill::where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        return view('admin.billing.dashboard', compact('bills', 'todaySales', 'todayStats', 'cashSales', 'onlineSales', 'dailyStats'));
    }

    /**
     * Create a new pending bill and prepare simulation of Razorpay QR
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'is_quotation' => 'nullable|boolean',
            'payment_method' => 'nullable|string|in:cash,online',
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.telugu_name' => 'nullable|string',
            'items.*.amount' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|integer|min:1',
        ]);


        $subtotal = collect($request->items)->sum(function($item) {
            return $item['amount'] * ($item['quantity'] ?? 1);
        });
        $discount = $request->discount_amount ?? 0;
        $gstPercent = 18; 
        
        $taxableAmount = $subtotal - $discount;
        $gstAmount = ($taxableAmount * $gstPercent) / 100;
        $total = $taxableAmount + $gstAmount;

        $paymentMethod = $request->payment_method ?? 'online';
        $paymentStatus = ($paymentMethod === 'cash') ? 'paid' : 'pending';

        $bill = QuickBill::create([
            'bill_number' => ($request->is_quotation ? 'QUOT-' : 'BC-') . strtoupper(Str::random(8)),
            'is_quotation' => $request->is_quotation ?? false,
            'items' => $request->items,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'gst_percent' => $gstPercent,
            'gst_amount' => $gstAmount,
            'total_amount' => $total,
            'payment_status' => $request->is_quotation ? 'pending' : $paymentStatus,
            'payment_method' => $paymentMethod,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'razorpay_order_id' => 'order_' . Str::random(14), // Dummy ID for simulation
        ]);

        return response()->json([
            'success' => true,
            'bill_id' => $bill->id,
            'total' => $total,
            'payment_method' => $paymentMethod,
            'razorpay_order_id' => $bill->razorpay_order_id,
        ]);
    }

    /**
     * Simulation of Payment Verification (Acting as a webhook or user callback)
     */
    public function verifyPayment(Request $request, $locale, $bill_id)
    {
        $bill = QuickBill::findOrFail($bill_id);
        
        $bill->update([
            'payment_status' => 'paid',
            'razorpay_payment_id' => $request->payment_id ?? ('pay_' . Str::random(14))
        ]);

        return redirect()->route('admin.billing.print', $bill->id)->with('success', 'Payment Received & Verified!');
    }

    /**
     * Search Customers by phone, email or name
     */
    public function searchCustomers(Request $request)
    {
        $q = $request->query('q');
        if (!$q || strlen($q) < 3) return response()->json([]);

        $users = \App\Models\User::where('phone', 'LIKE', "%{$q}%")
            ->orWhere('email', 'LIKE', "%{$q}%")
            ->orWhere('name', 'LIKE', "%{$q}%")
            ->take(5)
            ->get(['name', 'phone', 'email']);

        return response()->json($users);
    }

    /**
     * Search Products by name or code
     */
    public function searchProducts(Request $request)
    {
        $q = $request->query('q');
        if (!$q || strlen($q) < 2) return response()->json([]);

        $products = \App\Models\Product::where('product_name', 'LIKE', "%{$q}%")
            ->orWhere('product_code', 'LIKE', "%{$q}%")
            ->take(8)
            ->get(['product_name', 'telugu_name', 'price', 'product_code']);

        return response()->json($products);
    }

    /**
     * View/Print the Generated Bill
     */
    public function print($locale, $id)
    {
        $bill = QuickBill::findOrFail($id);
        return view('admin.billing.print', compact('bill'));
    }

    /**
     * Delete a Bill Record
     */
    public function destroy($locale, $id)
    {
        $bill = QuickBill::findOrFail($id);
        $bill->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Record successfully archived.']);
        }

        return redirect()->back()->with('success', 'Bill record purged from registry.');
    }
}
