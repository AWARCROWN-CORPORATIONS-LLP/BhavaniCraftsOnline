@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Inventory Levels</h2>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between bg-white sticky top-0 z-10">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none mb-2">Restock Requests</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">{{ $restocks->total() }} Pending Requests</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left uppercase tracking-widest">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Date</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Product</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Current Stock</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Requested Status</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($restocks as $restock)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6 text-[10px] font-bold text-gray-400">
                             {{ $restock->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col space-y-1">
                                <span class="text-[11px] font-black text-gray-900">{{ $restock->product->product_name ?? 'Unknown' }}</span>
                                <span class="text-[9px] font-bold text-gray-400">#INV-{{ $restock->product_id }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-[11px] font-bold {{ $restock->product->stock < 10 ? 'text-red-500' : 'text-gray-900' }}">
                            {{ $restock->product->stock }} Units
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] 
                                {{ $restock->status == 'Approved' ? 'bg-green-100 text-green-600' : 
                                   ($restock->status == 'Rejected' ? 'bg-red-100 text-red-600' : 'bg-sky-100 text-sky-600') }}">
                                {{ $restock->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('employee.restocks.update', $restock->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-[9px] font-black uppercase text-sky-500 hover:text-sky-700">Update Stock Hub</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center text-gray-300 italic uppercase">No inventory requests pending for fulfillment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($restocks->hasPages())
            <div class="p-8 bg-gray-50 border-t border-gray-100 italic">
                {{ $restocks->links() }}
            </div>
        @endif
    </div>

@endsection
