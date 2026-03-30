<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    /**
     * Generate and download the order receipt as PDF.
     */
    public function download($locale, $token)
    {
        $orderId = Order::decryptOrderId($token);
        if (!$orderId) abort(404);

        $order = Order::with(['items', 'address', 'user'])->findOrFail($orderId);
        
        if ($order->user_id !== Auth::id()) abort(403);

        $pdf = Pdf::loadView('public.receipt_pdf', compact('order'));
        
        return $pdf->download("Receipt-{$order->order_id_string}.pdf");
    }
}
