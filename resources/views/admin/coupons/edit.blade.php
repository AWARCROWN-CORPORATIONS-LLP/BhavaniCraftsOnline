@extends('layouts.admin')

@section('header_extra')
<a href="{{ route('admin.coupons.index') }}" class="text-[10px] font-black uppercase tracking-[3px] text-gray-400 hover:text-[#ff9933] transition-colors flex items-center space-x-2">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
    <span>Back to Registry</span>
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-12 pb-24">
    <!-- Header -->
    <div>
        <span class="text-[10px] font-black uppercase tracking-[4px] text-[#ff9933] block mb-2">Registry Refinement</span>
        <h1 class="text-4xl font-black text-gray-900 tracking-tight italic">Refine <span class="text-[#ff9933]">Divine Blessing</span></h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">Update the properties of an existing sacred coupon</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST" class="space-y-12">
        @csrf @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <!-- Basic Data -->
            <div class="space-y-8">
                <div class="card-premium p-8 bg-white border-none shadow-2xl shadow-gray-200/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 h-24 w-24 bg-gray-50/50 rotate-45 translate-x-12 -translate-y-12 rounded-full"></div>
                    
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-8 border-b border-gray-50 pb-6">Sacred Identity</h3>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Coupon Code</label>
                            <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required 
                                   class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                            <p class="px-1 text-[9px] text-gray-400 font-bold uppercase tracking-widest italic mt-1">THE WORD REMAINS SACRED</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Sacred Type</label>
                                <select name="type" required 
                                        class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                                    <option value="percentage" {{ $coupon->type === 'percentage' ? 'selected' : '' }}>PERCENTAGE (%)</option>
                                    <option value="fixed" {{ $coupon->type === 'fixed' ? 'selected' : '' }}>FIXED (₹)</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Benefit Value</label>
                                <input type="number" step="0.01" name="value" value="{{ old('value', $coupon->value) }}" required 
                                       class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-premium p-8 bg-white border-none shadow-2xl shadow-gray-200/50">
                    <div class="flex items-center justify-between mb-8 border-b border-gray-50 pb-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-900">Redemption Flow</h3>
                        <span class="text-[10px] font-black text-[#ff9933] uppercase tracking-widest bg-[#ff9933]/5 px-3 py-1 rounded-full italic">Uses: {{ $coupon->used_count }}</span>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Activation State</label>
                            <select name="status" required 
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                                <option value="1" {{ $coupon->status ? 'selected' : '' }}>RADIANTLY ACTIVE</option>
                                <option value="0" {{ !$coupon->status ? 'selected' : '' }}>DORMANT STATE</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Sacred Exclusivity</label>
                            <select name="is_first_order_only" required 
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                                <option value="0" {{ !$coupon->is_first_order_only ? 'selected' : '' }}>ALL SEEKERS</option>
                                <option value="1" {{ $coupon->is_first_order_only ? 'selected' : '' }}>FIRST ORDER ONLY</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Limits and Constraints -->
            <div class="space-y-8">
                <div class="card-premium p-8 bg-white border-none shadow-2xl shadow-gray-200/50">
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-8 border-b border-gray-50 pb-6">Sacred Constraints</h3>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Min Order Requirement (₹)</label>
                            <input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}" required 
                                   class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Redemption Limit</label>
                            <input type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Leave empty for unlimited" 
                                   class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                            <p class="px-1 text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-1">TOTAL PERMITTED REDEMPTIONS</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Expiry Ritual Date</label>
                            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}" 
                                   class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-[#ff9933] focus:border-[#ff9933] transition-all uppercase tracking-widest">
                            <p class="px-1 text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-1 italic italic">SCHEDULED FADE FROM EXISTENCE</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-between border-t border-gray-100 pt-10">
             <button type="button" onclick="confirm('Retract this blessing permanently?') ? document.getElementById('delete-form').submit() : ''" 
                     class="text-[9px] font-black text-red-400 uppercase tracking-[3px] hover:text-red-600 transition-colors">
                 Dissolve Blessing
             </button>

            <div class="flex items-center space-x-6">
                <a href="{{ route('admin.coupons.index') }}" class="text-[10px] font-black uppercase tracking-[3px] text-gray-400 hover:text-gray-900 transition-colors">Abort Refinement</a>
                <button type="submit" class="btn-luxury-saffron px-12 py-5 shadow-2xl shadow-[#ff9933]/20 flex items-center space-x-4">
                    <span class="text-xs">Seal Refinement Ritual</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    </form>

    <form id="delete-form" action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="hidden">
        @csrf @method('DELETE')
    </form>
</div>
@endsection
