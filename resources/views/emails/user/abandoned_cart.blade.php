<x-mail::message>
# You left something behind!

Hi {{ $user->name }},

We noticed you have some beautiful items waiting in your cart. They are selling out fast, so grab them before they're gone!

**Items in your cart:**
@foreach($cartItems as $item)
* {{ $item->product->product_name }} ({{ $item->quantity }}x)
@endforeach

<x-mail::button :url="url('/cart')">
Complete My Purchase
</x-mail::button>

We are here to help if you have any questions.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
