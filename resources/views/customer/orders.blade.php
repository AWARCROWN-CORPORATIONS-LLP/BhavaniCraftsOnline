@extends('customer.layout')

@section('customer_content')
<div class="space-y-8 animate-fadeIn">
    <div>
        <h2 class="text-2xl font-black text-onyx-900 uppercase tracking-widest leading-none mb-2">Sacred Orders</h2>
        <p class="text-sm text-gray-400 font-medium">Trace the journey of your curated artifacts.</p>
    </div>

    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-medium">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium">{{ session('error') }}</div>
    @endif

    @forelse($orders as $order)
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden group hover:border-brand-500/20 transition-all duration-500">
        <!-- Order Header Bar -->
        <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-8">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Ritual Ordered</p>
                    <p class="text-xs font-bold text-onyx-900">
                        {{ $order->ordered_date ? $order->ordered_date->format('d M, Y') : ($order->created_at?->format('d M, Y') ?? 'N/A') }}
                    </p>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Total Value</p>
                    <p class="text-xs font-black text-onyx-900">₹{{ number_format($order->total_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Payment</p>
                    <span class="text-[9px] font-black px-2 py-0.5 rounded-full uppercase tracking-widest
                        {{ $order->payment_status === 'Paid' ? 'bg-green-100 text-green-700' : ($order->payment_status === 'Pending' ? 'bg-yellow-100 text-yellow-700' : ($order->payment_status === 'Refunded' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600')) }}">
                        {{ $order->payment_status ?? 'Pending' }}
                    </span>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Status</p>
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
                    <span class="px-3 py-1 {{ $color }} text-[9px] font-black uppercase tracking-widest rounded-full">
                        {{ $order->status }}
                    </span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black uppercase tracking-[2px] text-gray-400 mb-1">Registry Code</p>
                <p class="text-xs font-black text-onyx-900">#{{ $order->order_id_string }}</p>
            </div>
        </div>

        <!-- Order Items Preview -->
        <div class="px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <!-- Product thumbnails -->
                    <div class="flex -space-x-3">
                        @foreach($order->items->take(3) as $item)
                        <div class="h-12 w-12 rounded-xl overflow-hidden bg-gray-50 border-2 border-white shadow-sm shrink-0">
                            @php $img = $item->product?->images?->where('is_main', true)->first() ?? $item->product?->images?->first(); @endphp
                            @if($img)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($img->image_url) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                    <svg class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </div>
                            @endif
                        </div>
                        @endforeach
                        @if($order->items->count() > 3)
                        <div class="h-12 w-12 rounded-xl bg-gray-100 border-2 border-white shadow-sm flex items-center justify-center shrink-0">
                            <span class="text-[10px] font-black text-gray-500">+{{ $order->items->count() - 3 }}</span>
                        </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-bold text-onyx-900">
                            {{ $order->items->first()?->product_name ?? 'Order Items' }}
                            @if($order->items->count() > 1) <span class="text-gray-400 font-normal text-xs">+ {{ $order->items->count() - 1 }} more</span> @endif
                        </p>
                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">{{ $order->items->count() }} artifact(s)</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    @if($order->status === 'Processing')
                    <form action="{{ route('customer.orders.cancel', $order->encryptedId()) }}" method="POST"
                          onsubmit="return confirm('Cancel Order #{{ $order->order_id_string }}?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 text-[10px] font-black uppercase tracking-widest text-red-400 border border-red-100 rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all">
                            Cancel
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('customer.orders.show', $order->encryptedId()) }}" 
                       class="px-6 py-2.5 border border-gray-100 text-onyx-900 text-[10px] font-black uppercase tracking-widest rounded-xl hover:border-brand-500 hover:text-brand-500 transition-all">
                        Registry Details →
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="py-20 text-center flex flex-col items-center justify-center bg-white rounded-[2rem] border-2 border-dashed border-gray-100">
        <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
            <svg class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
        </div>
        <p class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">Your ritual history is clear.</p>
        <a href="{{ url('/') }}" class="mt-8 text-[11px] font-black text-brand-500 uppercase tracking-[3px] hover:text-onyx-900 transition-colors">Start Your First Quest</a>
    </div>
    @endforelse

    <div class="mt-8">{{ $orders->links() }}</div>
</div>
@endsection
