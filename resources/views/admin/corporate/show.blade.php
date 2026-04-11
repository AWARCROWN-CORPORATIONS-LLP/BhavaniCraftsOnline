@extends('layouts.admin')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <div class="mb-10 flex items-center justify-between">
        <a href="{{ route('admin.corporate-requests.index') }}" class="inline-flex items-center space-x-3 text-white/50 hover:text-white transition-colors group">
            <svg class="h-4 w-4 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
            <span class="text-[10px] font-black uppercase tracking-widest">Back to Registry</span>
        </a>
        <div class="flex items-center space-x-3">
             <span class="text-[10px] font-black uppercase tracking-widest text-white/30 italic">Ref ID: #CORP-{{ str_pad($corporateRequest->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Card -->
        <div class="md:col-span-2 space-y-8">
            <div class="bg-white rounded-[3rem] p-12 shadow-2xl">
                <span class="px-4 py-2 bg-brand-50 hover:bg-brand-500 border border-brand-200 hover:border-brand-500 text-brand-600 hover:text-white text-[9px] font-black uppercase tracking-widest rounded-xl mb-6 inline-block transition-all">Institutional Inquiry</span>
                <h2 class="text-4xl font-serif font-bold text-onyx-950 italic mb-8 leading-tight">{{ $corporateRequest->company_name }}</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-3">Contact Person</p>
                        <p class="text-onyx-950 font-bold text-lg tracking-wide uppercase">{{ $corporateRequest->contact_person }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-3">Estimated Qty</p>
                        <p class="text-onyx-950 font-bold text-lg italic tracking-wider">{{ $corporateRequest->estimated_quantity ?? 'Unspecified' }} Units</p>
                    </div>
                </div>

                <div class="mt-12 pt-12 border-t border-gray-100">
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-6 italic">Requirement Message</p>
                    <div class="bg-gray-50 rounded-[2rem] p-8 border border-gray-100">
                        <p class="text-onyx-800 text-sm leading-relaxed italic">
                            {{ $corporateRequest->message ?? 'No specific technical requirement provided by the client.' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar / Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-8 border-b border-gray-50 pb-4">Communication</h3>
                <div class="space-y-6">
                    <a href="mailto:{{ $corporateRequest->email }}" class="flex flex-col group">
                        <span class="text-[9px] font-black uppercase tracking-widest text-brand-600 group-hover:text-brand-500">Official Email</span>
                        <span class="text-onyx-950 text-sm font-medium mt-1 truncate">{{ $corporateRequest->email }}</span>
                    </a>
                    <a href="tel:{{ $corporateRequest->phone }}" class="flex flex-col group">
                        <span class="text-[9px] font-black uppercase tracking-widest text-brand-600 group-hover:text-brand-500">Phone Hotline</span>
                        <span class="text-onyx-950 text-sm font-medium mt-1">{{ $corporateRequest->phone }}</span>
                    </a>
                </div>
            </div>

            <!-- Status Orchestration -->
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6">Master Control</h3>
                <form action="{{ route('admin.corporate-requests.status', $corporateRequest->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <select name="status" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-onyx-950 text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-brand-500/50 outline-none transition-all cursor-pointer">
                            <option value="pending" {{ $corporateRequest->status == 'pending' ? 'selected' : '' }}>Set as Pending</option>
                            <option value="contacted" {{ $corporateRequest->status == 'contacted' ? 'selected' : '' }}>Set as Contacted</option>
                            <option value="completed" {{ $corporateRequest->status == 'completed' ? 'selected' : '' }}>Set as Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-4 bg-onyx-950 hover:bg-emerald-600 text-white rounded-xl text-[9px] font-black uppercase tracking-widest transition-all">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
