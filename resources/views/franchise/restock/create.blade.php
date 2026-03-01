@extends('layouts.franchise')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Signal Restock</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Headquarters Supply</p>
    </div>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('franchise.restock.store') }}" method="POST" class="card-premium p-12 space-y-12">
            @csrf

            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Supply Blueprint</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Select Master Artifact</label>
                    <select name="product_id" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                        <option value="">-- Choose Artifact --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }} (Current: {{ $product->stock }} Units)</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Requisition Volume (Units)</label>
                        <input type="number" name="requested_quantity" min="1" required placeholder="50" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all text-gray-900">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Threat Level</label>
                        <select name="priority" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                            <option value="normal" class="text-blue-600 font-bold">Standard Run</option>
                            <option value="urgent" class="text-amber-600 font-bold">Urgent Fill</option>
                            <option value="critical" class="text-red-600 font-bold">Inventory Crisis</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-red-50/50 border border-red-100 rounded-2xl flex items-center space-x-4">
                 <div class="h-10 w-10 flex-shrink-0 bg-red-500/10 text-red-500 rounded-xl flex items-center justify-center">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                 </div>
                 <p class="text-[10px] text-red-600/80 font-bold uppercase tracking-widest leading-relaxed">
                     Restock requests mandate Headquarters' Wholesale clearance. Upon approval, shipping logs and volume records will auto-update. Ensure exact requisition thresholds.
                 </p>
            </div>

            <div class="pt-10 flex items-center justify-between">
                <a href="{{ route('franchise.restock.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Abort Initialization</a>
                <button type="submit" class="btn-luxury-saffron px-12 py-5 text-[11px] shadow-2xl">Transceive Request to HQ</button>
            </div>
        </form>
    </div>

@endsection
