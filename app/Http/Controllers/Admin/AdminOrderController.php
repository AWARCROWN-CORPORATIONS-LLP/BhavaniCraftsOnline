<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items'])->orderBy('ordered_date', 'desc')->paginate(15);
        return view('admin.orders.list', compact('orders'));
    }

    public function show(string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        $order->load(['user', 'items.product', 'items']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);

        $request->validate([
            'status'         => 'required|in:Processing,Shipped,Delivered,Cancelled,Returned',
            'payment_status' => 'required|in:Pending,Authorized,Paid,Refunded,Failed',
        ]);

        $order->update([
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Master order registry updated.');
    }

    public function destroy(string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order purged from registry.');
    }
}
