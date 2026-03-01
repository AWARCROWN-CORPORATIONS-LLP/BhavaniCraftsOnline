@extends('layouts.franchise')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">My Inventory</h2>
        <a href="/franchise/inventory/create" class="btn-luxury-saffron px-6 py-2 text-[10px] shadow-xl">Register New Artifact</a>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Your Private Collection</h3>
            <div class="flex items-center space-x-2">
                <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ $products->total() }} Entries</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Artifact Details</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Stock Level</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Wholesale Unit Price</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Listing Status</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Registry Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="p-8">
                                <div class="flex items-center space-x-4">
                                    <div class="h-16 w-16 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-300 group-hover:bg-[#ff9933]/10 group-hover:text-[#ff9933] transition-all overflow-hidden border border-gray-100 shrink-0">
                                        @if($product->images->where('is_main', true)->first())
                                            <img src="{{ asset('storage/' . $product->images->where('is_main', true)->first()->image_url) }}" class="w-full h-full object-cover">
                                        @elseif($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest leading-none mb-1">{{ $product->product_name }}</p>
                                        <p class="text-[10px] text-[#ff9933] font-bold uppercase tracking-wider leading-none">{{ $product->category->name ?? 'Uncategorized' }}</p>
                                        <p class="mt-2 text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $product->product_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-8">
                                <div class="flex items-center space-x-2">
                                    <span class="text-[11px] font-black text-gray-900">{{ $product->stock }}</span>
                                    <span class="text-[9px] text-gray-300 font-bold uppercase tracking-widest">Units Available</span>
                                </div>
                            </td>
                            <td class="p-8 font-black text-gray-900 text-sm">
                                ₹{{ number_format($product->price, 2) }}
                            </td>
                            <td class="p-8">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[2px] 
                                    @if($product->listed_status == 'Listed') bg-green-50 text-green-600 border border-green-100 
                                    @elseif($product->listed_status == 'Draft') bg-yellow-50 text-yellow-600 border border-yellow-100 
                                    @else bg-red-50 text-red-600 border border-red-100 @endif">
                                    {{ $product->listed_status }}
                                </span>
                            </td>
                            <td class="p-8 text-right">
                                <div class="flex items-center justify-end space-x-4">
                                    <a href="{{ route('franchise.inventory.edit', $product->id) }}" class="text-gray-400 hover:text-[#ff9933] transition-colors"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                    <form action="{{ route('franchise.inventory.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Eradicate entity from registry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="flex flex-col items-center opacity-30 capitalize">
                                    <svg class="h-20 w-20 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                    <h4 class="text-xl font-black uppercase tracking-[10px] text-gray-400">Inventory Empty</h4>
                                    <p class="text-[10px] font-bold tracking-widest mt-4">Begin your legacy by registering your first artifact.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
            <div class="p-8 border-t border-gray-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection
