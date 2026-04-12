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
            'tracking_number' => 'nullable|string|max:50',
            'shipping_partner' => 'nullable|string|max:50',
        ]);

        $updateData = [
            'status'         => $request->status,
            'payment_status' => $request->payment_status,
            'tracking_number' => $request->tracking_number,
            'shipping_partner' => $request->shipping_partner,
        ];

        if ($request->has('assigned_logistics_id')) {
            $updateData['assigned_logistics_id'] = $request->assigned_logistics_id;
            if ($request->assigned_logistics_id && $order->delivery_status === null) {
                $updateData['delivery_status'] = 'Pending';
            }
        }

        $oldStatus = $order->status;
        $oldTracking = $order->tracking_number;

        $order->update($updateData);

        if ($oldStatus !== $request->status || ($oldTracking === null && $request->tracking_number !== null)) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->user->email)->queue(new \App\Mail\OrderStatusNotification($order, $request->status));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send order status notification: " . $e->getMessage());
            }
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true, 
                'message' => 'Order registry updated.', 
                'status' => $order->status,
                'payment_status' => $order->payment_status
            ]);
        }

        return redirect()->back()->with('success', 'Master order registry updated.');
    }

    public function destroy($locale, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        
        $order->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Order purged from registry.']);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order purged from registry.');
    }
}
