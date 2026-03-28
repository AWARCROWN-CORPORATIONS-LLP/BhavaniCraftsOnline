@extends('layouts.admin')

@section('content')
<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">Curated Solutions</span>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight italic">Ritual <span class="text-brand-500">Kits</span></h1>
            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">Manage sacred bundles and ceremonial product sets</p>
        </div>
        <a href="{{ route('admin.ritual-kits.create') }}" class="btn-luxury-saffron px-8 py-4 flex items-center space-x-3 self-start">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            <span class="text-xs">Forge New Ritual Kit</span>
        </a>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="card-premium p-8 bg-white overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Kits</p>
                <h3 class="text-3xl font-black text-gray-900 italic">{{ \App\Models\RitualKit::count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
        </div>
        <div class="card-premium p-8 bg-white overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Active Kits</p>
                <h3 class="text-3xl font-black text-green-600 italic">{{ \App\Models\RitualKit::where('is_active', true)->count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-green-600">
                <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <div class="card-premium p-8 bg-white overflow-hidden relative group">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Product associations</p>
                <h3 class="text-3xl font-black text-brand-500 italic">{{ \DB::table('ritual_kit_product')->count() }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity text-brand-500">
                <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card-premium bg-white overflow-hidden border-none shadow-2xl shadow-gray-200/50">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 bg-gray-50/30">
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Kit Identity</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Price</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Inventory</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400">Divine state</th>
                        <th class="px-8 py-6 text-[10px] font-black uppercase tracking-[3px] text-gray-400 text-right">Rituals</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($kits as $kit)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-16 bg-gray-100 rounded-2xl overflow-hidden group-hover:ring-2 ring-brand-500 transition-all">
                                    <img src="{{ $kit->display_image ? Storage::url($kit->display_image) : 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop' }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-black text-gray-900 uppercase tracking-widest">{{ $kit->name }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 mt-1 italic tracking-widest line-clamp-1 max-w-xs">{{ $kit->description }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                             <span class="text-sm font-black text-gray-900 italic">₹{{ number_format($kit->price ?? $kit->products->sum('price'), 2) }}</span>
                        </td>
                        <td class="px-8 py-6">
                             <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                 {{ $kit->products_count }} Sacred Items
                             </span>
                        </td>
                        <td class="px-8 py-6">
                            @if($kit->is_active)
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
                                <a href="{{ route('admin.ritual-kits.edit', $kit) }}" class="p-2.5 bg-gray-50 text-gray-400 hover:text-brand-500 hover:bg-brand-500/5 rounded-xl transition-all">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                </a>
                                <form action="{{ route('admin.ritual-kits.destroy', $kit) }}" method="POST" onsubmit="return confirm('Retract this Ritual Kit from the registry?')">
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
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto space-y-4">
                                <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto">
                                    <svg class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                </div>
                                <h3 class="text-sm font-black text-gray-400 uppercase tracking-widest">No Ritual Kits Discovered</h3>
                                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest leading-loose">Forge curated collections of artifacts for specialized ceremonies.</p>
                                <a href="{{ route('admin.ritual-kits.create') }}" class="inline-block text-[10px] font-black text-brand-500 uppercase tracking-[3px] hover:text-brand-600 transition-colors border-b-2 border-transparent hover:border-brand-500 pb-1">Begin Assembly Ritual</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kits->hasPages())
        <div class="px-8 py-6 border-t border-gray-50 bg-gray-50/20">
            {{ $kits->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
