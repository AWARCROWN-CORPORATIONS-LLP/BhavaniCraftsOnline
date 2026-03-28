<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogisticsDashboardController extends Controller
{
    /**
     * Display the dashboard for the logged-in logistics operative.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Ensure user is authorized
        if (!$user->hasRole('logistics')) {
            abort(403, 'Unauthorized access: Not a logistics operative.');
        }

        // Fetch assigned active deliveries (Out for Delivery, Shipped, or Return Requested)
        $activeDeliveries = Order::where('assigned_logistics_id', $user->id)
            ->whereIn('delivery_status', ['Pending', 'In Transit', 'Out for Delivery', 'Return Requested'])
            ->where('status', '!=', 'Cancelled')
            ->orderBy('created_at', 'asc')
            ->get();

        // Fetch completed/failed deliveries for history
        $pastDeliveries = Order::where('assigned_logistics_id', $user->id)
            ->whereIn('delivery_status', ['Delivered', 'Returned', 'Failed'])
            ->orderBy('delivered_at', 'desc')
            ->take(20) // List the latest 20
            ->get();

        return view('shared.logistics.dashboard', compact('user', 'activeDeliveries', 'pastDeliveries'));
    }

    /**
     * Update the logistics status directly (e.g. mark Out for Delivery)
     */
    public function updateDeliveryStatus(Request $request, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        
        $order = Order::findOrFail($id);
        
        // Security check
        if ($order->assigned_logistics_id !== Auth::id()) abort(403);

        $request->validate(['status' => 'required|in:Out for Delivery,In Transit']);

        $order->update([
            'delivery_status' => $request->status
        ]);

        return back()->with('success', "Delivery milestone updated to {$request->status}.");
    }

    /**
     * Display the QR Code Delivery verification terminal.
     */
    public function showVerifyDelivery(string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        
        $order = Order::with(['user', 'shipping_address'])->findOrFail($id);
        
        // Security check
        if ($order->assigned_logistics_id !== Auth::id()) abort(403);
        
        if ($order->delivery_status === 'Delivered') {
            return redirect()->route('logistics.dashboard')->with('error', 'Artifact has already been successfully delivered.');
        }

        return view('shared.logistics.verify', compact('order'));
    }

    /**
     * Process the inputted PIN to confirm order delivery.
     */
    public function processVerifyDelivery(Request $request, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        
        $order = Order::findOrFail($id);
        
        // Security check
        if ($order->assigned_logistics_id !== Auth::id()) abort(403);
        
        $request->validate([
            'delivery_pin' => 'required|string|size:6'
        ]);

        if (!$order->delivery_pin || $order->delivery_pin !== $request->delivery_pin) {
            return back()->with('error', 'Access Denied: Invalid Security Code. Ensure the Recipient generated a new code if expired.');
        }

        // Success logic: Confirm drop-off
        $order->update([
            'delivery_status' => 'Delivered',
            'status' => 'Delivered', // synchronizing the core e-commerce state
            'delivered_at' => now(),
            'delivery_pin' => null, // Void the credential immediately
        ]);

        return redirect()->route('logistics.dashboard')->with('success', 'Authentication OK. Artifact officially marked as Delivered.');
    }
    /**
     * Update the real-time geospatial coordinates for an order.
     */
    public function updateLocation(Request $request, string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) return response()->json(['success' => false, 'message' => 'Invalid registry token.'], 404);
        
        $order = Order::findOrFail($id);
        
        // Security check
        if ($order->assigned_logistics_id !== Auth::id()) return response()->json(['success' => false, 'message' => 'Unauthorized field asset.'], 403);
        
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);
        
        $order->update([
            'delivery_latitude' => $request->latitude,
            'delivery_longitude' => $request->longitude,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Geospatial signature broadcasted.']);
    }
}
