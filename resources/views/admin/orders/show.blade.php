@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Review Sequence</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Order Registry #{{ $order->order_id_string }}</p>
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-12">
            <!-- ARTIFACT CONTENT -->
            <div class="card-premium p-12">
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Vested Artifacts</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="space-y-8">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center space-x-6">
                                <div class="h-20 w-16 bg-gray-100 rounded-xl flex items-center justify-center font-black text-xs text-gray-300 group-hover:bg-[#ff9933]/10 group-hover:text-[#ff9933] transition-all">
                                    {{ strtoupper(substr($item->product_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest">{{ $item->product_name }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Qty: {{ $item->quantity }} x ₹{{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-gray-900">₹{{ number_format($item->price * $item->quantity, 2) }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Calculated Subtotal</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 mt-12 pt-12 space-y-6">
                    <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[3px] text-gray-400">
                        <span>Base Offering Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->tax_total > 0)
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[3px] text-gray-400">
                            <span>Master Registry Tax (GST)</span>
                            <span>₹{{ number_format($order->tax_total, 2) }}</span>
                        </div>
                    @endif
                    @if($order->shipping_total > 0)
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[3px] text-gray-400">
                            <span>Trans-Dimensional Shipping Logic</span>
                            <span>₹{{ number_format($order->shipping_total, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-xl font-black uppercase text-gray-900 pt-6 border-t border-gray-100/50">
                        <span class="tracking-widest">Total Trade Value</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- REGISTRY STATUS UPDATE -->
            <form action="{{ route('admin.orders.update-status', $order->encryptedId()) }}" method="POST" class="card-premium p-12">
                @csrf
                @method('PATCH')
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Status Manifest</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Organic Progress</label>
                        <select name="status" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                            <option value="Processing" @if($order->status == 'Processing') selected @endif>Processing (In Devotion)</option>
                            <option value="Shipped" @if($order->status == 'Shipped') selected @endif>Shipped (On Transit Pipeline)</option>
                            <option value="Delivered" @if($order->status == 'Delivered') selected @endif>Delivered (Trade Fulfilled)</option>
                            <option value="Cancelled" @if($order->status == 'Cancelled') selected @endif>Cancelled (Vesta Revoked)</option>
                            <option value="Returned" @if($order->status == 'Returned') selected @endif>Returned (Cycle Restored)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Financial Finality</label>
                        <select name="payment_status" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                            <option value="Pending" @if($order->payment_status == 'Pending') selected @endif>Pending Waitlist</option>
                            <option value="Authorized" @if($order->payment_status == 'Authorized') selected @endif>Authorized Credit</option>
                            <option value="Paid" @if($order->payment_status == 'Paid') selected @endif>Paid & Committed</option>
                            <option value="Refunded" @if($order->payment_status == 'Refunded') selected @endif>Refunded Restoration</option>
                            <option value="Failed" @if($order->payment_status == 'Failed') selected @endif>Failed Extraction</option>
                        </select>
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" class="btn-luxury-saffron px-12 py-5 text-[11px] shadow-2xl">Commit Registry Update</button>
                </div>
            </form>
        </div>

        <div class="space-y-12">
            <!-- SEEKER CORE -->
            <div class="card-premium p-12 overflow-hidden relative">
                <div class="flex items-center space-x-6 mb-10 relative z-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Seeker Identity</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="space-y-6 relative z-10">
                    <div class="flex items-center space-x-6">
                        <div class="h-16 w-16 bg-gray-100 rounded-2xl flex items-center justify-center font-black text-lg text-gray-400 border border-gray-100">
                            {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-lg font-black text-gray-900 uppercase tracking-tighter leading-none mb-1">{{ $order->user->name ?? 'Guest User' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">{{ $order->user->email ?? 'No Email Provided' }}</p>
                        </div>
                    </div>
                    <div class="pt-6 border-t border-gray-50">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-4">Registry Origin</p>
                        <p class="text-sm font-bold text-gray-600 leading-relaxed italic">
                            Member since {{ $order->user->created_at ? $order->user->created_at->format('M Y') : 'Unknown' }}
                        </p>
                    </div>
                </div>
                <div class="absolute -right-20 -top-20 h-64 w-64 bg-[#ff9933]/5 rounded-full blur-3xl"></div>
            </div>

            <!-- PAYMENT LOGIC -->
            <div class="card-premium p-12">
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Financial Extraction</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-2 leading-none">Razorpay Hierarchy ID</p>
                        <p class="text-sm font-black text-gray-900 font-mono tracking-widest">{{ $order->razorpay_order_id ?? 'NOT_SYNCED' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-2 leading-none">Final Transaction Ref</p>
                        <p class="text-sm font-black text-gray-900 font-mono tracking-widest">{{ $order->razorpay_payment_id ?? 'AWAITING_FINISH' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
