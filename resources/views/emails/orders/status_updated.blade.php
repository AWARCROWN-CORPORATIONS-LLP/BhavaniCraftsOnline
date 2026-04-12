<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f9f9f9; color: #333; line-height: 1.6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 16px; overflow: hidden; shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #ff9933; padding: 40px 20px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; }
        .content { padding: 40px; }
        .status-badge { display: inline-block; padding: 8px 16px; background: #fff7ed; color: #ff9933; border: 1px solid #ff9933; border-radius: 100px; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
        .tracking-box { background: #f3f4f6; border-radius: 12px; padding: 20px; margin-top: 20px; text-align: center; }
        .tracking-box p { margin: 0 0 10px 0; font-size: 12px; font-weight: bold; color: #666; text-transform: uppercase; }
        .tracking-id { font-family: monospace; font-size: 18px; font-weight: 900; color: #111; display: block; margin-bottom: 15px; }
        .button { display: inline-block; background: #ff9933; color: white; padding: 15px 30px; border-radius: 12px; text-decoration: none; font-weight: bold; font-size: 14px; text-transform: uppercase; transition: all 0.3s; }
        .footer { text-align: center; padding: 30px; font-size: 11px; color: #999; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p style="margin-bottom: 5px; font-size: 11px; font-weight: bold; opacity: 0.8; text-transform: uppercase;">Divinely Crafted Update</p>
            <h1>Bhavani Crafts</h1>
        </div>
        <div class="content">
            <div class="status-badge">{{ $status }}</div>
            <p>Namaste {{ $order->user->name ?? 'Customer' }},</p>
            <p>We are pleased to inform you that your order <strong>#{{ $order->order_id_string }}</strong> has reached a new milestone.</p>
            
            <p>Your current status is: <strong>{{ $status }}</strong></p>

            @if($order->tracking_number)
            <div class="tracking-box">
                <p>Tracking Information ({{ $order->shipping_partner }})</p>
                <span class="tracking-id">{{ $order->tracking_number }}</span>
                @if($order->shipping_partner == 'India Post')
                    <a href="https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?txtConsignmentNo={{ $order->tracking_number }}" class="button" target="_blank">Track on India Post</a>
                @else
                    <a href="{{ route('customer.orders.show', $order->encryptedId()) }}" class="button">View Order Details</a>
                @endif
            </div>
            @else
            <div style="margin-top: 30px; text-align: center;">
                <a href="{{ route('customer.orders.show', $order->encryptedId()) }}" class="button">Track in Dashboard</a>
            </div>
            @endif

            <p style="margin-top: 30px; font-size: 13px;">If you have any questions, please contact our support team at support@bhavanicrafts.com.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Bhavani Crafts Online. All rights reserved.
        </div>
    </div>
</body>
</html>
