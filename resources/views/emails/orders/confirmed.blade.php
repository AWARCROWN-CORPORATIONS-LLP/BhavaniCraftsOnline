@component('mail::message')
# Namaste {{ $order->user->name }},

Thank you for choosing **Bhavani Crafts**. We have successfully received your order for our handcrafted artifacts. Our artisans are now preparing your selection with the utmost care and devotion.

## Order Summary
**Order ID:** #{{ $order->order_id_string }}  
**Date:** {{ $order->created_at->format('jS M Y, h:i A') }}  
**Status:** {{ $order->status }}  
**Payment Method:** {{ $order->payment_method }}

@component('mail::table')
| Item | Qty | Price | Total |
| :--- | :--: | :--- | :--- |
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | {{ App\Helpers\PriceHelper::format($item->price) }} | {{ App\Helpers\PriceHelper::format($item->price * $item->quantity) }} |
@endforeach
@endcomponent

@component('mail::panel')
**Total Amount:** {{ App\Helpers\PriceHelper::format($order->total_amount) }}  
*(Inclusive of all taxes and shipping)*
@endcomponent

### Delivery Information
**Recipient:** {{ $order->address->full_name }}  
**Address:** {{ $order->address->address_line1 }}, {{ $order->address->address_line2 ?? '' }}  
{{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->postal_code }}

@component('mail::button', ['url' => route('checkout.success', ['token' => $order->order_id_string, 'locale' => 'en-in'])])
Track your Order
@endcomponent

If you have any questions, simply reply to this email or contact our Sacred Support team.

Blessings,  
**Bhavani Crafts Team**
@endcomponent
