<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index($locale)
    {
        $orders = Order::with(['user', 'items'])->orderBy('ordered_date', 'desc')->paginate(15);
        return view('admin.orders.list', compact('orders'));
    }

    public function show($locale, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        $order->load(['user', 'items.product', 'items', 'assignedLogistics']);
        
        $logisticsPersonnel = User::whereHas('roles', function($q) {
            $q->where('name', 'logistics');
        })->where('is_blocked', false)->get();

        return view('admin.orders.show', compact('order', 'logisticsPersonnel'));
    }

    public function updateStatus(Request $request, $locale, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);

        $request->validate([
            'status'         => 'required|in:Processing,Shipped,Delivered,Cancelled,Returned,Return Requested',
            'payment_status' => 'required|in:Pending,Authorized,Paid,Refunded,Failed',
            'assigned_logistics_id' => 'nullable|exists:users,id',
        ]);

        $updateData = [
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
        ];

        // Only update assignment if provided, allows setting to null or specific ID
        if ($request->has('assigned_logistics_id')) {
            $updateData['assigned_logistics_id'] = $request->assigned_logistics_id;
            // Also transition delivery status automatically to pending if just assigned
            if ($request->assigned_logistics_id && $order->delivery_status === null) {
                $updateData['delivery_status'] = 'Pending';
            }
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Master order registry updated.');
    }

    public function destroy($locale, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order purged from registry.');
    }
}
