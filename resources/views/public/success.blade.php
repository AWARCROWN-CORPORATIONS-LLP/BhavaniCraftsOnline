@extends('layouts.public')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-24 px-6 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-2xl w-full text-center space-y-12">
        <!-- Spiritual Success Icon -->
        <div class="relative inline-block">
           <div class="h-32 w-32 bg-brand-500 rounded-full flex items-center justify-center mx-auto shadow-2xl animate-bounce">
                <svg class="h-16 w-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
             <!--<div class="absolute -top-4 -right-4 h-12 w-12 bg-white rounded-full shadow-lg flex items-center justify-center animate-pulse">
                <span class="text-brand-500 font-black text-xl italic"></span>
            </div>-->
        </div>

        <!-- Thank You Message -->
        <div class="space-y-6">
            <span class="text-[10px] font-black uppercase tracking-[5px] text-brand-500 block mb-2">Sacred Order Manifested</span>
            <h1 class="text-5xl font-black text-onyx-900 tracking-tight italic">Order Confirmed!</h1>
            <p class="text-gray-400 text-sm font-medium leading-relaxed max-w-md mx-auto italic">
                "May these sacred artifacts bring divine harmony to your space."<br>
                Thank you for being part of our journey.
            </p>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-gray-200/50 border border-gray-100 text-left space-y-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 h-40 w-40 bg-gray-50/50 rounded-full -translate-y-20 translate-x-10 group-hover:scale-110 transition-transform duration-1000"></div>
            
            <div class="flex items-center justify-between border-b border-gray-100 pb-8">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Ritual ID</h3>
                    <p class="text-lg font-black text-onyx-900 tracking-widest">{{ $order->order_id_string }}</p>
                </div>
                <div class="text-right">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Status</h3>
                    <span class="px-3 py-1 bg-brand-500 text-white text-[9px] font-black uppercase tracking-widest rounded-full italic">Sanctified</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-10">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 border-l-2 border-brand-500 pl-3">Destiny Points</h3>
                    <p class="text-sm font-bold text-onyx-900 leading-relaxed italic">
                        {{ $order->address->full_name }}<br>
                        {{ $order->address->address_line1 }}<br>
                        {{ $order->address->city }}, {{ $order->address->state }}<br>
                        {{ $order->address->postal_code }}
                    </p>
                </div>
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 border-l-2 border-brand-500 pl-3">Offerings</h3>
                    <div class="space-y-2">
                        @foreach($order->items->take(2) as $item)
                        <div class="flex justify-between items-center text-xs font-medium text-onyx-900 italic">
                            <span>{{ Str::limit($item->product_name, 20) }} x{{ $item->quantity }}</span>
                            <span class="font-bold">₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                        @endforeach
                        @if($order->items->count() > 2)
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest italic">+ {{ $order->items->count() - 2 }} more artifacts</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs font-black uppercase tracking-widest text-onyx-900 italic">Divine Offering Total</span>
                <span class="text-2xl font-black text-onyx-900 italic tracking-tight">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6 pt-6">
            <a href="{{ route('checkout.receipt', ['token' => $order->encryptedId()]) }}" 
               class="w-full sm:w-auto px-10 h-16 bg-onyx-900 text-white text-[11px] font-black uppercase tracking-[4px] rounded-2xl shadow-xl hover:bg-brand-500 transition-all flex items-center justify-center space-x-3">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Download Sacred Receipt</span>
            </a>
            <a href="{{ route('customer.dashboard') }}" 
               class="w-full sm:w-auto px-10 h-16 bg-gray-100 text-onyx-900 text-[11px] font-black uppercase tracking-[4px] rounded-2xl hover:bg-gray-200 transition-all flex items-center justify-center">
                Portal Dashboard
            </a>
        </div>
        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-[4px]">A confirmation blessing has been sent to {{ $order->user->email }}</p>
    </div>
</div>
@endsection
