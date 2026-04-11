@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.franchise-applications.index') }}" class="inline-flex items-center space-x-2 text-gray-400 hover:text-onyx-900 transition-colors font-black uppercase tracking-widest text-[10px]">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" /></svg>
            <span>Back to Applications</span>
        </a>
        <div class="flex items-center space-x-3">
             <span class="text-[10px] font-black uppercase tracking-[3px] text-gray-400">Application #{{ $application->id }}</span>
             <span class="h-2 w-2 rounded-full bg-brand-500 animate-pulse"></span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Applicant Details -->
        <div class="lg:col-span-2 space-y-8">
            <div class="card-premium p-8 lg:p-12 space-y-12">
                <div class="flex items-end justify-between border-b border-gray-100 pb-12">
                    <div>
                        <h1 class="text-4xl font-black text-onyx-900 uppercase tracking-widest leading-none">{{ $application->full_name }}</h1>
                        <p class="text-brand-500 font-bold uppercase tracking-[4px] text-[10px] mt-4">Prospective Partner</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-12">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Email Identity</p>
                        <p class="font-bold text-onyx-900 text-lg">{{ $application->email }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Phone Connectivity</p>
                        <p class="font-bold text-onyx-900 text-lg">{{ $application->phone }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Target Territory</p>
                        <p class="font-bold text-onyx-900 text-lg">{{ $application->location }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Registry Date</p>
                        <p class="font-bold text-onyx-900 text-lg">{{ $application->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="pt-12 border-t border-gray-100">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-6">Experience & Business Intent</p>
                    <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                         <p class="text-onyx-900 leading-relaxed font-medium whitespace-pre-wrap">{{ $application->experience }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Actions -->
        <div class="space-y-8">
            <div class="card-premium p-8 space-y-8">
                <h3 class="text-[10px] font-black text-onyx-900 uppercase tracking-[4px] border-b border-gray-100 pb-4">Decision Hub</h3>
                
                <form action="{{ route('admin.franchise-applications.status', $application->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Application Status</label>
                        <select name="status" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20">
                            <option value="pending" {{ $application->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                            <option value="reviewed" {{ $application->status == 'reviewed' ? 'selected' : '' }}>REVIEWED</option>
                            <option value="approved" {{ $application->status == 'approved' ? 'selected' : '' }}>APPROVED</option>
                            <option value="rejected" {{ $application->status == 'rejected' ? 'selected' : '' }}>REJECTED</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Internal Admin Notes</label>
                        <textarea name="admin_notes" rows="4" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-xs font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20" placeholder="Notes for corporate eyes only...">{{ $application->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-onyx-900 text-white text-[10px] font-black uppercase tracking-[3px] rounded-xl hover:bg-onyx-800 transition-all shadow-xl shadow-onyx-900/10">Synchronize Decision</button>
                </form>
            </div>

            <div class="p-8 bg-brand-50/50 border border-brand-100/50 rounded-[30px]">
                 <p class="text-[9px] font-black text-brand-600 uppercase tracking-widest mb-2">Protocol Note</p>
                 <p class="text-[10px] text-brand-700/70 font-medium leading-relaxed italic">Approving an application here does not automatically create a user. It marks the record as vetted for the HQ onboarding team to initiate legal contracts.</p>
            </div>
        </div>
    </div>
</div>
@endsection
