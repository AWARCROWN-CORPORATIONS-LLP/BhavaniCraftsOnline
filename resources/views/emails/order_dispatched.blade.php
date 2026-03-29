@extends('emails.layout')
@section('email_title', 'Order Dispatched')
@section('email_content')
    <h2 style="font-size: 14px; font-weight: 900; color: #c62828; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 24px;">Artifact Dispatched</h2>
    <p style="font-size: 14px; color: #475569; line-height: 1.8; margin-bottom: 20px;">Dear {{ $order->customer_name }},</p>
    <p style="font-size: 14px; color: #475569; line-height: 1.8; margin-bottom: 30px;">Your sacred artifact <strong style="color: #111111;">#{{ $order->order_number }}</strong> has begun its journey. We have meticulously fortified its packaging to ensure it reaches your doorstep in pristine condition.</p>
    
    <div style="background-color: #f0fdf4; padding: 30px; border-radius: 16px; margin-bottom: 40px; border: 1px solid #dcfce7;">
        <p style="font-size: 10px; font-weight: 800; color: #16a34a; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 12px; margin-top: 0;">Arrival Estimate</p>
        <span style="font-size: 20px; font-weight: 900; color: #111111;">5 - 7 Business Days</span>
    </div>

    <a href="{{ route('customer.orders.show', $order->token, true) }}" style="display: inline-block; background-color: #111111; color: #ffffff; padding: 16px 36px; border-radius: 12px; font-size: 12px; font-weight: 900; text-decoration: none; text-transform: uppercase; letter-spacing: 3px; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">Track Artifact</a>
@endsection
