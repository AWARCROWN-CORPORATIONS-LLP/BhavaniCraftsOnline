@extends('emails.layout')

@section('email_title', 'Welcome to Bhavani Crafts')

@section('email_content')
    <h2 style="font-size: 24px; color: #111111; margin-bottom: 24px;">Thank you for joining us!</h2>
    <p style="font-size: 16px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
        Hello! We are very happy to have you in our community. 
    </p>
    <p style="font-size: 16px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
        At Bhavani Crafts, we love keeping traditions alive. From now on, you will be the first to know about our new Brass items, Pooja sets, and marriage tips.
    </p>
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ url('/') }}" style="background-color: #111111; color: #ffffff; padding: 18px 32px; text-decoration: none; border-radius: 12px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px;">Visit Our Store</a>
    </div>
    <p style="font-size: 14px; color: #94a3b8; font-style: italic;">
        "Bringing holy traditions to your home."
    </p>
@endsection
