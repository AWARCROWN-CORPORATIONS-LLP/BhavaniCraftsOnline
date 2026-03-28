<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentVerificationController extends Controller
{
    /**
     * Show the payment verification search page.
     */
    public function index()
    {
        Log::info("Staff Portal: Payment Verification Index Loaded by " . (Auth::user()->name ?? 'Guest'));
        return view('shared.payment_verification');
    }

    /**
     * Search for an order by Razorpay Order ID, Email, or Phone.
     */
    public function search(Request $request)
    {
        Log::info("Staff Portal: Payment Verification Query Initiated", $request->all());

        $q = $request->input('query');

        // Fallback for direct query params like ?1 or ?razorpayid
        if (!$q) {
            $allParams = $request->query();
            if (count($allParams) > 0) {
                // Return first param key if it exists
                $q = array_key_first($allParams);
            }
        }

        if (!$q || strlen($q) < 1) {
            return back()->with('error', 'Query missing. Provide a partial Razorpay ID, email, or order ID.');
        }

        // Expanded query scope with LIKE
        $orders = Order::where('razorpay_order_id', 'LIKE', "%$q%")
            ->orWhere('order_id_string', 'LIKE', "%$q%")
            ->orWhere('id', 'LIKE', "%$q%") // Now partial ID matching too
            ->orWhereHas('user', function($u) use ($q) {
                $u->where('email', 'LIKE', "%$q%")->orWhere('phone', 'LIKE', "%$q%");
            })
            ->with(['user', 'items'])
            ->latest()
            ->paginate(15);

        // Debug log if result count is 0
        if ($orders->isEmpty()) {
            Log::info("Registry Search Yielded No Matches for: '{$q}'");
        }

        return view('shared.payment_verification', compact('orders', 'q'));
    }

    /**
     * Verify the payment status via Razorpay API.
     */
    public function verify(Order $order, Request $request)
    {
        $razorpayOrderId = $request->input('manual_razorpay_order_id', $order->razorpay_order_id);

        if (!$razorpayOrderId) {
            return back()->with('error', 'Audit Blocked: Razorpay Order ID reference missing on this record.');
        }

        try {
            Log::info("Invoking Razorpay Verification for Registry Item: {$order->id}");

            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            
            // Sync with Razorpay order object
            $razorpayOrder = $api->order->fetch($razorpayOrderId);
            
            // Retrieve all associated transactions
            $payments = $razorpayOrder->payments();
            
            $isPaid = false;
            $paymentId = null;

            foreach ($payments->items as $payment) {
                if ($payment->status === 'captured') {
                    $isPaid = true;
                    $paymentId = $payment->id;
                    break;
                }
            }

            if ($isPaid) {
                $order->update([
                    'payment_status' => 'Paid',
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_order_id' => $razorpayOrderId, 
                    'status' => $order->status === 'pending' ? 'Processing' : $order->status
                ]);
                return back()->with('success', "Audit Success: Payment identified (ID: {$paymentId}) sync complete.");
            } else {
                return back()->with('info', "Audit Update: Razorpay Order status is '{$razorpayOrder->status}'. No captured payments identified.");
            }

        } catch (\Exception $e) {
            Log::error("Registry Audit Failure: " . $e->getMessage());
            return back()->with('error', 'Communications Failure with Razorpay: ' . $e->getMessage());
        }
    }
}
