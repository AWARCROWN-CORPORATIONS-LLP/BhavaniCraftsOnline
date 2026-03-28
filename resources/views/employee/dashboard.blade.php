@extends('layouts.employee')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight">Staff Overview</h2>
@endsection

@section('content')

    <!-- STATS GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Total Products Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[200px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-sky-500 uppercase tracking-[4px] mb-2 leading-none font-bold">Total Products</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['total_products'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Active Products in Catalog</p>
                <div class="h-10 w-10 bg-sky-500/10 text-sky-500 flex items-center justify-center rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
            </div>
        </div>

        <!-- Pending Orders Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[200px] bg-sky-50/20">
            <div class="z-10">
                <p class="text-[9px] font-black text-sky-500 uppercase tracking-[4px] mb-2 leading-none font-bold">Orders Pending</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['pending_orders'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Awaiting Processing</p>
                <div class="h-10 w-10 bg-sky-500 text-white flex items-center justify-center rounded-xl shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>

        <!-- Total Orders Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[200px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-sky-500 uppercase tracking-[4px] mb-2 leading-none font-bold">Total Orders</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['total_orders'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">All Historical Orders</p>
                <div class="h-10 w-10 bg-sky-500/10 text-sky-500 flex items-center justify-center rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
            </div>
        </div>

        <!-- Pending Restocks Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[200px]">
            <div class="z-10">
                <p class="text-[9px] font-black text-sky-500 uppercase tracking-[4px] mb-2 leading-none font-bold">Restocks</p>
                <h3 class="text-4xl lg:text-5xl font-black text-gray-900 leading-none tracking-tighter">{{ $stats['pending_restocks'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">Awaiting Approval</p>
                <div class="h-10 w-10 bg-sky-500/10 text-sky-500 flex items-center justify-center rounded-xl">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
            </div>
        </div>

        <!-- Successful Deliveries Card -->
        <div class="card-premium p-8 relative overflow-hidden flex flex-col justify-between h-[200px] bg-emerald-50/50">
            <div class="z-10">
                <p class="text-[9px] font-black text-emerald-600 uppercase tracking-[4px] mb-2 leading-none font-bold">Logistics Success</p>
                <h3 class="text-4xl lg:text-5xl font-black text-emerald-700 leading-none tracking-tighter">{{ $stats['successful_deliveries'] }}</h3>
            </div>
            <div class="z-10 flex items-center justify-between">
                <p class="text-[10px] text-emerald-500/60 font-bold uppercase tracking-widest leading-none">Delivered Items</p>
                <div class="h-10 w-10 bg-emerald-500 text-white flex items-center justify-center rounded-xl shadow-lg">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT BROADCASTS -->
    @if($activeBroadcasts->count() > 0)
    <div class="mt-16">
        <div class="flex items-center space-x-6 mb-8">
            <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest">Active Broadcasts</h2>
            <div class="flex-grow h-[1.5px] bg-sky-100"></div>
        </div>
        <div class="space-y-4">
            @foreach($activeBroadcasts as $broadcast)
                <div class="card-premium p-6 flex flex-col md:flex-row items-center justify-between border-l-4 border-sky-500">
                    <div class="flex items-start md:items-center space-x-4">
                        <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-xl bg-sky-500/10 text-sky-600">
                             <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-gray-900 mb-1">{{ $broadcast->title }}</h4>
                            <p class="text-[11px] font-bold text-gray-500 leading-relaxed">{{ $broadcast->message }}</p>
                        </div>
                    </div>
                    <time class="text-[9px] font-black text-gray-400 uppercase tracking-[3px] ml-4 flex-shrink-0">{{ $broadcast->created_at->diffForHumans() }}</time>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- QUICK ACTIONS -->
    <div class="mt-16">
        <div class="flex items-center space-x-6 mb-8">
            <h2 class="text-xl font-black text-gray-900 uppercase tracking-widest">Staff Actions</h2>
            <div class="flex-grow h-[1.5px] bg-sky-100"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center uppercase tracking-widest">
            
            <a href="{{ route('employee.products.create') }}" class="card-premium p-10 flex flex-col items-center group hover:bg-sky-500 transition-all duration-300">
                <div class="h-16 w-16 bg-sky-100/50 text-sky-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white transition-all">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </div>
                <h4 class="text-[10px] font-black text-gray-900 group-hover:text-white mb-1">Add Product</h4>
                <p class="text-[8px] text-gray-400 group-hover:text-white/70 font-bold">Create New Catalog Item</p>
            </a>

            <a href="{{ route('employee.categories.index') }}" class="card-premium p-10 flex flex-col items-center group hover:bg-sky-500 transition-all duration-300">
                <div class="h-16 w-16 bg-sky-100/50 text-sky-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white transition-all">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                </div>
                <h4 class="text-[10px] font-black text-gray-900 group-hover:text-white mb-1">Categories</h4>
                <p class="text-[8px] text-gray-400 group-hover:text-white/70 font-bold">Manage Hierarchy</p>
            </a>

            <a href="{{ route('employee.orders.index') }}" class="card-premium p-10 flex flex-col items-center group hover:bg-sky-500 transition-all duration-300">
                <div class="h-16 w-16 bg-sky-100/50 text-sky-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white transition-all">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <h4 class="text-[10px] font-black text-gray-900 group-hover:text-white mb-1">Update Orders</h4>
                <p class="text-[8px] text-gray-400 group-hover:text-white/70 font-bold">Manage Status & Payment</p>
            </a>

            <a href="{{ route('employee.broadcasts.create') }}" class="card-premium p-10 flex flex-col items-center group hover:bg-sky-500 transition-all duration-300">
                <div class="h-16 w-16 bg-sky-100/50 text-sky-500 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-white transition-all">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                </div>
                <h4 class="text-[10px] font-black text-gray-900 group-hover:text-white mb-1">New Broadcast</h4>
                <p class="text-[8px] text-gray-400 group-hover:text-white/70 font-bold">Staff Announcement</p>
            </a>
        </div>
    </div>

    <!-- RECENT ORDERS TABLE -->
    <div class="mt-16 bg-white rounded-[32px] border border-gray-100 shadow-2xl shadow-gray-200/50 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between bg-white sticky top-0 z-10">
            <div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-[4px] leading-none mb-2">Recent Client Orders</h3>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[2px]">Latest Order Submissions</p>
            </div>
            <a href="{{ route('employee.orders.index') }}" class="px-6 py-3 bg-gray-50 text-gray-900 text-[10px] font-black uppercase tracking-[3px] rounded-xl hover:bg-gray-100 transition-all">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Order ID</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Seeker</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Status</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Date</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Action</th>
                    </tr>
                <tbody class="divide-y divide-gray-50 uppercase">
                    @foreach($recentOrders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <span class="text-[11px] font-black text-gray-900 tracking-wider">#{{ $order->order_id_string }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[11px] font-bold text-gray-500 tracking-wider">{{ $order->user->name ?? 'Unknown' }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] 
                                {{ $order->status == 'Delivered' ? 'bg-green-100 text-green-600' : 
                                   ($order->status == 'Cancelled' ? 'bg-red-100 text-red-600' : 'bg-sky-100 text-sky-600') }}">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-[10px] font-bold text-gray-400 lowercase tracking-widest italic">
                            {{ $order->ordered_date->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-6">
                             <a href="{{ route('employee.orders.show', $order->encryptedId()) }}" class="text-[10px] font-black uppercase tracking-[3px] text-sky-500 hover:text-sky-700">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
