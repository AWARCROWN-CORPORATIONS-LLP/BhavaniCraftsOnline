@extends('layouts.employee')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight text-brand">Inventory</h2>
@endsection

@section('content')

    <div class="mb-10 flex items-center justify-between">
        <div class="space-y-1">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[4px] leading-none">Product Management</h3>
            <p class="text-[10px] items-center font-bold text-sky-500 uppercase tracking-[2px]">Current Catalog</p>
        </div>
        <a href="{{ route('employee.products.create') }}" class="btn-luxury px-10 py-5 text-[11px]">Add New Product</a>
    </div>

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-2xl shadow-sky-900/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Product Name</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Category</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Availability</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Stock Status</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Visibility</th>
                        <th class="px-8 py-5 text-[9px] font-black uppercase tracking-[4px] text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 uppercase tracking-widest">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center font-black text-xs text-gray-400">
                                    {{ strtoupper(substr($product->product_name, 0, 1)) }}
                                </div>
                                <span class="text-[11px] font-black text-gray-900">{{ $product->product_name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[10px] font-bold text-gray-500">{{ $product->category->name ?? 'None' }}</span>
                        </td>
                        <td class="px-8 py-6">
                             <span class="text-[10px] font-bold text-gray-400 italic">Restricted</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[11px] font-bold {{ $product->stock < 10 ? 'text-red-500' : 'text-gray-900' }}">{{ $product->stock }} Units</span>
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] 
                                {{ $product->listed_status == 'Listed' ? 'bg-green-100 text-green-600' : 
                                   ($product->listed_status == 'Draft' ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') }}">
                                {{ $product->listed_status }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                             <a href="{{ route('employee.products.edit', $product->id) }}" class="text-[10px] font-black uppercase tracking-[3px] text-sky-500 hover:text-sky-700">Edit</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-8 bg-gray-50 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    </div>

@endsection
