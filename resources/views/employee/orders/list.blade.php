@extends('layouts.employee')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight">Orders</h2>
@endsection

@section('content')

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-2xl shadow-gray-200/50 overflow-hidden">
        <div class="p-8 border-b border-gray-50 bg-white sticky top-0 z-10">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-[4px] leading-none mb-2">Order Management</h3>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px]">Full Order History</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Order ID</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Customer</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Payment</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Status</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 uppercase">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-[11px] font-black text-gray-900 tracking-wider">#{{ $order->order_id_string }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[11px] font-bold text-gray-500 tracking-wider">{{ $order->user->name ?? 'Unknown' }}</span>
                        </td>
                        <td class="px-8 py-6">
                             <span class="px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] 
                                {{ $order->payment_status == 'Paid' ? 'bg-green-100 text-green-600' : 
                                   ($order->payment_status == 'Failed' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600') }}">
                                {{ $order->payment_status }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] 
                                {{ $order->status == 'Delivered' ? 'bg-green-100 text-green-600' : 
                                   ($order->status == 'Cancelled' ? 'bg-red-100 text-red-600' : 'bg-sky-100 text-sky-600') }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                             <a href="{{ route('employee.orders.show', $order->encryptedId()) }}" class="text-[10px] font-black uppercase tracking-[3px] text-sky-500 hover:text-sky-700">View Order</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-8 bg-gray-50 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

@endsection
