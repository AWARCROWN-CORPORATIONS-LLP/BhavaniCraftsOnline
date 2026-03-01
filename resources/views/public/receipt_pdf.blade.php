<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sacred Receipt - {{ $order->order_id_string }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; line-height: 1.6; }
        .receipt-container { padding: 40px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #ff9933; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #ff9933; }
        .order-info { margin-top: 30px; }
        .info-grid { width: 100%; margin-bottom: 30px; }
        .info-grid td { vertical-align: top; width: 50%; }
        .section-title { font-size: 10px; font-weight: bold; text-transform: uppercase; color: #999; margin-bottom: 5px; }
        .item-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .item-table th { background: #f8f8f8; color: #666; font-size: 10px; text-transform: uppercase; padding: 12px; text-align: left; }
        .item-table td { padding: 12px; border-bottom: 1px solid #eee; font-size: 12px; }
        .totals { margin-top: 30px; float: right; width: 250px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 12px; }
        .grand-total { border-top: 2px solid #ff9933; margin-top: 10px; padding-top: 10px; font-weight: bold; font-size: 16px; display: block; }
        .footer { margin-top: 100px; text-align: center; font-size: 10px; color: #aaa; border-top: 1px solid #eee; padding-top: 20px; }
        .thank-you { font-family: 'Times New Roman', serif; font-style: italic; font-size: 18px; color: #ff9933; margin-top: 40px; text-align: center; }
    </style>
</head>
<body>
    <div class="receipt-container">
        <table width="100%">
            <tr>
                <td><div class="logo">BHAVANI CRAFTS</div></td>
                <td align="right">
                    <div style="font-size: 18px; font-weight: bold;">SACRED RECEIPT</div>
                    <div style="font-size: 12px; color: #666;">ID: {{ $order->order_id_string }}</div>
                </td>
            </tr>
        </table>

        <div class="order-info">
            <table class="info-grid">
                <tr>
                    <td>
                        <div class="section-title">Seeker Details</div>
                        <div style="font-weight: bold; font-size: 14px;">{{ $order->user->name }}</div>
                        <div style="font-size: 12px;">{{ $order->user->email }}</div>
                        <div style="font-size: 12px;">{{ $order->user->phone }}</div>
                    </td>
                    <td>
                        <div class="section-title">Ritual Date</div>
                        <div style="font-size: 12px;">{{ $order->created_at->format('M d, Y | h:i A') }}</div>
                        <div class="section-title" style="margin-top: 15px;">Sanctified Address</div>
                        <div style="font-size: 11px; line-height: 1.4;">
                            {{ $order->address->first_name }} {{ $order->address->last_name }}<br>
                            {{ $order->address->address_line1 }}<br>
                            @if($order->address->address_line2) {{ $order->address->address_line2 }}<br> @endif
                            {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}<br>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="section-title">Blessing Manifested (Order Details)</div>
            <table class="item-table">
                <thead>
                    <tr>
                        <th>Artifact Description</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th align="right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td>₹{{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td align="right">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals" style="width: 100%;">
                <table align="right" width="250">
                    <tr>
                        <td align="left" style="font-size: 12px; color: #666;">Subtotal</td>
                        <td align="right" style="font-size: 12px; font-weight: bold;">₹{{ number_format($order->subtotal, 2) }}</td>
                    </tr>
                    @if($order->discount_total > 0)
                    <tr>
                        <td align="left" style="font-size: 12px; color: #16a34a;">Sacred Discount</td>
                        <td align="right" style="font-size: 12px; font-weight: bold; color: #16a34a;">- ₹{{ number_format($order->discount_total, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td align="left" style="font-size: 12px; color: #666;">GST (18%)</td>
                        <td align="right" style="font-size: 12px; font-weight: bold;">₹{{ number_format($order->tax_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td align="left" style="font-size: 12px; color: #666;">Sacred Shipping</td>
                        <td align="right" style="font-size: 12px; font-weight: bold;">{{ $order->shipping_total == 0 ? 'FREE' : '₹' . number_format($order->shipping_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><div class="grand-total">
                            <table width="100%">
                                <tr>
                                    <td>GRAND TOTAL</td>
                                    <td align="right">₹{{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                            </table>
                        </div></td>
                    </tr>
                </table>
                <div style="clear: both;"></div>
            </div>

            <div style="margin-top: 50px;">
                <div class="section-title">Sacred Transaction Flow</div>
                <table width="100%">
                    <tr>
                        <td style="font-size: 11px;">
                            <strong>METHOD:</strong> {{ $order->payment_method ?? 'Razorpay' }}<br>
                            <strong>STATUS:</strong> {{ strtoupper($order->payment_status) }}
                        </td>
                        <td align="right" style="font-size: 11px;">
                            <strong>TXN ID:</strong> {{ $order->razorpay_payment_id ?? 'N/A' }}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="thank-you">
                "May these sacred artifacts bring divine harmony to your space."<br>
                Thank you for being part of our journey.
            </div>
        </div>

        <div class="footer">
            BHAVANI CRAFTS | Handcrafted Spiritual Artifacts | support@bhavanicrafts.com<br>
            Note: This is a computer-generated receipt sanctified for your records.
        </div>
    </div>
</body>
</html>
