<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;
use Razorpay\Api\Api;
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Show the checkout page with cart summary and address selection.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please sign in to proceed to checkout.');
        }

        $cartQuery = CartItem::with(['product.images'])
            ->where('user_id', Auth::id());

        if (request()->filled('single_cart_item')) {
            $cartQuery->where('id', request('single_cart_item'));
        }

        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('info', 'Your cart is empty.');
        }

        $addresses = Address::where('user_id', Auth::id())->orderByDesc('is_default')->get();

        // Calculate totals
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        
        $discountAmount = 0;
        $appliedCoupon = session('applied_coupon');
        if ($appliedCoupon) {
            $coupon = \App\Models\Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
               
                session(['applied_coupon.discount_amount' => $discountAmount]);
            } else {
                session()->forget('applied_coupon');
                $appliedCoupon = null;
            }
        }

        $discountedSubtotal = max(0, $subtotal - $discountAmount);
        
        // Dynamic GST calculation based on individual product rates
        $gst = $cartItems->reduce(function($carry, $item) use ($subtotal, $discountAmount) {
            $itemSubtotal = $item->product->price * $item->quantity;
            // Distribute discount proportionally to items for accurate tax base
            $proportionalDiscount = $subtotal > 0 ? ($itemSubtotal / $subtotal) * $discountAmount : 0;
            $taxableValue = max(0, $itemSubtotal - $proportionalDiscount);
            return $carry + ($taxableValue * (($item->product->gst_rate ?? 18) / 100));
        }, 0);
        $gst = round($gst, 2);

        $shipping = $discountedSubtotal >= 999 ? 0 : 80;
        $total    = $discountedSubtotal + $gst + $shipping;

        return view('public.checkout', compact('cartItems', 'addresses', 'subtotal', 'gst', 'shipping', 'total', 'discountAmount', 'appliedCoupon'));
    }

    /**
     * Create a Razorpay order and return the order ID to the frontend.
     */
    public function createOrder(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'nullable|in:razorpay,cod',
        ]);

        $address = Address::findOrFail($request->address_id);

        if ($address->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized address.'], 403);
        }

        $cartQuery = CartItem::with('product')->where('user_id', Auth::id());

        if ($request->filled('single_cart_item')) {
            $cartQuery->where('id', $request->single_cart_item);
        }

        $cartItems = $cartQuery->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        
        $discountAmount = 0;
        $couponId = null;
        if (session()->has('applied_coupon')) {
            $appliedCoupon = session('applied_coupon');
            $coupon = \App\Models\Coupon::find($appliedCoupon['id']);
            if ($coupon && $coupon->isValid($subtotal)) {
                $discountAmount = $coupon->calculateDiscount($subtotal);
                $couponId = $coupon->id;
            } else {
                session()->forget('applied_coupon');
            }
        }

        $discountedTotalLocal = max(0, $subtotal - $discountAmount);
        
        $gst = $cartItems->reduce(function($carry, $item) use ($subtotal, $discountAmount) {
            $itemSubtotal = $item->product->price * $item->quantity;
            $proportionalDiscount = $subtotal > 0 ? ($itemSubtotal / $subtotal) * $discountAmount : 0;
            $taxableValue = max(0, $itemSubtotal - $proportionalDiscount);
            return $carry + ($taxableValue * (($item->product->gst_rate ?? 18) / 100));
        }, 0);
        $gst = round($gst, 2);

        $shipping = $discountedTotalLocal >= 999 ? 0 : 80;
        $total    = $discountedTotalLocal + $gst + $shipping;

        $paymentMethod = $request->input('payment_method', 'razorpay');

        // ── COD path: place order directly ──────────────────────────────────
        if ($paymentMethod === 'cod') {
            // Security: Disable COD for high-value orders to prevent fraudulent activity
            if ($total > 5000) {
                return response()->json([
                    'error' => 'Cash on Delivery (COD) is not available for orders above ₹5,000. Please select an online payment method to proceed.'
                ], 400);
            }
            
            DB::beginTransaction();
            try {
                $order = Order::create([
                    'user_id'         => Auth::id(),
                    'order_id_string' => 'BCM-' . strtoupper(Str::random(8)),
                    'address_id'      => $address->id,
                    'status'          => 'Processing',
                    'total_amount'    => $total,
                    'currency'        => 'INR',
                    'subtotal'        => $subtotal,
                    'discount_total'  => $discountAmount,
                    'tax_total'       => $gst,
                    'shipping_total'  => $shipping,
                    'payment_status'  => 'Pending',
                    'payment_method'  => 'COD',
                    'coupon_id'       => $couponId,
                    'discount_amount' => $discountAmount,
                ]);

                if ($couponId) {
                    \App\Models\Coupon::where('id', $couponId)->increment('used_count');
                }

                foreach ($cartItems as $item) {
                    /** @var \App\Models\CartItem $item */
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product->product_name,
                        'quantity'     => $item->quantity,
                        'price'        => $item->product->price,
                        'tax_amount'   => round(($item->product->price * $item->quantity) * (($item->product->gst_rate ?? 18) / 100), 2),
                    ]);
                    $item->delete(); // Delete only the items processed in this order
                }

                session()->forget('applied_coupon');
                DB::commit();

                // Send Confirmation Email
                try {
                    Mail::to(Auth::user()->email)->send(new OrderConfirmed($order));
                } catch (\Exception $e) {
                    \Log::error('Order Confirmation Email failed for order ' . $order->order_id_string . ': ' . $e->getMessage());
                }

                return response()->json([
                    'success'  => true,
                    'order_id' => $order->order_id_string,
                    'redirect' => route('checkout.success', ['token' => $order->encryptedId(), 'locale' => app()->getLocale()]),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Order creation failed. Please try again.'], 500);
            }
        }

        // ── Razorpay path ────────────────────────────────────────────────────
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $razorpayOrder = $api->order->create([
                'receipt'         => 'BCM-' . strtoupper(Str::random(8)),
                'amount'          => (int) round($total * 100), // in paise
                'currency'        => 'INR',
                'payment_capture' => 1, // auto-capture
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Payment gateway error. Please try again.'], 500);
        }

        // ── CREATE THE ORDER IN DB (Pending status) ────────────────────────────────
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'            => Auth::id(),
                'order_id_string'    => 'BCM-' . strtoupper(Str::random(8)),
                'address_id'         => $address->id,
                'status'             => 'Pending Payment',
                'total_amount'       => $total,
                'currency'           => 'INR',
                'subtotal'           => $subtotal,
                'discount_total'     => $discountAmount,
                'tax_total'          => $gst,
                'shipping_total'     => $shipping,
                'payment_status'     => 'Unpaid',
                'payment_method'     => 'Razorpay',
                'razorpay_order_id'  => $razorpayOrder->id,
                'coupon_id'          => $couponId,
                'discount_amount'    => $discountAmount,
            ]);

            if ($couponId) {
                \App\Models\Coupon::where('id', $couponId)->increment('used_count');
            }

            foreach ($cartItems as $item) {
                /** @var \App\Models\CartItem $item */
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->product_name,
                    'quantity'     => $item->quantity,
                    'price'        => $item->product->price,
                    'tax_amount'   => round(($item->product->price * $item->quantity) * (($item->product->gst_rate ?? 18) / 100), 2),
                ]);
                $item->delete(); // Items are removed once order is staged for payment
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Order staging failed. Please try again.'], 500);
        }

        return response()->json([
            'razorpay_order_id' => $razorpayOrder->id,
            'amount'            => (int) round($total * 100),
            'currency'          => 'INR',
            'key'               => config('services.razorpay.key'),
            'user_name'         => Auth::user()->name,
            'user_email'        => Auth::user()->email,
            'user_phone'        => Auth::user()->phone ?? '',
            'order_token'       => $order->encryptedId(),
        ]);
    }

    /**
     * Verify payment signature and update the order status.
     */
    public function verifyPayment(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id'   => 'required|string',
            'razorpay_signature'  => 'required|string',
        ]);

        // Find existing order in DB
        $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
        }

        // Verify signature
        $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

        try {
            $attributes = [
                'razorpay_order_id'   => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ];
            $api->utility->verifyPaymentSignature($attributes);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed. Contact support.'], 400);
        }

        // Update the order status to PAID
        try {
            $order->update([
                'status'              => 'Processing',
                'payment_status'      => 'Paid',
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature'  => $request->razorpay_signature,
            ]);

            // Send Confirmation Email
            try {
                Mail::to(Auth::user()->email)->send(new OrderConfirmed($order));
            } catch (\Exception $e) {
                \Log::error('Order Confirmation Email failed for order ' . $order->order_id_string . ': ' . $e->getMessage());
            }

            return response()->json([
                'success'    => true,
                'message'    => 'Order verified successfully!',
                'order_id'   => $order->order_id_string,
                'redirect'   => route('checkout.success', ['token' => $order->encryptedId(), 'locale' => app()->getLocale()]),
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Order update failed. Contact support.'], 500);
        }
    }

    /**
     * Show success page after order placement.
     */
    public function success($locale, $token)
    {
        $orderId = \App\Models\Order::decryptOrderId($token);
        if (!$orderId) abort(404);

        $order = \App\Models\Order::with(['items', 'address'])->findOrFail($orderId);
        
        if ($order->user_id !== \Auth::id()) abort(403);

        return view('public.success', compact('order'));
    }
}
