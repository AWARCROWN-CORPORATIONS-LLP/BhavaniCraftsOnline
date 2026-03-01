@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">HQ Partner Restocks</h2>
        <span class="bg-[#ff9933]/10 text-[#ff9933] text-[9px] font-black uppercase tracking-[3px] px-4 py-1.5 rounded-full">B2B Supply Chain</span>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Global Supply Requisitions</h3>
            <span class="px-4 py-1.5 bg-gray-100 text-gray-500 rounded-full text-[9px] font-black uppercase tracking-widest">{{ $requests->count() }} Live Requests</span>
        </div>

        <div class="p-8 space-y-6">
            @forelse($requests as $request)
                <div class="border rounded-2xl flex flex-col md:flex-row p-6 hover:shadow-md transition-shadow bg-white
                    {{ $request->priority == 'critical' ? 'border-red-200' : ($request->priority == 'urgent' ? 'border-amber-200' : 'border-gray-100') }}">
                    
                    <!-- Details Left -->
                    <div class="flex-1 md:pr-8 border-b md:border-b-0 md:border-r border-gray-100 mb-6 md:mb-0 pb-6 md:pb-0">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-xs font-black uppercase tracking-widest text-[#ff9933]">{{ $request->franchise->name ?? 'Unknown Partner' }}</h4>
                            <span class="text-[9px] font-bold text-gray-400">{{ $request->created_at->format('M d, Y - H:i') }}</span>
                        </div>

                        <h5 class="text-lg font-black text-gray-900 mb-1 leading-tight">{{ $request->product->product_name ?? 'Deleted Artifact' }}</h5>
                        
                        <div class="flex items-center space-x-6 mt-4">
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">Current Stock</p>
                                <p class="text-sm font-black text-gray-600">{{ $request->current_stock }} Units</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-[#ff9933] font-bold uppercase tracking-widest mb-1">Requested Fill</p>
                                <p class="text-sm font-black text-[#ff9933]">+{{ $request->requested_quantity }} Units</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-1">Threat Level</p>
                                <span class="text-[10px] font-black uppercase tracking-widest
                                    {{ $request->priority == 'critical' ? 'text-red-500' : ($request->priority == 'urgent' ? 'text-amber-500' : 'text-blue-500') }}">
                                    {{ $request->priority }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Right -->
                    <div class="md:w-1/3 md:pl-8 flex flex-col justify-center">
                        <form action="{{ route('admin.restocks.update', $request->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')
                            
                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">HQ Logistics Status</label>
                                <select name="status" class="w-full text-sm font-bold bg-gray-50 border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-[#ff9933]/20 transition-all text-gray-900" 
                                    {{ $request->status == 'shipped' ? 'disabled' : '' }}>
                                    <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Pending Audit</option>
                                    <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>HQ Approved (Sourcing)</option>
                                    <option value="shipped" {{ $request->status == 'shipped' ? 'selected' : '' }}>Shipped (Updates Partner Stock)</option>
                                </select>
                            </div>

                            <div>
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2 block">Supply Manifest Notes</label>
                                <input type="text" name="admin_notes" value="{{ $request->admin_notes }}" placeholder="e.g. ETA 3 Days, Partial shipment..." class="w-full text-xs font-medium bg-gray-50 border-none rounded-xl py-3 px-4 focus:ring-2 focus:ring-[#ff9933]/20 transition-all text-gray-700"
                                {{ $request->status == 'shipped' ? 'readonly' : '' }}>
                            </div>

                            @if($request->status != 'shipped')
                                <button type="submit" class="w-full py-3 bg-[#ff9933] text-white text-[10px] items-center justify-center font-black uppercase tracking-[3px] rounded-xl hover:bg-[#e68a2e] transition-all flex space-x-2 shadow-lg shadow-[#ff9933]/20">
                                    <span>Update Logistics Route</span>
                                </button>
                            @else
                                <div class="w-full py-3 bg-green-50 text-green-600 border border-green-100 text-[10px] flex items-center justify-center font-black uppercase tracking-[3px] rounded-xl cursor-not-allowed">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Logistics Fulfilled
                                </div>
                            @endif
                        </form>
                    </div>

                </div>
            @empty
                <div class="text-center py-20 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                    <div class="mx-auto h-16 w-16 bg-white text-gray-300 rounded-full flex items-center justify-center shadow-sm mb-4">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <h3 class="text-sm font-black text-gray-400 uppercase tracking-[4px]">No Supply Requests</h3>
                    <p class="text-[11px] font-bold text-gray-400 mt-2">All Franchise partners are currently fully stocked.</p>
                </div>
            @endforelse
        </div>

        @if($requests->hasPages())
            <div class="p-6 border-t border-gray-50 bg-gray-50/30">
                {{ $requests->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

@endsection
