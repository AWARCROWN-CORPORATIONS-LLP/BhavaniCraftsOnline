<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeOrderController extends Controller
{
    /**
     * Display a listing of all orders for the employee.
     */
    public function index()
    {
        $orders = Order::with(['user', 'items'])->orderBy('ordered_date', 'desc')->paginate(15);
        return view('employee.orders.list', compact('orders'));
    }

    /**
     * Display the specific order details.
     */
    public function show(string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);
        $order->load(['user', 'items.product', 'items', 'assignedLogistics']);

        $logisticsPersonnel = User::whereHas('roles', function($q) {
            $q->where('name', 'logistics');
        })->where('is_blocked', false)->get();

        return view('employee.orders.show', compact('order', 'logisticsPersonnel'));
    }

    /**
     * Update order and payment status.
     */
    public function updateStatus(Request $request, string $token)
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

        if ($request->has('assigned_logistics_id')) {
            $updateData['assigned_logistics_id'] = $request->assigned_logistics_id;
            // Also transition delivery status automatically to pending if just assigned
            if ($request->assigned_logistics_id && $order->delivery_status === null) {
                $updateData['delivery_status'] = 'Pending';
            }
        }

        $order->update($updateData);

        return redirect()->back()->with('success', 'Order status for ' . $order->order_id_string . ' has been updated.');
    }
}
