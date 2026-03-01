@extends('customer.layout')

@section('customer_content')
<div class="space-y-8 animate-fadeIn">

    <!-- Order Header -->
    <div class="bg-onyx-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('customer.orders') }}" class="flex items-center space-x-2 text-white/50 hover:text-white transition-colors mb-4 text-[10px] font-black uppercase tracking-widest">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>All Orders</span>
                </a>
                <h2 class="text-2xl font-black italic mb-1">Registry #{{ $order->order_id_string }}</h2>
                <p class="text-white/50 text-xs font-medium">
                    Placed on {{ $order->ordered_date ? $order->ordered_date->format('d M Y, h:i A') : ($order->created_at?->format('d M Y, h:i A') ?? 'N/A') }}
                </p>
            </div>
            <div class="flex items-center space-x-4">
                @php
                    $statusColors = [
                        'Processing' => 'bg-yellow-100 text-yellow-700',
                        'Shipped'    => 'bg-blue-100 text-blue-700',
                        'Delivered'  => 'bg-green-100 text-green-700',
                        'Cancelled'  => 'bg-red-100 text-red-700',
                        'Returned'   => 'bg-gray-100 text-gray-700',
                    ];
                    $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700';
                @endphp
                <span class="px-5 py-2 {{ $color }} text-[10px] font-black uppercase tracking-[3px] rounded-full">
                    {{ $order->status }}
                </span>

                @if(in_array($order->status, ['Processing']))
                <form action="{{ route('customer.orders.cancel', $order->encryptedId()) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to cancel this sacred order?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-5 py-2 bg-red-500/10 text-red-400 text-[10px] font-black uppercase tracking-[3px] rounded-full hover:bg-red-500 hover:text-white transition-all">
                        Cancel Order
                    </button>
                </form>
                @endif
            </div>
        </div>
        <div class="absolute -right-10 -bottom-10 h-48 w-48 bg-brand-500/10 rounded-full blur-3xl"></div>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Items -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Order Items -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-onyx-900">Sacred Artifacts</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($order->items as $item)
                    <div class="px-8 py-6 flex items-center space-x-5">
                        <div class="h-20 w-20 rounded-2xl overflow-hidden bg-gray-50 shrink-0 border border-gray-100">
                            @php $img = $item->product?->images?->where('is_main', true)->first() ?? $item->product?->images?->first(); @endphp
                            @if($img)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($img->image_url) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-onyx-900 mb-1">{{ $item->product_name }}</p>
                            <p class="text-[10px] text-gray-400 font-medium">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-black text-onyx-900">₹{{ number_format($item->price * $item->quantity, 2) }}</p>
                            @if($item->tax_amount > 0)
                            <p class="text-[10px] text-gray-400 font-medium">+₹{{ number_format($item->tax_amount, 2) }} GST</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Delivery Address -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xs font-black uppercase tracking-widest text-onyx-900 mb-6">Delivery Sanctuary</h3>
                @if($order->address)
                <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                    <p class="text-sm font-black text-onyx-900 mb-1">{{ $order->address->full_name }}</p>
                    <p class="text-[11px] text-gray-500 mb-3">{{ $order->address->phone_number }}</p>
                    <p class="text-[11px] text-gray-500 leading-relaxed">
                        {{ $order->address->address_line1 }},
                        @if($order->address->address_line2) {{ $order->address->address_line2 }}, @endif
                        {{ $order->address->city }}, {{ $order->address->state }} – {{ $order->address->postal_code }},
                        {{ $order->address->country }}
                    </p>
                </div>
                @else
                <p class="text-sm text-gray-400 italic">Address not found.</p>
                @endif
            </div>
        </div>

        <!-- Right: Payment Summary -->
        <div class="space-y-6">
            <!-- Price Breakdown -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xs font-black uppercase tracking-widest text-onyx-900 mb-6">Price Registry</h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-[11px]">
                        <span class="text-gray-400 font-medium">Subtotal</span>
                        <span class="font-bold text-onyx-900">₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->tax_total > 0)
                    <div class="flex justify-between text-[11px]">
                        <span class="text-gray-400 font-medium">GST</span>
                        <span class="font-bold text-onyx-900">₹{{ number_format($order->tax_total, 2) }}</span>
                    </div>
                    @endif
                    @if($order->discount_total > 0)
                    <div class="flex justify-between text-[11px]">
                        <span class="text-gray-400 font-medium">Discount</span>
                        <span class="font-bold text-green-500">-₹{{ number_format($order->discount_total, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-[11px]">
                        <span class="text-gray-400 font-medium">Shipping</span>
                        @if($order->shipping_total == 0)
                            <span class="font-bold text-green-500">FREE</span>
                        @else
                            <span class="font-bold text-onyx-900">₹{{ number_format($order->shipping_total, 2) }}</span>
                        @endif
                    </div>
                    <div class="border-t border-gray-100 pt-4 flex justify-between">
                        <span class="text-sm font-black text-onyx-900 uppercase tracking-widest">Total</span>
                        <span class="text-lg font-black text-onyx-900">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-xs font-black uppercase tracking-widest text-onyx-900 mb-6">Payment Details</h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-[11px]">
                        <span class="text-gray-400 font-medium">Method</span>
                        <span class="font-bold text-onyx-900 uppercase">
                            {{ $order->razorpay_payment_id ? 'Razorpay' : 'Cash on Delivery' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-[11px]">
                        <span class="text-gray-400 font-medium">Payment Status</span>
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                            {{ $order->payment_status === 'Paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $order->payment_status }}
                        </span>
                    </div>
                    @if($order->razorpay_payment_id)
                    <div class="pt-2 border-t border-gray-50">
                        <p class="text-[9px] font-black uppercase text-gray-400 mb-1">Razorpay Payment ID</p>
                        <code class="text-[10px] text-brand-500 font-mono break-all">{{ $order->razorpay_payment_id }}</code>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
