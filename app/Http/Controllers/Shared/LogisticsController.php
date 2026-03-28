<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    /**
     * Display the secure delivery verification terminal.
     */
    public function verifyForm($token)
    {
        $orderId = Order::decryptOrderId($token);
        if (!$orderId) {
            return abort(404, 'Logistics Identifier Corrupted or Invalid.');
        }

        $order = Order::with(['user', 'address'])->findOrFail($orderId);

        // Security check: If already delivered, show success/info page
        if ($order->delivery_status === 'Delivered') {
            return view('shared.logistics.delivered_status', compact('order'));
        }

        return view('shared.logistics.verify', compact('order', 'token'));
    }

    /**
     * Process the delivery PIN and finalize transaction with advanced proof.
     */
    public function processVerification(Request $request, $token)
    {
        $request->validate([
            'delivery_pin' => 'required|string|size:6',
            'delivery_photo' => 'nullable|image|max:10240', // Max 10MB
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $orderId = Order::decryptOrderId($token);
        if (!$orderId) {
            return back()->with('error', 'Critical Error: Memory registry mismatch.');
        }

        $order = Order::findOrFail($orderId);

        // Security: Prevent bypass of Delivered status
        if ($order->delivery_status === 'Delivered') {
            return back()->with('info', 'Registry Audit: This order is already marked as Delivered.');
        }

        // Verify PIN
        if ($order->delivery_pin === $request->delivery_pin) {
            
            $data = [
                'delivery_status' => 'Delivered',
                'status' => 'Delivered', // Master status sync
                'delivered_at' => now(),
                'delivery_pin' => null, // Invalidate after success
            ];

            // Save Geolocation Signature
            if ($request->filled('latitude') && $request->filled('longitude')) {
                $data['delivery_latitude'] = $request->latitude;
                $data['delivery_longitude'] = $request->longitude;
            }

            // Save Photo Proof
            if ($request->hasFile('delivery_photo')) {
                $path = $request->file('delivery_photo')->store('logistics/proofs', 'public');
                $data['delivery_photo_url'] = $path;
            }

            $order->update($data);

            return redirect()->route('logistics.verify', $token)->with('success', 'Authentication Successful. Delivery Proof & Registry Archived.');
        }

        return back()->with('error', 'Authentication Failed: Invalid Delivery PIN provided by the customer.');
    }

    /**
     * Mark order as Returned/Failed from logistics side with reason auditing.
     */
    public function markFailed(Request $request, $token)
    {
        $orderId = Order::decryptOrderId($token);
        if (!$orderId) abort(404);
        
        $order = Order::findOrFail($orderId);
        $status = $request->input('status', 'Returned');
        $reason = $request->input('reason', 'Logistics Exception');
        
        $order->update([
            'delivery_status' => $status,
            'failed_delivery_reason' => $reason,
            'status' => $status === 'Returned' ? 'Returned' : 'Failed'
        ]);

        return redirect()->route('logistics.verify', $token)->with('warning', "Logistics Event Noted: Order moved to '{$status}' due to '{$reason}'.");
    }
}
