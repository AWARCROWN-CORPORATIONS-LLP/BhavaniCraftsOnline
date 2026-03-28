<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Generate and download the official trade registry invoice for an order.
     */
    public function download(string $token)
    {
        $id = Order::decryptOrderId($token);
        if (!$id) abort(404);
        
        $order = Order::with(['user', 'items', 'address'])->findOrFail($id);
        
        // Security: Only owner or authorized staff can download
        if (auth()->id() !== $order->user_id && !auth()->user()->hasRole('super_admin') && !auth()->user()->hasRole('admin') && !auth()->user()->hasRole('employee')) {
            abort(403, 'Unauthorized access to trade artifacts.');
        }

        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        
        return $pdf->download("Bhavani_Crafts_Invoice_{$order->order_id_string}.pdf");
    }
}
