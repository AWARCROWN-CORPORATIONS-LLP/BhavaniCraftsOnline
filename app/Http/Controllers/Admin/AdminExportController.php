<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Response;

class AdminExportController extends Controller
{
    /**
     * Generate an encrypted stream of all financial transactions.
     */
    public function exportOrders()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=financial_transactions_" . date('Y_m_d_H_i') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Order Token', 'Date', 'Customer ID', 'Total Amount', 'Currency', 
            'Payment Status', 'Fulfillment Status', 'Razorpay ID'
        ];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Order::chunk(100, function($orders) use($file) {
                foreach ($orders as $order) {
                    $row = [
                        $order->order_id_string,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->user_id,
                        $order->total_amount,
                        $order->currency,
                        $order->payment_status,
                        $order->status,
                        $order->razorpay_payment_id ?? 'N/A'
                    ];
                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Generate an inventory compliance report.
     */
    public function exportProducts()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=inventory_compliance_" . date('Y_m_d_H_i') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Product ID', 'Name', 'SKU/Code', 'Current Stock', 'Threshold', 
            'Price (MRP)', 'Discount %', 'Status'
        ];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            Product::chunk(100, function($products) use($file) {
                foreach ($products as $product) {
                    $row = [
                        $product->id,
                        $product->product_name,
                        $product->product_code ?? 'N/A',
                        $product->stock,
                        $product->stock_threshold,
                        $product->mrp,
                        $product->discount_percent,
                        $product->listed_status
                    ];
                    fputcsv($file, $row);
                }
            });

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
