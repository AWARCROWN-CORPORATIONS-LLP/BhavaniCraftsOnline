@extends('customer.layout')

@section('customer_content')
<div x-data="{ showSafetyModal: false }" class="space-y-8 animate-fadeIn">

    <!-- Order Header -->
    <div class="bg-onyx-900 rounded-[2rem] p-8 text-white relative overflow-hidden shadow-2xl">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <a href="{{ route('customer.orders') }}" class="flex items-center space-x-2 text-white/50 hover:text-white transition-colors mb-4 text-[10px] font-black uppercase tracking-widest">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    <span>All Orders</span>
                </a>
                <h2 class="text-2xl font-black italic mb-1">Order #{{ $order->order_id_string }}</h2>
                <div class="flex flex-wrap items-center gap-4 mt-2">
                    <p class="text-white/50 text-xs font-medium">
                        Placed on {{ $order->ordered_date ? $order->ordered_date->format('d M Y, h:i A') : ($order->created_at?->format('d M Y, h:i A') ?? 'N/A') }}
                    </p>
                    <a href="{{ route('orders.invoice', $order->encryptedId()) }}" data-turbo="false" class="flex items-center space-x-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl transition-all border border-white/10 text-[9px] font-black uppercase tracking-widest text-brand-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        <span>Official Invoice</span>
                    </a>
                </div>
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
                      onsubmit="return confirm('Are you sure you want to cancel this order?')">
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

    <!-- NEW: SECURE DELIVERY AUTHENTICATION -->
    @if(in_array($order->status, ['Processing', 'In Transit', 'Out for Delivery']))
    <div class="mt-8 p-10 bg-onyx-900 rounded-[3rem] text-white shadow-2xl shadow-onyx-900/30 relative overflow-hidden group">
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-8">
                <div class="flex flex-col">
                    <h3 class="text-xs font-black uppercase tracking-[4px] text-brand-500">Delivery Verification</h3>
                    <p class="text-[9px] font-bold text-white/40 mt-1 uppercase">Share this PIN only with the verified delivery person</p>
                </div>
                <div class="h-12 w-12 bg-brand-500/10 rounded-2xl flex items-center justify-center border border-brand-500/20">
                    <svg class="h-6 w-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12l-6-10-6 10zm6-8V7a1 1 0 00-1-1H7a1 1 0 00-1 1v4h12V7a1 1 0 00-1-1H9" /></svg>
                </div>
            </div>

            @if($order->delivery_latitude && $order->delivery_longitude)
                <div class="mb-8 p-6 bg-white/5 rounded-2xl border border-white/10 flex items-center justify-between">
                    <div>
                        <span class="text-[9px] font-black uppercase text-brand-500 tracking-widest">Live Tracking</span>
                        <p class="text-xs font-bold text-white mt-1 uppercase">Delivery person location found</p>
                    </div>
                    <a href="https://www.google.com/maps?q={{ $order->delivery_latitude }},{{ $order->delivery_longitude }}" target="_blank" class="px-6 py-3 bg-brand-500 hover:bg-brand-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-transform hover:scale-[1.05] flex items-center space-x-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>Locate on Map</span>
                    </a>
                </div>
            @endif

            @if($order->delivery_pin)
                <div class="bg-white/5 p-8 rounded-[2rem] border border-white/10 flex flex-col md:flex-row items-center justify-between gap-6 backdrop-blur-sm">
                    <div class="flex flex-col text-center md:text-left">
                        <span class="text-[9px] font-black uppercase text-white/30 tracking-widest">Active Delivery PIN</span>
                        <span class="text-5xl font-black tracking-[1rem] mt-3 text-brand-400 font-mono">{{ $order->delivery_pin }}</span>
                    </div>
                    <div class="text-center md:text-right border-t md:border-t-0 md:border-l border-white/10 pt-6 md:pt-0 md:pl-10">
                        <span class="text-[9px] font-black uppercase text-white/30 tracking-widest">Tries Remaining</span>
                        <span class="block text-2xl font-black mt-2 text-white">{{ 3 - $order->pin_generations_count }} <span class="text-[10px] text-white/30 font-bold uppercase tracking-widest ml-1">Left</span></span>
                    </div>
                </div>
                
                @if($order->pin_generations_count < 3)
                    <form action="{{ route('customer.orders.generate_pin', $order->encryptedId()) }}" method="POST" class="mt-8 text-center" onsubmit="return confirm('Warning: You can only generate a secure PIN 3 times in total. Continue with regeneration?')">
                        @csrf
                        <button type="submit" class="text-[9px] font-black uppercase tracking-[3px] text-white/40 hover:text-brand-400 transition-all border-b border-white/5 hover:border-brand-400/50 pb-2">
                            Regenerate Secure PIN
                        </button>
                    </form>
                @endif
            @else
                <form action="{{ route('customer.orders.generate_pin', $order->encryptedId()) }}" method="POST" onsubmit="return confirm('Note: For your security, you can only generate a unique PIN 3 times for this order. Ensure you share it with the verified agent only. Initialize?')">
                    @csrf
                    <button type="submit" class="w-full py-6 bg-brand-500 text-white rounded-[1.5rem] text-[11px] font-black uppercase tracking-[4px] shadow-xl shadow-brand-500/20 hover:bg-brand-600 hover:-translate-y-1 transition-all active:scale-95 group">
                        <span class="flex items-center justify-center space-x-3">
                            <span>Generate Delivery PIN</span>
                            <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </span>
                    </button>
                </form>
            @endif
            
            @if($order->pin_generations_count >= 3)
                <div class="mt-8 p-4 bg-red-400/10 border border-red-400/20 rounded-xl">
                    <p class="text-[9px] font-black text-red-400 uppercase tracking-widest text-center italic leading-loose">Security: Last PIN generated. Please verify delivery now.</p>
                </div>
            @endif
        </div>
        
        <!-- Animated Background Decor -->
        <div class="absolute -right-20 -bottom-20 h-64 w-64 bg-brand-500/10 rounded-full blur-[80px] group-hover:bg-brand-500/20 transition-all duration-700"></div>
    </div>
    @endif

    <!-- TRACKING & CONFIRMATION HUB (For Post Office / Outside Partners) -->
    @if($order->shipping_partner && in_array($order->status, ['Shipped', 'In Transit', 'Out for Delivery']))
    <div class="p-10 bg-white rounded-[2.5rem] shadow-xl border border-gray-100 relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="space-y-2">
                <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500">Shipping Update</span>
                <h3 class="text-xl font-black text-onyx-900">Your order is with <span class="text-brand-500">{{ $order->shipping_partner }}</span></h3>
                <div class="flex items-center space-x-4 mt-4">
                    <div class="px-4 py-2 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[9px] font-black uppercase text-gray-400 block">AWB / Tracking ID</span>
                        <span class="text-xs font-black text-onyx-900">{{ $order->tracking_number ?? 'Awaiting ID' }}</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                @if($order->shipping_partner == 'India Post' && $order->tracking_number)
                <a href="https://www.indiapost.gov.in/_layouts/15/dop.portal.tracking/trackconsignment.aspx?txtConsignmentNo={{ $order->tracking_number }}" target="_blank" 
                   class="px-8 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-red-700 transition-all shadow-lg shadow-red-600/20">
                    Track via India Post
                </a>
                @endif
                
                <form action="{{ route('customer.orders.confirm', $order->encryptedId()) }}" method="POST" onsubmit="return confirm('Please confirm only if you have physically received the package.')">
                    @csrf
                    <button type="submit" class="px-8 py-4 bg-brand-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20">
                        Confirm Delivery Receipt
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- NEW: DELIVERY AGENT IDENTITY & SAFETY PROTOCOL -->
    @if($order->assignedLogistics)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Agent Identity -->
        <div class="p-8 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="h-20 w-20 rounded-2xl bg-brand-50 flex items-center justify-center border border-brand-100 shrink-0">
                <svg class="h-10 w-10 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <div class="flex-1">
                <span class="text-[9px] font-black uppercase text-brand-500 tracking-widest">Assigned Delivery Agent</span>
                <h4 class="text-lg font-black text-onyx-900 mt-1">{{ $order->assignedLogistics->name }}</h4>
                <p class="text-[11px] text-gray-500 font-medium">Agent ID: BCM-AGT-{{ str_pad($order->assignedLogistics->id, 4, '0', STR_PAD_LEFT) }}</p>
                <div class="mt-4 flex space-x-3">
                    <a href="tel:{{ $order->assignedLogistics->phone }}" class="px-4 py-2 bg-onyx-900 text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-brand-500 transition-all shadow-md">Call Agent</a>
                </div>
            </div>
        </div>

        <!-- Safety Compliant Section -->
        <div class="p-8 bg-red-50 rounded-[2.5rem] border border-red-100 flex flex-col justify-center">
            <div class="flex items-start space-x-4">
                <div class="h-10 w-10 bg-red-500 text-white rounded-xl flex items-center justify-center shrink-0 shadow-lg shadow-red-500/20">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-red-900 uppercase tracking-wide">Found a Safety Issue?</h4>
                    <p class="text-[10px] text-red-700 mt-1 font-medium leading-relaxed">If you experience any misconduct, safety issues, or unprofessional behavior, report it to our support team.</p>
                    <button @click="showSafetyModal = true" class="mt-4 text-[9px] font-black uppercase tracking-widest text-red-600 border-b border-red-200 pb-1 hover:text-red-800 transition-colors">Report Safety Issue</button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif

    @if(in_array($order->status, ['Delivered']))
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- RATING CARD -->
        <div class="p-8 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <h3 class="text-xs font-black uppercase tracking-[3px] text-gray-400 mb-6">Rate Delivery Experience</h3>
            @if($order->delivery_rating)
                <div class="flex items-center space-x-2">
                    @for($i=1; $i<=5; $i++)
                        <svg class="h-8 w-8 {{ $i <= $order->delivery_rating ? 'text-amber-400' : 'text-gray-100' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    @endfor
                </div>
                <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mt-4">Feedback Archived</p>
            @else
                <form action="{{ route('customer.orders.rate', $order->encryptedId()) }}" method="POST" class="flex flex-col items-center">
                    @csrf
                    <div class="flex items-center space-x-2 group">
                        @for($i=1; $i<=5; $i++)
                            <button type="submit" name="rating" value="{{ $i }}" class="hover:scale-125 transition-transform">
                                <svg class="h-8 w-8 text-gray-100 hover:text-amber-400 transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            </button>
                        @endfor
                    </div>
                </form>
            @endif
        </div>

        <!-- RETURN CARD -->
        <div class="p-8 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col">
            <h3 class="text-xs font-black uppercase tracking-[3px] text-gray-400 mb-6 text-center">Return Items</h3>
            @if($order->status == 'Returned')
                 <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100 flex flex-col items-center text-center">
                    <span class="text-[10px] font-black uppercase text-amber-600 tracking-widest mb-2">Return Processing</span>
                    <p class="text-[8px] font-bold text-amber-500 italic uppercase">{{ $order->return_reason }}</p>
                 </div>
            @else
                <form action="{{ route('customer.orders.return', $order->encryptedId()) }}" method="POST" onsubmit="return confirm('Initiate Return Protocol? This will alert our logistics team.')">
                    @csrf
                    <textarea name="reason" required placeholder="Tell us why you want to return the items..." 
                              class="w-full h-24 bg-gray-50 border-none rounded-2xl p-4 text-[10px] font-medium focus:ring-2 focus:ring-gray-100 mb-4"></textarea>
                    <button type="submit" class="w-full py-4 bg-red-50 text-red-500 rounded-xl text-[9px] font-black uppercase tracking-[2px] border border-red-100 hover:bg-red-500 hover:text-white transition-all">Submit Return Request</button>
                </form>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Items -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Order Items -->
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-50">
                    <h3 class="text-xs font-black uppercase tracking-widest text-onyx-900">Order Items</h3>
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
                <h3 class="text-xs font-black uppercase tracking-widest text-onyx-900 mb-6">Delivery Address</h3>
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
                <h3 class="text-xs font-black uppercase tracking-widest text-onyx-900 mb-6">Price Details</h3>
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
</div>

