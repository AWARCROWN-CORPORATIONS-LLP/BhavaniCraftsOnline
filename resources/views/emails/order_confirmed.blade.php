@extends('emails.layout')
@section('email_title', 'Order Confirmed')
@section('email_content')
    <h2 style="font-size: 14px; font-weight: 900; color: #1e40af; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 24px;">Sacred Confirmation</h2>
    <p style="font-size: 14px; color: #475569; line-height: 1.8; margin-bottom: 20px;">Dear {{ $order->customer_name }},</p>
    <p style="font-size: 14px; color: #475569; line-height: 1.8; margin-bottom: 30px;">Your devotion has reached us. We are honored to confirm your order <strong style="color: #111111;">#{{ $order->order_number }}</strong>. Our artisans are already preparing your sacred artifacts for their journey into your home.</p>
    
    <div style="background-color: #f8fafc; padding: 30px; border-radius: 16px; margin-bottom: 40px; border: 1px solid #f1f5f9;">
        <p style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 12px; margin-top: 0;">Order Summary</p>
        <span style="font-size: 24px; font-weight: 900; color: #111111;">₹{{ number_format($order->total_amount, 2) }}</span>
    </div>

    <a href="{{ route('customer.orders.show', $order->token, true) }}" style="display: inline-block; background-color: #c62828; color: #ffffff; padding: 16px 36px; border-radius: 12px; font-size: 12px; font-weight: 900; text-decoration: none; text-transform: uppercase; letter-spacing: 3px; box-shadow: 0 10px 20px rgba(198, 40, 40, 0.2);">Track Journey</a>
@endsection
