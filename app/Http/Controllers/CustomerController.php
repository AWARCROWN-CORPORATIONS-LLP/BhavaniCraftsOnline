<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)->orderBy('ordered_date', 'desc')->take(5)->get();
        
        $registryGifts = \App\Models\RegistryContribution::whereHas('wishlist', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('wishlist.product')->orderBy('created_at', 'desc')->get();

        return view('customer.dashboard', compact('user', 'recentOrders', 'registryGifts'));
    }

    public function profile()
    {
        return view('customer.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:15',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];

        if ($request->filled('new_password')) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password does not match.']);
            }
            $user->password = Hash::make($data['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses()->latest()->get();
        return view('customer.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'address_type' => 'required|in:home,office,other',
            'is_default' => 'boolean'
        ]);

        if ($data['is_default'] ?? false) {
            Auth::user()->addresses()->update(['is_default' => false]);
        }

        $address = Auth::user()->addresses()->create($data);
        
        if ($request->wantsJson()) {
            return response()->json([
                'success' => 'Address saved successfully.',
                'address' => $address
            ]);
        }

        return back()->with('success', 'Address saved successfully.');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return back()->with('success', 'Address deleted successfully.');
    }

    public function setDefaultAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return back()->with('success', 'Default address updated.');
    }

    public function orders()
    {
        $orders = Order::with(['items.product.images'])->where('user_id', Auth::id())->orderBy('ordered_date', 'desc')->paginate(10);
        return view('customer.orders', compact('orders'));
    }

    public function toggleWishlistSharing(Request $request)
    {
        $user = Auth::user();
        $user->wishlist_public = !$user->wishlist_public;
        
        if ($user->wishlist_public && !$user->wishlist_token) {
            $user->wishlist_token = 'coll_' . Str::random(12);
        }
        
        $user->save();
        
        $status = $user->wishlist_public ? 'Wishlist is now public.' : 'Wishlist is now private.';
        return back()->with('success', $status);
    }

    public function showOrder(string $token)
    {
        $id = \App\Models\Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        if ($order->user_id !== Auth::id()) abort(403);
        $order->load(['items.product.images', 'address']);
        return view('customer.order_detail', compact('order'));
    }

    public function cancelOrder(string $token)
    {
        $id = \App\Models\Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        if ($order->user_id !== Auth::id()) abort(403);

        if (!in_array($order->status, ['Processing'])) {
            return back()->with('error', 'This order cannot be cancelled as it is already ' . strtolower($order->status) . '.');
        }

        $order->update([
            'status'         => 'Cancelled',
            'payment_status' => $order->payment_status === 'Paid' ? 'Refunded' : $order->payment_status,
        ]);

        return back()->with('success', 'Order #' . $order->order_id_string . ' has been cancelled. If paid, a refund will be processed within 5–7 business days.');
    }

    /**
     * Generate a unique 6-digit PIN for order collection verification.
     */
    public function generateDeliveryPin(string $token)
    {
        $id = \App\Models\Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        
        // Security check
        if ($order->user_id !== Auth::id()) abort(403);

        // State check: Only for orders in transit or processing
        if ($order->delivery_status === 'Delivered') {
            return back()->with('error', 'Order already delivered.');
        }

        // Limit check: Max 3 generations
        if ($order->pin_generations_count >= 3) {
            return back()->with('error', 'Pin generation limit (3) exceeded. Please contact support.');
        }

        // Generate 6-digit numeric PIN
        $pin = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $order->update([
            'delivery_pin' => $pin,
            'pin_generations_count' => $order->pin_generations_count + 1,
            'delivery_status' => $order->delivery_status === 'Pending' ? 'In Transit' : $order->delivery_status
        ]);

        return back()->with('success', 'Delivery PIN generated successfully. Share this with the delivery agent.');
    }

    /**
     * Rate the delivery experience from the customer side.
     */
    public function rateOrder(Request $request, string $token)
    {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);
        
        $id = \App\Models\Order::decryptOrderId($token);
        $order = Order::findOrFail($id);
        
        if ($order->user_id !== Auth::id()) abort(403);
        if ($order->delivery_status !== 'Delivered') {
            return back()->with('error', 'Rating is only permitted for finalized deliveries.');
        }

        $order->update(['delivery_rating' => $request->rating]);

        return back()->with('success', 'Thank you for your feedback! Your rating is saved.');
    }

    public function confirmDelivery(string $token)
    {
        $id = \App\Models\Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        
        if ($order->user_id !== Auth::id()) abort(403);

        if ($order->status === 'Delivered') {
            return back()->with('error', 'Order is already marked as delivered.');
        }

        $order->update([
            'status' => 'Delivered',
            'delivery_status' => 'Delivered',
            'delivered_at' => now(),
            'customer_confirmed_at' => now(),
        ]);

        return back()->with('success', 'Thank you! You have confirmed the delivery of your order.');
    }

    /**
     * Initiate a Return Management System (RMS) request.
     */
    public function requestReturn(Request $request, string $token)
    {
        $request->validate(['reason' => 'required|string|max:500']);
        
        $id = \App\Models\Order::decryptOrderId($token);
        $order = Order::findOrFail($id);
        
        if ($order->user_id !== Auth::id()) abort(403);
        
        if ($order->delivery_status !== 'Delivered') {
            return back()->with('error', 'Return requests are only available for delivered items.');
        }

        $order->update([
            'delivery_status' => 'Return Requested',
            'status' => 'Return Requested',
            'return_requested_at' => now(),
            'return_reason' => $request->reason
        ]);

        return back()->with('success', 'Return request received. Our team will contact you for pick-up soon.');
    }

    /**
     * Store a new safety complaint related to an order.
     */
    public function storeSafetyComplaint(Request $request, string $token)
    {
        $id = \App\Models\Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = \App\Models\Order::findOrFail($id);
        
        if ($order->user_id !== \Illuminate\Support\Facades\Auth::id()) abort(403);

        $request->validate([
            'complaint_type' => 'required|string|max:100',
            'description'    => 'required|string|max:1000',
            'assigned_logistics_id' => 'nullable|exists:users,id'
        ]);

        \App\Models\SafetyComplaint::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'order_id' => $order->id,
            'assigned_logistics_id' => $request->assigned_logistics_id,
            'complaint_type' => $request->complaint_type,
            'description' => $request->description,
            'status' => 'Pending'
        ]);

        return back()->with('success', 'Report submitted. Our team will investigate this immediately.');
    }
}
