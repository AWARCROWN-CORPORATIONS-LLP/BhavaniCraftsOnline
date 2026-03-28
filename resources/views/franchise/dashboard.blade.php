@extends('layouts.franchise')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight">Store Dashboard</h2>
@endsection

@section('content')

    <!-- STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Total Business Volume -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Total Earnings</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">₹{{ number_format($stats['total_volume'], 2) }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Total Earnings</p>
                <div class="h-10 w-10 bg-[#ff9933]/10 text-[#ff9933] flex items-center justify-center rounded-xl shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </div>
            </div>
        </div>

        <!-- Orders Count -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Total Orders</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['orders_count'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Order History</p>
                <div class="h-10 w-10 bg-gray-50 text-gray-400 flex items-center justify-center rounded-xl shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
            </div>
        </div>

        <!-- Pending Items -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px] bg-gradient-to-br from-white to-gray-50 border-orange-100">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Pending Shipments</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['pending_shipments'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Orders in Queue</p>
                <div class="h-10 w-10 bg-[#ff9933] text-white flex items-center justify-center rounded-xl shadow-lg @if($stats['pending_shipments'] > 0) animate-pulse @endif">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
            </div>
        </div>

        <!-- Catalog Access -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[220px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2 leading-none">Inventory Items</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['inventory_size'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">My Inventory</p>
                <a href="{{ route('franchise.inventory') }}" class="h-10 w-10 bg-[#ff9933]/10 text-[#ff9933] flex items-center justify-center rounded-xl shadow-lg hover:bg-[#ff9933] hover:text-white transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </a>
            </div>
        </div>
    </div>

    @if(isset($activeBroadcasts) && $activeBroadcasts->count() > 0)
        <!-- ACTIVE BROADCAST TICKER -->
        <div class="mt-12 space-y-4">
            @foreach($activeBroadcasts as $broadcast)
                <div class="card-premium p-6 flex flex-col md:flex-row items-center justify-between border-l-4 
                    {{ $broadcast->urgency == 'critical' ? 'border-red-500 bg-red-50/30' : 
                       ($broadcast->urgency == 'warning' ? 'border-amber-500 bg-amber-50/30' : 'border-blue-500 bg-blue-50/30') }}">
                    <div class="flex items-start md:items-center space-x-4 mb-4 md:mb-0">
                        <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-xl 
                            {{ $broadcast->urgency == 'critical' ? 'bg-red-500/10 text-red-500 animate-pulse' : 
                               ($broadcast->urgency == 'warning' ? 'bg-amber-500/10 text-amber-500' : 'bg-blue-500/10 text-blue-500') }}">
                            @if($broadcast->urgency == 'critical')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center space-x-3 mb-1 mt-1">
                                <h4 class="text-sm font-black uppercase tracking-widest text-gray-900 leading-none">{{ $broadcast->title }}</h4>
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-[2px] bg-[#ff9933]/10 text-[#ff9933]">
                                    Headquarters
                                </span>
                            </div>
                            <p class="text-[11px] font-bold text-gray-500 leading-relaxed">{{ $broadcast->message }}</p>
                        </div>
                    </div>
                    <time class="text-[9px] font-black text-gray-400 uppercase tracking-[3px] ml-4 flex-shrink-0">{{ $broadcast->created_at->diffForHumans() }}</time>
                </div>
            @endforeach
        </div>
    @endif

    <!-- RECENT TRANSACTIONS -->
    <div class="mt-16">
        <div class="flex items-center space-x-6 mb-8">
            <h2 class="text-2xl font-black text-gray-900 uppercase">Recent Store Orders</h2>
            <div class="flex-grow h-[1px] bg-gray-100"></div>
        </div>

        <div class="card-premium overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Order ID</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Order Date</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Total Amount</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Status</th>
                        <th class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50/30 transition-all group">
                            <td class="p-6">
                                <span class="text-[11px] font-black text-gray-900 uppercase tracking-widest">#{{ $order->order_id_string }}</span>
                            </td>
                            <td class="p-6 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                {{ $order->ordered_date->format('d M, Y') }}
                            </td>
                            <td class="p-6 text-[12px] font-black text-gray-900">
                                ₹{{ number_format($order->total_amount, 2) }}
                            </td>
                            <td class="p-6">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[2px] 
                                    @if($order->status == 'Delivered') bg-green-50 text-green-600 border border-green-100 
                                    @elseif($order->status == 'Processing') bg-blue-50 text-blue-600 border border-blue-100 
                                    @else bg-gray-50 text-gray-400 border border-gray-100 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="p-6 text-right">
                                <button class="text-[10px] font-black text-[#ff9933] uppercase tracking-[3px] hover:underline">View Details</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center opacity-30">
                                <p class="text-[10px] font-black uppercase tracking-[8px]">No Orders Found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
