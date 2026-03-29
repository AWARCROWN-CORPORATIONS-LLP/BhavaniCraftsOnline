@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Product Management</h2>
        <a href="{{ route('admin.products.create') }}" class="btn-luxury-saffron px-6 py-2 text-[10px] shadow-xl">Add New Product</a>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Global Catalog Presence</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ $products->total() }} Entities</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Product Details</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Stock Level</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Price (MRP)</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="h-16 w-16 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-300 group-hover:bg-[#1e40af]/10 group-hover:text-[#1e40af] transition-all overflow-hidden border border-gray-100 uppercase shrink-0">
                                        @if($product->images->where('is_main', true)->first())
                                            <img src="{{ asset('storage/' . $product->images->where('is_main', true)->first()->image_url) }}" class="w-full h-full object-cover">
                                        @elseif($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($product->product_name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest leading-none mb-1">{{ $product->product_name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <p class="text-[10px] text-[#1e40af] font-bold uppercase tracking-wider leading-none">{{ $product->category->name ?? 'Unmapped' }}</p>
                                            <span class="text-[8px] px-2 py-0.5 rounded bg-gray-100 text-gray-500 font-bold uppercase tracking-widest">
                                                {{ $product->user ? 'Partner: ' . $product->user->name : 'In-house Store' }}
                                            </span>
                                        </div>
                                        <p class="mt-2 text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $product->product_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <span class="text-[11px] font-black text-gray-900">{{ $product->stock }}</span>
                                    <span class="text-[9px] text-gray-300 font-bold uppercase tracking-widest">Units in Stock</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-black text-gray-900 text-sm">
                                <div class="flex items-end space-x-2">
                                    <span>₹{{ number_format($product->price, 2) }}</span>
                                    @if($product->discount_percent && $product->discount_percent > 0)
                                        <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-[8px] rounded font-black">{{ $product->discount_percent }}% OFF</span>
                                    @endif
                                </div>
                                <p class="text-[8px] text-gray-300 italic line-through mt-0.5">MRP: ₹{{ number_format($product->mrp ?? $product->price, 2) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[2px] 
                                    @if($product->listed_status == 'Listed') bg-green-50 text-green-600 border border-green-100 
                                    @elseif($product->listed_status == 'Draft') bg-yellow-50 text-yellow-600 border border-yellow-100 
                                    @else bg-red-50 text-red-600 border border-red-100 @endif">
                                    {{ $product->listed_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-4">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-gray-400 hover:text-[#1e40af] transition-colors"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                                <td colspan="5" class="py-12 text-center text-gray-400 lowercase italic opacity-20">No products found.</td>
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