<!-- Safety Complaint Modal -->
<div x-show="showSafetyModal" x-cloak
     class="fixed inset-0 z-[100] overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-onyx-900/80 backdrop-blur-md" @click="showSafetyModal = false"></div>

        <div class="inline-block w-full max-w-lg my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2.5rem] border border-red-100">
            <div class="p-10">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[4px] text-red-500 block mb-2">Safety Issue</span>
                        <h2 class="text-2xl font-serif font-bold text-onyx-900 italic">Report <span class="text-red-500">Issue</span></h2>
                    </div>
                    <button @click="showSafetyModal = false" class="h-10 w-10 flex items-center justify-center rounded-full bg-red-50 text-red-400 hover:text-red-600 transition-all">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('customer.orders.safety_complaint', $order->encryptedId()) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Type of Issue</label>
                        <select name="complaint_type" required class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-red-500 focus:border-red-500 transition-all">
                            <option value="">Select Issue Type</option>
                            <option value="Misconduct">Personal Misconduct</option>
                            <option value="Safety Protocol">Safety Protocol Violation</option>
                            <option value="Unprofessional">Unprofessional Behavior</option>
                            <option value="Harassment">Harassment / Threat</option>
                            <option value="Other">Other Security Concern</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Detailed Description</label>
                        <textarea name="description" required placeholder="Describe what happened so our support team can help..."
                                  class="w-full h-32 bg-gray-50 border-transparent rounded-2xl p-6 text-sm font-medium text-onyx-900 focus:bg-white focus:ring-red-500 focus:border-red-500 transition-all"></textarea>
                    </div>

                    <input type="hidden" name="assigned_logistics_id" value="{{ $order->assigned_logistics_id }}">

                    <div class="pt-4">
                        <button type="submit" class="w-full h-16 bg-red-500 text-white text-[11px] font-black uppercase tracking-[3px] rounded-2xl shadow-xl shadow-red-500/20 hover:bg-red-600 transition-all transform active:scale-95">
                            Submit Report
                        </button>
                        <p class="text-center text-[9px] text-gray-400 font-medium mt-4 italic">
                            Report will be sent to our support team for immediate investigation.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
