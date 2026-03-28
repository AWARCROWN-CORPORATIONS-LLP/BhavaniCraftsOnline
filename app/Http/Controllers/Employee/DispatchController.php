<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DispatchController extends Controller
{
    /**
     * Display the Dispatch Center - Ready for Logistics.
     */
    public function index()
    {
        // Only show orders that are Processing/Paid and HAVEN'T been printed yet
        $orders = Order::where(function($q) {
                $q->where('status', 'Processing')->orWhere('payment_status', 'Paid');
            })
            ->whereNull('label_printed_at')
            ->with(['user', 'address'])
            ->orderBy('id', 'asc')
            ->get();

        return view('employee.dispatch.index', compact('orders'));
    }

    /**
     * Generate the Logistics Label and mark as printed in registry.
     */
    public function generateLabel(Order $order)
    {
        // Secure Registry: Only allowed if not already printed or force reprint
        if (!$order->dispatch_id) {
            $order->update([
                'dispatch_id' => 'LOG-' . strtoupper(Str::random(10)),
                'label_printed_at' => now(),
                'status' => 'Processing' // Ensure it's in processing if it was pending
            ]);
            $order->refresh();
        }

        return view('employee.dispatch.label', compact('order'));
    }

    /**
     * View history of printed labels.
     */
    public function history()
    {
        $orders = Order::whereNotNull('label_printed_at')
            ->with(['user', 'address'])
            ->latest('label_printed_at')
            ->paginate(15);

        return view('employee.dispatch.history', compact('orders'));
    }
}
