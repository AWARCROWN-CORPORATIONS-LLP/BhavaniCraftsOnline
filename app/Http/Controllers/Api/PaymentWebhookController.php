<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class PaymentWebhookController extends Controller
{
    /**
     * Handle incoming Razorpay webhooks.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');
        $secret = config('services.razorpay.webhook_secret');

        // 1. Verify the signature for security
        try {
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
            $api->utility->verifyWebhookSignature($payload, $signature, $secret);
        } catch (SignatureVerificationError $e) {
            Log::error('Razorpay Webhook Signature Verification Failed', [
                'error' => $e->getMessage()
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        // 2. Process based on event type
        if ($event === 'order.paid') {
            $this->processOrderPaid($data['payload']);
        } elseif ($event === 'payment.captured') {
            $this->processPaymentCaptured($data['payload']);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Handle order.paid event.
     */
    protected function processOrderPaid($payload)
    {
        $razorpayOrderId = $payload['order']['entity']['id'];
        $razorpayPaymentId = $payload['payment']['entity']['id'] ?? null;
        
        $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();

        if ($order) {
            if ($order->payment_status !== 'Paid') {
                $order->update([
                    'status'              => 'Processing',
                    'payment_status'      => 'Paid',
                    'razorpay_payment_id' => $razorpayPaymentId,
                ]);
                Log::info("Order {$order->order_id_string} confirmed as PAID via Webhook (order.paid).");
            }
        } else {
            Log::warning("Razorpay Webhook (order.paid): Order not found for Razorpay Order ID: {$razorpayOrderId}");
        }
    }

    /**
     * Handle payment.captured event (alternative or fallback).
     */
    protected function processPaymentCaptured($payload)
    {
        $razorpayOrderId = $payload['payment']['entity']['order_id'];
        $razorpayPaymentId = $payload['payment']['entity']['id'];
        
        $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();

        if ($order) {
            if ($order->payment_status !== 'Paid') {
                $order->update([
                    'status'              => 'Processing',
                    'payment_status'      => 'Paid',
                    'razorpay_payment_id' => $razorpayPaymentId,
                ]);
                Log::info("Order {$order->order_id_string} confirmed as PAID via Webhook (payment.captured).");
            }
        }
    }
}
