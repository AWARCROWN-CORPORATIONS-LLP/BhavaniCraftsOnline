@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Order Details</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Order #{{ $order->order_id_string }}</p>
    </div>
@endsection

@section('content')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2 space-y-12">
            <!-- ARTIFACT CONTENT -->
            <div class="card-premium p-12">
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Items Ordered</h3>
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
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    @if($order->tax_total > 0)
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[3px] text-gray-400">
                            <span>GST Tax</span>
                            <span>₹{{ number_format($order->tax_total, 2) }}</span>
                        </div>
                    @endif
                    @if($order->shipping_total > 0)
                        <div class="flex items-center justify-between text-[10px] font-black uppercase tracking-[3px] text-gray-400">
                            <span>Shipping Cost</span>
                            <span>₹{{ number_format($order->shipping_total, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-between text-xl font-black uppercase text-gray-900 pt-6 border-t border-gray-100/50">
                        <span class="tracking-widest">Total Amount</span>
                        <span>₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- LOGISTICS TERMINAL (NEW: ADVANCED FIELD PROOFS) -->
            <div class="card-premium p-12 bg-gray-900 border-none relative overflow-hidden group">
                <div class="flex items-center space-x-6 mb-10 relative z-10">
                    <h3 class="text-xs font-black text-sky-400 uppercase tracking-[6px] leading-none">Delivery Status</h3>
                    <div class="flex-grow h-[1px] bg-white/10"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 relative z-10">
                    <!-- Proof Metrics -->
                    <div class="space-y-8">
                        <div>
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-4">Status</p>
                            <div class="flex items-center space-x-4">
                                <div class="px-6 py-3 bg-white/5 rounded-2xl border border-white/10">
                                    <span class="text-[10px] font-black uppercase text-sky-400 tracking-widest">{{ $order->delivery_status ?? 'Not Started' }}</span>
                                </div>
                                @if($order->delivered_at)
                                    <p class="text-[9px] font-bold text-white/40 uppercase">{{ $order->delivered_at->format('d M Y, H:i') }}</p>
                                @endif
                            </div>
                        </div>

                        @if($order->delivery_latitude && $order->delivery_longitude)
                        <div>
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-4">Delivery Location</p>
                            <a href="https://www.google.com/maps?q={{ $order->delivery_latitude }},{{ $order->delivery_longitude }}" target="_blank" 
                               class="inline-flex items-center space-x-3 px-6 py-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl group/map hover:bg-emerald-500/20 transition-all">
                                <svg class="h-4 w-4 text-emerald-400 group-hover/map:scale-125 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">View on Map</span>
                            </a>
                        </div>
                        @endif

                        @if($order->delivery_rating)
                        <div>
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-4">Customer Rating</p>
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
                            <p class="text-[9px] font-black text-white/30 uppercase tracking-[4px] mb-6">Delivery Photo</p>
                            <div class="w-full aspect-video rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($order->delivery_photo_url) }}" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="text-center space-y-4">
                                <div class="h-16 w-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 border border-white/10">
                                    <svg class="h-8 w-8 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <p class="text-[10px] font-black text-white/20 uppercase tracking-[3px]">No photo yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($order->failed_delivery_reason)
                <div class="mt-12 p-8 bg-red-400/10 border border-red-400/20 rounded-3xl relative z-10">
                    <div class="flex items-center space-x-4">
                        <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <p class="text-[9px] font-black text-red-300 uppercase tracking-[4px] mb-1 leading-none">Delivery Failed Reason</p>
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
                            <p class="text-[9px] font-black text-amber-300 uppercase tracking-[4px] mb-1 leading-none">Return Reason</p>
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
            <div x-data="{ 
                loading: false, 
                success: false, 
                async submitStatus(e) {
                    if (this.loading) return;
                    this.loading = true;
                    this.success = false;
                    
                    try {
                        const formData = new FormData(e.target);
                        const resp = await fetch('{{ route('admin.orders.update-status', [app()->getLocale(), $order->encryptedId()]) }}', {
                            method: 'POST', // Spoofed to PATCH via _method
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const body = await resp.json();
                        if (body.success) {
                            this.success = true;
                            setTimeout(() => { this.success = false; }, 3000);
                        }
                    } catch (e) { console.error(e); } finally { this.loading = false; }
                }
            }" class="card-premium p-12">
                <form @submit.prevent="submitStatus($event)">
                    @csrf
                    @method('PATCH')
                    <div class="flex items-center space-x-6 mb-10">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Update Status</h3>
                        <div class="flex-grow h-[1px] bg-gray-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Order Status</label>
                            <select name="status" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                                <option value="Processing" @if($order->status == 'Processing') selected @endif>Processing</option>
                                <option value="Shipped" @if($order->status == 'Shipped') selected @endif>Shipped</option>
                                <option value="Delivered" @if($order->status == 'Delivered') selected @endif>Delivered</option>
                                <option value="Cancelled" @if($order->status == 'Cancelled') selected @endif>Cancelled</option>
                                <option value="Return Requested" @if($order->status == 'Return Requested') selected @endif>Return Requested</option>
                                <option value="Returned" @if($order->status == 'Returned') selected @endif>Returned</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Payment Status</label>
                            <select name="payment_status" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                                <option value="Pending" @if($order->payment_status == 'Pending') selected @endif>Pending</option>
                                <option value="Authorized" @if($order->payment_status == 'Authorized') selected @endif>Authorized</option>
                                <option value="Paid" @if($order->payment_status == 'Paid') selected @endif>Paid</option>
                                <option value="Refunded" @if($order->payment_status == 'Refunded') selected @endif>Refunded</option>
                                <option value="Failed" @if($order->payment_status == 'Failed') selected @endif>Failed</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Shipping Partner</label>
                            <select name="shipping_partner" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                                <option value="">-- Internal / Delivery Boy --</option>
                                <option value="India Post" @if($order->shipping_partner == 'India Post') selected @endif>India Post</option>
                                <option value="Professional Courier" @if($order->shipping_partner == 'Professional Courier') selected @endif>Professional Courier</option>
                                <option value="BlueDart" @if($order->shipping_partner == 'BlueDart') selected @endif>BlueDart</option>
                                <option value="Other" @if($order->shipping_partner == 'Other') selected @endif>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Tracking Number / AWB</label>
                            <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Enter Tracking ID" 
                                   class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-100 pt-8">
                        <label class="text-[10px] font-black text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Assign Delivery Boy</label>
                        <div class="flex items-center space-x-4">
                            <svg class="h-6 w-6 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a1 1 0 100-2 1 1 0 000 2z" /></svg>
                            <select name="assigned_logistics_id" class="w-full bg-gray-50 border-none px-6 py-4 rounded-xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                                <option value="">-- Not Assigned --</option>
                                @foreach($logisticsPersonnel ?? [] as $lp)
                                    <option value="{{ $lp->id }}" @if($order->assigned_logistics_id == $lp->id) selected @endif>
                                        {{ $lp->name }} ({{ $lp->phone }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-between">
                        <div x-show="success" x-transition class="text-[10px] font-black uppercase text-emerald-500 tracking-widest bg-emerald-50 px-6 py-3 rounded-full border border-emerald-100 italic">
                            ✓ Registry Synced Successfully
                        </div>
                        <div x-show="!success"></div>

                        <button type="submit" class="relative btn-luxury-saffron px-12 py-5 text-[11px] shadow-2xl flex items-center justify-center overflow-hidden min-w-[180px]">
                            <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-[#ff9933]">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            </div>
                            <span x-show="!loading">Update Master Record</span>
                        </button>
                    </div>
                </form>

                <!-- EMERGENCY PURGE -->
                <div class="mt-12 border-t border-red-50 pt-8 flex items-center justify-between opacity-40 hover:opacity-100 transition-opacity">
                    <div>
                        <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest mb-1">Danger Zone</p>
                        <p class="text-[8px] text-gray-400 font-bold uppercase">This action cannot be undone</p>
                    </div>
                    <form action="{{ route('admin.orders.destroy', [app()->getLocale(), $order->encryptedId()]) }}" method="POST" onsubmit="return confirm('Permanently PURGE this order from registry?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 border border-red-100 text-rose-500 hover:bg-rose-50 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">Purge Order</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="space-y-12">
            <!-- SEEKER CORE -->
            <div class="card-premium p-12 overflow-hidden relative">
                <div class="flex items-center space-x-6 mb-10 relative z-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Customer Details</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="space-y-6 relative z-10">
                    <div class="flex items-center space-x-6">
                        <div class="h-16 w-16 bg-gray-100 rounded-2xl flex items-center justify-center font-black text-lg text-gray-400 border border-gray-100">
                            {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-lg font-black text-gray-900 uppercase tracking-tighter leading-none mb-1">{{ $order->user->name ?? 'Guest Member' }}</p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">{{ $order->user->email ?? 'No Email Provided' }}</p>
                        </div>
                    </div>
                    <div class="pt-6 border-t border-gray-50">
                        <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-4">Member Since</p>
                        <p class="text-sm font-bold text-gray-600 leading-relaxed italic">
                            Member since {{ $order->user->created_at ? $order->user->created_at->format('M Y') : 'Unknown' }}
                        </p>
                    </div>
                </div>
                <div class="absolute -right-20 -top-20 h-64 w-64 bg-[#ff9933]/5 rounded-full blur-3xl"></div>
            </div>

            <!-- PAYMENT & ASSET REGISTRY -->
            <div class="card-premium p-12 overflow-hidden relative group">
                <div class="flex items-center space-x-6 mb-10 relative z-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Order Assets</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 relative z-10">
                    <div class="space-y-6">
                        <div>
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-2 leading-none">Order ID</p>
                            <p class="text-sm font-black text-gray-900 font-mono tracking-widest">{{ $order->order_id_string }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-300 uppercase tracking-[4px] mb-2 leading-none">Transaction Ref</p>
                            <p class="text-xs font-black text-blue-600 font-mono tracking-widest truncate">{{ $order->razorpay_payment_id ?? 'AWAITING_FINISH' }}</p>
                        </div>
                        <div class="pt-4 flex flex-col space-y-3">
                            <a href="{{ route('orders.invoice', [app()->getLocale(), $order->encryptedId()]) }}" class="flex items-center space-x-3 text-[10px] font-black uppercase text-gray-600 hover:text-brand-primary transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <span>Download Receipt</span>
                            </a>
                            <a href="{{ route('employee.dispatch.label', [app()->getLocale(), $order->id]) }}" target="_blank" class="flex items-center space-x-3 text-[10px] font-black uppercase text-gray-600 hover:text-emerald-600 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                <span>Print Shipping Label</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- QR Verification Asset -->
                    <div class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-3xl border border-gray-100 group-hover:border-brand-primary/20 transition-all">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('logistics.verify.show', [app()->getLocale(), $order->encryptedId()]) }}" class="h-32 w-32 mix-blend-multiply opacity-80 group-hover:opacity-100 transition-opacity">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-[3px] mt-4">Scan for Dispatch Proof</p>
                    </div>
                </div>
            </div>

            <!-- LOGISTICS AGENT PROFILE -->
            @if($order->assignedLogistics)
            <div class="card-premium p-12 bg-gradient-to-br from-white to-gray-50">
                <div class="flex items-center space-x-6 mb-10">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Assigned Agent</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="h-16 w-16 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-xl shadow-blue-500/20">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <p class="text-lg font-black text-gray-900 uppercase tracking-tighter leading-none mb-1">{{ $order->assignedLogistics->name }}</p>
                        <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest leading-none">{{ $order->assignedLogistics->phone }}</p>
                    </div>
                </div>
                
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="p-4 bg-white border border-gray-100 rounded-2xl">
                        <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-1">Agent Status</p>
                        <p class="text-[10px] font-bold text-gray-700 uppercase">{{ $order->assignedLogistics->is_blocked ? 'Inactive' : 'Available' }}</p>
                    </div>
                    <div class="p-4 bg-white border border-gray-100 rounded-2xl">
                        <p class="text-[8px] font-black text-gray-300 uppercase tracking-widest mb-1">Current Assignment</p>
                        <p class="text-[10px] font-bold text-emerald-600 uppercase">{{ $order->delivery_status ?? 'Assigned' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

@endsection
