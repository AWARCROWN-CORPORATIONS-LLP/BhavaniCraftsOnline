@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Dispatch Registry Audit</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-emerald-500 uppercase tracking-[4px]">Logistics History</p>
    </div>
@endsection

@section('content')

    <div class="flex items-center justify-between mb-10">
        <div class="flex space-x-4">
            <a href="{{ route('employee.dispatch.index') }}" class="px-8 py-4 bg-white text-gray-400 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-gray-100 hover:bg-gray-50 transition-all shadow-sm">Packing Queue</a>
            <a href="{{ route('employee.dispatch.history') }}" class="px-8 py-4 bg-emerald-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest ">Audit History ({{ $orders->total() }})</a>
        </div>
    </div>

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none text-emerald-600">Archived Dispatch Events</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left uppercase tracking-widest">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Order Ref</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Logistics ID</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Recipient Registry</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Registry Status</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Registry Snapshot</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-gray-900">#{{ $order->order_id_string }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-emerald-600">{{ $order->dispatch_id }}</p>
                            <p class="text-[9px] font-bold text-gray-400 mt-1 italic">PRN: {{ $order->label_printed_at->format('d M, H:i') }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-gray-900">{{ $order->user->name ?? 'Sacred Guest' }}</p>
                            <p class="text-[9px] font-bold text-gray-400 mt-1">{{ $order->address->city ?? 'Unknown' }}, {{ $order->address->postal_code ?? '------' }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col space-y-1">
                                <span class="inline-block px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] w-fit
                                    {{ $order->payment_status == 'Paid' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <a href="{{ route('employee.dispatch.label', $order->id) }}" target="_blank" class="text-[10px] font-black uppercase text-emerald-500 hover:text-emerald-700 transition-colors">Reprint Label</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center text-gray-300 italic uppercase font-black text-[10px]">Registry History Empty</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-8 py-6 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>

@endsection
