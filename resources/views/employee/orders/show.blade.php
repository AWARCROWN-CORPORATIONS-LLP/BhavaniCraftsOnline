@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Order Details</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-sky-500 uppercase tracking-[4px]">Order #{{ $order->order_id_string }}</p>
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-12">
            <!-- ARTIFACT CONTENT -->
            <div class="card-premium p-12">
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Ordered Items</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="space-y-8">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center space-x-6">
                                <div class="h-20 w-16 bg-gray-100 rounded-xl flex items-center justify-center font-black text-xs text-gray-300 group-hover:bg-sky-500/10 group-hover:text-sky-500 transition-all">
                                    {{ strtoupper(substr($item->product_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest">{{ $item->product_name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Qty: {{ $item->quantity }} Units</p>
                                </div>
                            </div>
                             <div class="text-right">
                                <span class="px-3 py-1 rounded-full bg-gray-100 text-[8px] font-black tracking-[2px] text-gray-400">Verified Item</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 mt-12 pt-12">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Order check complete. Pricing hidden as per policy.</p>
                </div>
            </div>

            <!-- REGISTRY STATUS UPDATE -->
            <form action="{{ route('employee.orders.update-status', $order->encryptedId()) }}" method="POST" class="card-premium p-12 shadow-sky-100/50 border-sky-100 bg-sky-50/10">
                @csrf
                @method('PATCH')
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-sky-500 uppercase tracking-[6px] leading-none">Update Status</h3>
                    <div class="flex-grow h-[1px] bg-sky-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Order Shipment</label>
                        <select name="status" class="w-full bg-white border border-gray-100 px-8 py-5 rounded-2xl text-sm font-black focus:ring-2 focus:ring-sky-500/20 transition-all appearance-none uppercase tracking-widest">
                            <option value="Processing" @if($order->status == 'Processing') selected @endif>Processing</option>
                            <option value="Shipped" @if($order->status == 'Shipped') selected @endif>Shipped</option>
                            <option value="Delivered" @if($order->status == 'Delivered') selected @endif>Delivered</option>
                            <option value="Cancelled" @if($order->status == 'Cancelled') selected @endif>Cancelled</option>
                            <option value="Return Requested" @if($order->status == 'Return Requested') selected @endif>Return Requested</option>
                            <option value="Returned" @if($order->status == 'Returned') selected @endif>Returned</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Payment Update</label>
                        <select name="payment_status" class="w-full bg-white border border-gray-100 px-8 py-5 rounded-2xl text-sm font-black focus:ring-2 focus:ring-sky-500/20 transition-all appearance-none uppercase tracking-widest">
                            <option value="Pending" @if($order->payment_status == 'Pending') selected @endif>Pending</option>
                            <option value="Paid" @if($order->payment_status == 'Paid') selected @endif>Paid</option>
                            <option value="Failed" @if($order->payment_status == 'Failed') selected @endif>Failed</option>
                            <option value="Refunded" @if($order->payment_status == 'Refunded') selected @endif>Refunded</option>
                        </select>
                    </div>
                </div>

                <div class="mt-8 border-t border-gray-100 pt-8">
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Field Agent Assignment (Logistics Deployment)</label>
                    <div class="flex items-center space-x-4">
                        <svg class="h-6 w-6 text-sky-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a1 1 0 100-2 1 1 0 000 2z" /></svg>
                        <select name="assigned_logistics_id" class="w-full bg-white border border-gray-100 px-6 py-4 rounded-xl text-sm font-black focus:ring-2 focus:ring-sky-500/20 transition-all">
                            <option value="">-- No Field Agent Assigned --</option>
                            @foreach($logisticsPersonnel ?? [] as $lp)
                                <option value="{{ $lp->id }}" @if($order->assigned_logistics_id == $lp->id) selected @endif>
                                    {{ $lp->name }} ({{ $lp->phone }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" class="btn-luxury px-12 py-5 text-[11px] shadow-2xl">Update Database</button>
                </div>
            </form>
        </div>

        <div class="space-y-12">
            <!-- SEEKER CORE -->
            <div class="card-premium p-12 overflow-hidden relative">
                <div class="flex items-center space-x-6 mb-10 relative z-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Customer Details</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="space-y-6 relative z-10 uppercase tracking-widest">
                    <div class="flex items-center space-x-6">
                        <div class="h-16 w-16 bg-sky-50 text-sky-500 rounded-2xl flex items-center justify-center font-black text-lg border border-sky-100">
                            {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-lg font-black text-gray-900 leading-none mb-1">{{ $order->user->name ?? 'Guest User' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold tracking-widest leading-none lowercase italic">{{ $order->user->email ?? 'No email' }}</p>
                        </div>
                    </div>
                    <div class="pt-6 border-t border-gray-50">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-4">Contact Phone</p>
                        <p class="text-sm font-black text-gray-900">{{ $order->user->phone ?? 'Not Available' }}</p>
                    </div>
                </div>
            </div>

            <!-- PAYMENT LOGIC -->
            <div class="card-premium p-12">
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Payment Reference</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-2 leading-none">Raz-Pay Order</p>
                        <p class="text-sm font-black text-gray-900 font-mono tracking-widest">{{ $order->razorpay_order_id ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
