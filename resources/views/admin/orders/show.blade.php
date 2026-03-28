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

            <!-- LOGISTICS TERMINAL (NEW: ADVANCED FIELD PROOFS) -->
            <div class="card-premium p-12 bg-gray-900 border-none relative overflow-hidden group">
                <div class="flex items-center space-x-6 mb-10 relative z-10">
                    <h3 class="text-xs font-black text-sky-400 uppercase tracking-[6px] leading-none">Logistics Terminal</h3>
                    <div class="flex-grow h-[1px] bg-white/10"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">
                    <!-- Proof Metrics -->
                    <div class="space-y-8">
                        <div>
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-4">Registry Finalization</p>
                            <div class="flex items-center space-x-4">
                                <div class="px-6 py-3 bg-white/5 rounded-2xl border border-white/10">
                                    <span class="text-[10px] font-black uppercase text-sky-400 tracking-widest">{{ $order->delivery_status ?? 'Pending Initiation' }}</span>
                                </div>
                                @if($order->delivered_at)
                                    <p class="text-[9px] font-bold text-white/40 uppercase">{{ $order->delivered_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        @if($order->delivery_latitude && $order->delivery_longitude)
                        <div>
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-4">Geospatial Signature</p>
                            <a href="https://www.google.com/maps?q={{ $order->delivery_latitude }},{{ $order->delivery_longitude }}" target="_blank" 
                               class="inline-flex items-center space-x-3 px-6 py-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl group/map hover:bg-emerald-500/20 transition-all">
                                <svg class="h-4 w-4 text-emerald-400 group-hover/map:scale-125 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">View Exchange Location</span>
                            </a>
                        </div>
                        @endif

                        @if($order->delivery_rating)
                        <div>
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-4">Customer Satisfaction Registry</p>
                            <div class="flex items-center space-x-2">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="h-5 w-5 {{ $i <= $order->delivery_rating ? 'text-amber-400' : 'text-white/10' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                @endfor
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Visual Proof -->
                    <div class="bg-white/5 rounded-[2rem] border border-white/10 p-6 flex flex-col items-center justify-center min-h-[300px]">
                        @if($order->delivery_photo_url)
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-6">Biometric Delivery Proof</p>
                            <div class="w-full aspect-video rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($order->delivery_photo_url) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="text-center space-y-4">
                                <div class="h-16 w-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/10">
                                    <svg class="h-8 w-8 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <p class="text-[10px] font-black text-white/20 uppercase tracking-[3px]">Waiting for Field Proof</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($order->failed_delivery_reason)
                <div class="mt-12 p-8 bg-red-400/10 border border-red-400/20 rounded-3xl relative z-10">
                    <div class="flex items-center space-x-4">
                        <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <p class="text-[9px] font-black text-red-300 uppercase tracking-[4px] mb-1 leading-none">Field Exception Root</p>
                            <p class="text-xs font-bold text-red-400 uppercase tracking-widest">{{ $order->failed_delivery_reason }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($order->return_reason)
                <div class="mt-12 p-8 bg-amber-400/10 border border-amber-400/20 rounded-3xl relative z-10">
                    <div class="flex items-center space-x-4">
                        <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z" /></svg>
                        <div class="flex-grow">
                            <p class="text-[9px] font-black text-amber-300 uppercase tracking-[4px] mb-1 leading-none">RMS Internal Ticket</p>
                            <p class="text-[11px] font-bold text-amber-400 leading-relaxed italic">{{ $order->return_reason }}</p>
                        </div>
                        @if($order->return_requested_at)
                            <p class="text-[9px] font-black text-amber-300 uppercase tracking-widest shrink-0">{{ $order->return_requested_at->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Decor -->
                <div class="absolute -right-20 -bottom-20 h-64 w-64 bg-sky-500/10 rounded-full blur-[100px] group-hover:bg-sky-500/20 transition-all duration-700"></div>
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
                            <option value="Return Requested" @if($order->status == 'Return Requested') selected @endif>Return Requested (Artifact Extraction)</option>
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

                <div class="mt-8 border-t border-gray-100 pt-8">
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Field Agent Assignment (Logistics Override)</label>
                    <div class="flex items-center space-x-4">
                        <svg class="h-6 w-6 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a1 1 0 100-2 1 1 0 000 2z" /></svg>
                        <select name="assigned_logistics_id" class="w-full bg-gray-50 border-none px-6 py-4 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
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
