@extends('layouts.admin')

@section('content')
<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[4px] text-[#ff9933] block mb-2">Divine Treasury</span>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight italic">Coupon <span class="text-[#ff9933]">Registry</span></h1>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">Manage sacred blessings and artifact discounts</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn-luxury-saffron px-8 py-4 flex items-center space-x-3 self-start">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span class="text-xs">Forge New Coupon</span>
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card-premium p-8 bg-white overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Coupons</p>
                <h3 class="text-3xl font-black text-gray-900 italic">{{ \App\Models\Coupon::count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
            </div>
        </div>
        <div class="card-premium p-8 bg-white overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Blessings</p>
                <h3 class="text-3xl font-black text-green-600 italic">{{ \App\Models\Coupon::where('status', true)->count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-green-600">
                <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="card-premium p-8 bg-white overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Redeemed</p>
                <h3 class="text-3xl font-black text-[#ff9933] italic">{{ \App\Models\Coupon::sum('used_count') }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-[#ff9933]">
                <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card-premium bg-white overflow-hidden border-none shadow-2xl shadow-gray-200/50">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/30">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Coupon Identity</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Benefit</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Requirements</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Redemption status</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Divine state</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400 text-right">Rituals</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="h-12 w-12 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-[#ff9933]/10 transition-colors">
                                    <span class="text-gray-400 group-hover:text-[#ff9933] font-black text-xs uppercase tracking-widest">{{ substr($coupon->code, 0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest">{{ $coupon->code }}</p>
                                    @if($coupon->expires_at)
                                        <p class="text-[10px] font-bold {{ now()->isAfter($coupon->expires_at) ? 'text-red-400' : 'text-gray-400' }} mt-1 italic">
                                            Expiry: {{ $coupon->expires_at->format('M d, Y') }}
                                        </p>
                                    @else
                                        <p class="text-[10px] font-bold text-gray-400 mt-1 italic tracking-widest">ETERNAL BLESSING</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($coupon->type === 'percentage')
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    {{ $coupon->value }}% Discount
                                </span>
                            @else
                                <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    ₹{{ number_format($coupon->value) }} OFF
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Min Order: ₹{{ number_format($coupon->min_order_amount) }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-2">
                                <p class="text-sm font-black text-gray-900 italic">{{ $coupon->used_count }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">/ {{ $coupon->usage_limit ?? '∞' }} Uses</p>
                            </div>
                            <div class="w-24 h-1.5 bg-gray-100 rounded-full mt-2 overflow-hidden">
                                @php 
                                    $percent = $coupon->usage_limit ? ($coupon->used_count / $coupon->usage_limit) * 100 : 0;
                                @endphp
                                <div class="h-full bg-[#ff9933] transition-all duration-1000" style="width: {{ min(100, $percent) }}%"></div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            @if($coupon->status)
                                <span class="inline-flex items-center space-x-2 text-green-600 bg-green-50 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-green-100 transition-all hover:scale-105">
                                    <span class="h-1.5 w-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                    <span>Radiant</span>
                                </span>
                            @else
                                <span class="inline-flex items-center space-x-2 text-gray-400 bg-gray-50 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-gray-100">
                                    <span class="h-1.5 w-1.5 bg-gray-300 rounded-full"></span>
                                    <span>Dormant</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-2.5 bg-gray-50 text-gray-400 hover:text-[#ff9933] hover:bg-[#ff9933]/5 rounded-xl transition-all">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Retract this blessing from the registry?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2.5 bg-gray-50 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto space-y-4">
                                <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto">
                                    <svg class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </div>
                                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">No Coupons Discovered</h3>
                                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest leading-loose">The divine registry is empty. Start by forging a new coupon.</p>
                                <a href="{{ route('admin.coupons.create') }}" class="inline-block text-[10px] font-black text-[#ff9933] uppercase tracking-[3px] hover:text-[#fb8c00] transition-colors border-b-2 border-transparent hover:border-[#ff9933] pb-1">Begin Forge Ritual</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($coupons->hasPages())
        <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/20">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
