@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Dispatch Center</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-emerald-500 uppercase tracking-[4px]">Logistics Readiness</p>
    </div>
@endsection

@section('content')

    <div class="flex items-center justify-between mb-10">
        <div class="flex space-x-4">
            <a href="{{ route('employee.dispatch.index') }}" class="px-8 py-4 bg-emerald-500 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-500/20">Pending Dispatch ({{ count($orders) }})</a>
            <a href="{{ route('employee.dispatch.history') }}" class="px-8 py-4 bg-white text-gray-400 rounded-2xl text-[10px] font-black uppercase tracking-widest border border-gray-100 hover:bg-gray-50 transition-all">Audit History</a>
        </div>
    </div>

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none text-emerald-600">Packing Queue</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left uppercase tracking-widest">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Order Ref</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Recipient Registry</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Location Signature</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Registry Status</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Logistics Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-gray-900">#{{ $order->order_id_string }}</p>
                            <p class="text-[9px] font-bold text-gray-400 mt-1 italic">REC: {{ $order->created_at->format('d M, H:i') }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[11px] font-black text-gray-900">{{ $order->user->name ?? 'Sacred Guest' }}</p>
                            <p class="text-[9px] font-bold text-emerald-500 mt-1 lowercase truncate max-w-[150px]">{{ $order->user->email ?? '--' }}</p>
                        </td>
                        <td class="px-8 py-6">
                            @if($order->address)
                                <p class="text-[10px] font-black text-gray-900">{{ $order->address->city }}, {{ $order->address->state }}</p>
                                <p class="text-[8px] font-bold text-gray-400 mt-1">{{ $order->address->postal_code }}</p>
                            @else
                                <span class="text-[10px] text-red-300 italic">Address Corrupted</span>
                            @endif
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
                            <a href="{{ route('employee.dispatch.label', $order->id) }}" target="_blank" class="group flex items-center space-x-3 bg-white border border-gray-100 px-6 py-3 rounded-xl hover:border-emerald-500 transition-all shadow-sm">
                                <svg class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                <span class="text-[9px] font-black uppercase text-emerald-600 tracking-widest">Generate Label</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">Dispatch Registry Clear</p>
                                <p class="text-[9px] text-gray-300 mt-2">All orders have been synchronized with logistics labels.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
