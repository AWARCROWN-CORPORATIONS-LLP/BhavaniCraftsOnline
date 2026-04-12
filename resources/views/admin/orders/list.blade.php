@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">All Orders</h2>
        <span class="bg-gray-100 text-gray-400 text-[9px] font-black uppercase tracking-[3px] px-4 py-1.5 rounded-full border border-gray-200">Sales</span>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Order History</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ $orders->total() }} Orders</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Order ID</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Customer</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Amount</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[12px] font-black text-gray-900 uppercase tracking-[2px] mb-1">#{{ $order->order_id_string }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $order->ordered_date->format('d M, Y H:i') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded-lg bg-gray-100 flex items-center justify-center font-black text-[10px] text-gray-400">
                                        {{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black text-gray-800 uppercase leading-none mb-1">{{ $order->user->name ?? 'Guest' }}</p>
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ $order->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-black text-gray-900">₹{{ number_format($order->total_amount, 2) }}</span>
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $order->payment_status }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[2px] 
                                    @if($order->status == 'Delivered') bg-green-50 text-green-600 border border-green-100 
                                    @elseif($order->status == 'Processing') bg-blue-50 text-blue-600 border border-blue-100 
                                    @elseif($order->status == 'Cancelled') bg-red-50 text-red-600 border border-red-100 
                                    @else bg-gray-50 text-gray-400 border border-gray-100 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order->encryptedId()) }}" class="btn-luxury-saffron px-5 py-2 text-[9px] shadow-lg">View Order</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center opacity-30">
                                <h4 class="text-xl font-black uppercase tracking-[10px] text-gray-400">No Orders Found</h4>
                                <p class="text-[10px] font-bold tracking-widest mt-4 uppercase">Waiting for the first order</p>
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-8 border-t border-gray-50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection
