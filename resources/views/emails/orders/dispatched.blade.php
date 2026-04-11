@component('mail::message')
# Your Sacred Artifact is on its way!

Namaste {{ $order->user->name }},

We are delighted to inform you that your order **#{{ $order->order_id_string }}** has been handcrafted, blessed, and is now dispatched. It won't be long before it brings a touch of heritage and devotion to your space.

@component('mail::panel')
**Estimated Delivery:** Within 3-5 business days.
@endcomponent

### Delivery Details
**Recipient:** {{ $order->address->first_name }} {{ $order->address->last_name }}  
**Address:** {{ $order->address->address_line_1 }}, {{ $order->address->city }}, {{ $order->address->state }}

If you have a tracking ID, you can follow your package's journey using the button below.

@component('mail::button', ['url' => route('checkout.success', ['token' => $order->order_id_string, 'locale' => 'en-in'])])
Track your Artifact
@endcomponent

Thank you for supporting traditional Indian craftsmanship.

Blessings,  
**Bhavani Crafts Team**
@endcomponent
