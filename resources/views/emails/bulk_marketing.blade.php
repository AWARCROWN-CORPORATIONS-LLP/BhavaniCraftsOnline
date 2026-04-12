@extends('emails.layout')

@section('email_title', $title)

@section('email_content')
    <div style="font-size: 16px; color: #475569; line-height: 1.8; margin-bottom: 24px;">
        {!! nl2br(e($bodyContent)) !!}
    </div>
    
    <div style="text-align: center; margin: 40px 0;">
        <a href="{{ url('/') }}" style="background-color: #111111; color: #ffffff; padding: 18px 32px; text-decoration: none; border-radius: 12px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px;">Visit Bhavani Crafts</a>
    </div>
@endsection
