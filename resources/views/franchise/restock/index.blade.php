@extends('layouts.franchise')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Automated Restock</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Headquarters Supply</p>
    </div>
    
    <a href="{{ route('franchise.restock.create') }}" class="btn-luxury-saffron px-6 py-2.5 text-[10px]">
        Signal Restock Request
    </a>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Supply Manifests</h3>
            <span class="px-4 py-1.5 bg-[#ff9933]/10 text-[#ff9933] rounded-full text-[9px] font-black uppercase tracking-widest">{{ $requests->total() }} Logged</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Artifact Identity</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Volume Req.</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Threat Level</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">HQ Status</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px] text-right">Chronology</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="p-8">
                                <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $request->product->product_name ?? '[Deleted Product]' }}</h4>
                                <p class="text-[10px] text-gray-400 font-medium truncate max-w-xs">Existing Stock: {{ $request->current_stock }} Units</p>
                            </td>
                            <td class="p-8">
                                <span class="text-[12px] font-black text-gray-900">+{{ $request->requested_quantity }} Units</span>
                            </td>
                            <td class="p-8">
                                @if($request->priority == 'critical')
                                    <span class="flex items-center space-x-2 text-red-500">
                                        <span class="h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-red-600">Inventory Crisis</span>
                                    </span>
                                @elseif($request->priority == 'urgent')
                                    <span class="flex items-center space-x-2 text-amber-500">
                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-600">Urgent Fill</span>
                                    </span>
                                @else
                                    <span class="flex items-center space-x-2 text-blue-500">
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600">Standard Run</span>
                                    </span>
                                @endif
                            </td>
                            <td class="p-8">
                                <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[2px] 
                                    @if($request->status == 'pending') bg-gray-50 text-gray-500 border border-gray-200
                                    @elseif($request->status == 'approved') bg-yellow-50 text-yellow-600 border border-yellow-200
                                    @elseif($request->status == 'shipped') bg-green-50 text-green-600 border border-green-200
                                    @endif">
                                    {{ $request->status }}
                                </span>
                                @if($request->admin_notes)
                                    <p class="text-[9px] text-gray-400 mt-2 italic max-w-xs truncate" title="{{ $request->admin_notes }}">HQ: {{ $request->admin_notes }}</p>
                                @endif
                            </td>
                            <td class="p-8 text-right">
                                <div class="text-[11px] font-bold text-gray-400">{{ $request->created_at->format('M d, Y') }}</div>
                                <div class="text-[9px] text-gray-300 font-medium">{{ $request->created_at->format('H:i') }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center text-gray-400">
                                <p class="text-xs uppercase tracking-[4px] font-black">No Active Supply Requests</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-50 flex justify-between items-center bg-gray-50/30">
            {{ $requests->links('pagination::tailwind') }}
        </div>
    </div>

@endsection
