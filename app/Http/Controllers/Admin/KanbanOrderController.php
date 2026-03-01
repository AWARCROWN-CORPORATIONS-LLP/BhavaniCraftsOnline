<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class KanbanOrderController extends Controller
{
    public function index()
    {
        // Group orders by status
        $pendingOrders = Order::where('status', 'Pending')->orderBy('ordered_date', 'desc')->get();
        $processingOrders = Order::where('status', 'Processing')->orderBy('ordered_date', 'desc')->get();
        $shippedOrders = Order::where('status', 'Shipped')->orderBy('ordered_date', 'desc')->get();
        $deliveredOrders = Order::where('status', 'Delivered')->orderBy('ordered_date', 'desc')->get();

        return view('admin.orders.kanban', compact(
            'pendingOrders', 
            'processingOrders', 
            'shippedOrders', 
            'deliveredOrders'
        ));
    }

    public function updateStatus(Request $request, string $token)
    {
        $id = \App\Models\Order::decryptOrderId($token);
        if (!$id) abort(404);
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Pending,Processing,Shipped,Delivered'
        ]);

        $order->status = $request->status;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Artifact Fulfillment Status Updated']);
    }
}
