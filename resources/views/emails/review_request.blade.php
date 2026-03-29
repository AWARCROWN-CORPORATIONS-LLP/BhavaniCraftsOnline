@extends('emails.layout')
@section('email_title', 'The Final Word')
@section('email_content')
    <h2 style="font-size: 14px; font-weight: 900; color: #1e40af; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 24px;">Sacred Feedback</h2>
    <p style="font-size: 14px; color: #475569; line-height: 1.8; margin-bottom: 20px;">Dear {{ $order->customer_name }},</p>
    <p style="font-size: 14px; color: #475569; line-height: 1.8; margin-bottom: 30px;">We hope your sacred artifact has brought a touch of heritage and devotion to your home. Feedback from our community means the world to our artisans.</p>
    
    <div style="text-align: center; margin-bottom: 40px; padding: 40px; background-color: #fafafa; border-radius: 24px; border: 1px dashed #e2e8f0;">
        <p style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 24px; margin-top: 0;">How was your experience?</p>
        <div style="font-size: 32px; letter-spacing: 12px;">⭐⭐⭐⭐⭐</div>
    </div>

    <div style="text-align: center;">
        <a href="{{ route('customer.orders.show', $order->token, true) }}?review=true" style="display: inline-block; background-color: #1e40af; color: #ffffff; padding: 16px 36px; border-radius: 12px; font-size: 12px; font-weight: 900; text-decoration: none; text-transform: uppercase; letter-spacing: 3px; box-shadow: 0 10px 20px rgba(30, 64, 175, 0.2);">Share My Review</a>
    </div>
@endsection
