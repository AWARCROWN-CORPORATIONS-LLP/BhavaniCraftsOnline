@extends('layouts.admin')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-10">
        <div>
            <h2 class="text-3xl font-serif font-bold text-white mb-2 italic">Corporate Catalog Requests</h2>
            <p class="text-white/50 text-[10px] font-black uppercase tracking-[3px]">Institutional & B2B Inquiry Management</p>
        </div>
        <div class="flex items-center space-x-4">
            <span class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-brand-400">
                Total Requests: {{ $requests->total() }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-xs font-bold uppercase tracking-widest">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/50">
                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Company / Contact</th>
                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Contact Info</th>
                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Received</th>
                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-gray-400">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50/50 transition-all group">
                        <td class="p-6">
                            <div class="flex flex-col">
                                <span class="text-onyx-950 font-bold text-sm tracking-wide group-hover:text-brand-600 transition-colors uppercase">{{ $request->company_name }}</span>
                                <span class="text-gray-400 text-[10px] font-medium tracking-widest mt-1">{{ $request->contact_person }}</span>
                            </div>
                        </td>
                        <td class="p-6">
                            <div class="flex flex-col space-y-1">
                                <span class="text-onyx-800 text-xs font-medium">{{ $request->email }}</span>
                                <span class="text-brand-600 text-[10px] font-black tracking-widest">{{ $request->phone }}</span>
                            </div>
                        </td>
                        <td class="p-6">
                            @if($request->status == 'pending')
                                <span class="px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-600 text-[9px] font-black uppercase tracking-widest rounded-lg">Pending Review</span>
                            @elseif($request->status == 'contacted')
                                <span class="px-3 py-1 bg-brand-500/10 border border-brand-500/20 text-brand-600 text-[9px] font-black uppercase tracking-widest rounded-lg">Contacted</span>
                            @else
                                <span class="px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-[9px] font-black uppercase tracking-widest rounded-lg">Completed</span>
                            @endif
                        </td>
                        <td class="p-6">
                            <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest">{{ $request->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="p-6 text-right">
                            <a href="{{ route('admin.corporate-requests.show', $request->id) }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-onyx-950 hover:bg-brand-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                                <span>View Details</span>
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <svg class="h-16 w-16 text-onyx-950 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                <span class="text-[10px] font-black uppercase tracking-[5px] text-onyx-950">No Corporate Inquiries Found</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">
        {{ $requests->links() }}
    </div>
</div>
@endsection
