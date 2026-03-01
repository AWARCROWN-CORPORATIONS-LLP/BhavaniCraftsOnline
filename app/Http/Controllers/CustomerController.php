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
        return view('customer.dashboard', compact('user', 'recentOrders'));
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

        return back()->with('success', 'Sacred profile updated successfully.');
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

        Auth::user()->addresses()->create($data);

        return back()->with('success', 'Ritual address saved.');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        $address->delete();
        return back()->with('success', 'Address removed from registry.');
    }

    public function setDefaultAddress(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);
        Auth::user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return back()->with('success', 'Default sanctuary updated.');
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
        
        $status = $user->wishlist_public ? 'Collection is now public for sharing.' : 'Collection is now private.';
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
}
