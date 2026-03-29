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
        
        $todaySales = QuickBill::whereDate('created_at', Carbon::today())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        $dailyStats = QuickBill::where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        return view('admin.billing.dashboard', compact('bills', 'todaySales', 'dailyStats'));
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
            'discount_amount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string',
            'items.*.telugu_name' => 'nullable|string',
            'items.*.amount' => 'required|numeric|min:0',
        ]);


        $subtotal = collect($request->items)->sum('amount');
        $discount = $request->discount_amount ?? 0;
        $gstPercent = 18; 
        
        $taxableAmount = $subtotal - $discount;
        $gstAmount = ($taxableAmount * $gstPercent) / 100;
        $total = $taxableAmount + $gstAmount;

        $bill = QuickBill::create([
            'bill_number' => ($request->is_quotation ? 'QUOT-' : 'BC-') . strtoupper(Str::random(8)),
            'is_quotation' => $request->is_quotation ?? false,
            'items' => $request->items,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'gst_percent' => $gstPercent,
            'gst_amount' => $gstAmount,
            'total_amount' => $total,
            'payment_status' => $request->is_quotation ? 'pending' : 'pending',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'razorpay_order_id' => 'order_' . Str::random(14), // Dummy ID
        ]);


        // Generate a dummy payment URL (In reality, this would be Razorpay's link)
        $paymentUrl = route('admin.billing.verify', ['bill_id' => $bill->id, 'mock' => 'success']);
        $qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($paymentUrl);

        return response()->json([
            'success' => true,
            'bill_id' => $bill->id,
            'qr_code' => $qrCodeUrl,
            'total' => $total,
            'verify_url' => $paymentUrl
        ]);
    }

    /**
     * Simulation of Payment Verification (Acting as a webhook or user callback)
     */
    public function verifyPayment($locale, $bill_id)
    {
        $bill = QuickBill::findOrFail($bill_id);
        
        // Mock verification
        $bill->update([
            'payment_status' => 'paid',
            'razorpay_payment_id' => 'pay_' . Str::random(14)
        ]);

        return redirect()->route('admin.billing.print', $bill->id)->with('success', 'Payment Received & Verified!');
    }

    /**
     * View/Print the Generated Bill
     */
    public function print($locale, $id)
    {
        $bill = QuickBill::findOrFail($id);
        return view('admin.billing.print', compact('bill'));
    }
}

