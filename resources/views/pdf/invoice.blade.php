<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Official Trade Registry - Invoice #{{ $order->order_id_string }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #1a1a1a; padding: 40px; }
        .header { border-bottom: 2px solid #0ea5e9; padding-bottom: 20px; margin-bottom: 30px; }
        .logo { font-size: 24px; font-weight: bold; color: #0ea5e9; text-transform: uppercase; letter-spacing: 2px; }
        .invoice-title { float: right; font-size: 20px; color: #94a3b8; text-transform: uppercase; letter-spacing: 4px; }
        .details-grid { width: 100%; margin-bottom: 40px; }
        .details-col { width: 50%; vertical-align: top; }
        .label { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 4px; }
        .value { font-size: 12px; font-weight: bold; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th { text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; padding: 12px 0; }
        td { padding: 16px 0; font-size: 12px; border-bottom: 1px solid #f1f5f9; }
        .total-section { float: right; width: 300px; }
        .total-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .grand-total { border-top: 2px solid #1a1a1a; padding-top: 12px; font-size: 18px; font-weight: bold; }
        .footer { position: fixed; bottom: 40px; left: 40px; right: 40px; text-align: center; border-top: 1px solid #e2e8f0; padding-top: 20px; font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="logo">Bhavani Crafts</span>
        <span class="invoice-title">Official Trade Artifact</span>
    </div>

    <table class="details-grid">
        <tr>
            <td class="details-col" style="border:none">
                <div class="label">Recipient Seeker</div>
                <div class="value">{{ $order->user->name }}</div>
                <div class="label">Communication Vector</div>
                <div class="value">{{ $order->user->email }}</div>
                <div class="label">Destination Point</div>
                <div class="value">
                    @if($order->address)
                        {{ $order->address->address_line1 }}<br>
                        {{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->postal_code }}
                    @else
                        Extraction point not recorded.
                    @endif
                </div>
            </td>
            <td class="details-col" style="border:none; text-align: right;">
                <div class="label">Registry ID</div>
                <div class="value">#{{ $order->order_id_string }}</div>
                <div class="label">Trade Sequence Date</div>
                <div class="value">{{ $order->ordered_date ? $order->ordered_date->format('M d, Y') : 'N/A' }}</div>
                <div class="label">Clearing Status</div>
                <div class="value" style="color: {{ $order->payment_status === 'Paid' ? '#10b981' : '#f59e0b' }}">
                    {{ strtoupper($order->payment_status) }}
                </div>
            </td>
        </tr>
    </table>

    <table style="border:none">
        <thead>
            <tr>
                <th style="border:none">Artifact Description</th>
                <th style="border:none">Quantity</th>
                <th style="border:none">Unit Valuation</th>
                <th style="border:none; text-align: right;">Total Index</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td style="border-bottom: 1px solid #f1f5f9;">{{ $item->product_name ?? 'Unknown Artifact' }}</td>
                <td style="border-bottom: 1px solid #f1f5f9;">{{ $item->quantity }}</td>
                <td style="border-bottom: 1px solid #f1f5f9;">₹{{ number_format($item->price, 2) }}</td>
                <td style="border-bottom: 1px solid #f1f5f9; text-align: right;">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div style="font-size: 14px; text-align: right; margin-bottom: 10px;">
            <span style="color: #94a3b8; text-transform: uppercase;">Subtotal:</span> 
            <span style="font-weight: bold; margin-left: 20px;">₹{{ number_format($order->total_amount, 2) }}</span>
        </div>
        <div class="grand-total" style="text-align: right;">
            <span style="color: #94a3b8; text-transform: uppercase; font-size: 12px; font-weight: normal;">Grand Total Index:</span> 
            <span style="margin-left: 20px;">₹{{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    <div class="footer">
        This is a cryptographically generated trade registry artifact from Bhavani Crafts. Unauthorized replication is strictly prohibited under sovereign commerce protocols.
    </div>
</body>
</html>
