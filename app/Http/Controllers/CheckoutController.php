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

        $cartItems = CartItem::with(['product.images'])
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('info', 'Your cart is empty.');
        }

        $addresses = Address::where('user_id', Auth::id())->orderByDesc('is_default')->get();

        // Calculate totals
        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $gst      = round($subtotal * 0.18, 2);
        $shipping = $subtotal >= 999 ? 0 : 80;
        $total    = $subtotal + $gst + $shipping;

        return view('public.checkout', compact('cartItems', 'addresses', 'subtotal', 'gst', 'shipping', 'total'));
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

        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Cart is empty.'], 400);
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);
        $gst      = round($subtotal * 0.18, 2);
        $shipping = $subtotal >= 999 ? 0 : 80;
        $total    = $subtotal + $gst + $shipping;

        $paymentMethod = $request->input('payment_method', 'razorpay');

        // ── COD path: place order directly ──────────────────────────────────
        if ($paymentMethod === 'cod') {
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
                    'discount_total'  => 0,
                    'tax_total'       => $gst,
                    'shipping_total'  => $shipping,
                    'payment_status'  => 'Pending',
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $item->product_id,
                        'product_name' => $item->product->product_name,
                        'quantity'     => $item->quantity,
                        'price'        => $item->product->price,
                        'tax_amount'   => round($item->product->price * $item->quantity * 0.18, 2),
                    ]);
                }

                CartItem::where('user_id', Auth::id())->delete();
                DB::commit();

                return response()->json([
                    'success'  => true,
                    'order_id' => $order->order_id_string,
                    'redirect' => route('customer.orders'),
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

        // Temporarily store context in session for verification
        session([
            'checkout_address_id'    => $address->id,
            'checkout_total'         => $total,
            'checkout_subtotal'      => $subtotal,
            'checkout_gst'           => $gst,
            'checkout_shipping'      => $shipping,
            'checkout_razorpay_order' => $razorpayOrder->id,
        ]);

        return response()->json([
            'razorpay_order_id' => $razorpayOrder->id,
            'amount'            => (int) round($total * 100),
            'currency'          => 'INR',
            'key'               => config('services.razorpay.key'),
            'user_name'         => Auth::user()->name,
            'user_email'        => Auth::user()->email,
            'user_phone'        => Auth::user()->phone ?? '',
        ]);
    }

    /**
     * Verify payment signature and place the order.
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

        // Verify session data matches
        if (session('checkout_razorpay_order') !== $request->razorpay_order_id) {
            return response()->json(['success' => false, 'message' => 'Order mismatch detected.'], 400);
        }

        $addressId = session('checkout_address_id');
        $total     = session('checkout_total');
        $subtotal  = session('checkout_subtotal');
        $gst       = session('checkout_gst');
        $shipping  = session('checkout_shipping');

        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();

        // Place order in DB
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id'            => Auth::id(),
                'order_id_string'    => 'BCM-' . strtoupper(Str::random(8)),
                'address_id'         => $addressId,
                'status'             => 'Processing',
                'total_amount'       => $total,
                'currency'           => 'INR',
                'subtotal'           => $subtotal,
                'discount_total'     => 0,
                'tax_total'          => $gst,
                'shipping_total'     => $shipping,
                'payment_status'     => 'Paid',
                'razorpay_order_id'  => $request->razorpay_order_id,
                'razorpay_payment_id'=> $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->product_name,
                    'quantity'     => $item->quantity,
                    'price'        => $item->product->price,
                    'tax_amount'   => round($item->product->price * $item->quantity * 0.18, 2),
                ]);
            }

            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();

            // Clear session checkout data
            session()->forget([
                'checkout_address_id', 'checkout_total', 'checkout_subtotal',
                'checkout_gst', 'checkout_shipping', 'checkout_razorpay_order',
            ]);

            DB::commit();

            return response()->json([
                'success'    => true,
                'message'    => 'Order placed successfully!',
                'order_id'   => $order->order_id_string,
                'redirect'   => route('customer.orders'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Order creation failed. Contact support.'], 500);
        }
    }
}
