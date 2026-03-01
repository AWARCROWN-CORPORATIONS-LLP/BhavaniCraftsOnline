@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Business Registry</h2>
        <span class="bg-[#ff9933]/10 text-[#ff9933] text-[9px] font-black uppercase tracking-[3px] px-4 py-1.5 rounded-full border border-[#ff9933]/20">Approval Needed</span>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Franchise Applicants</h3>
            <div class="flex items-center space-x-2">
                <div class="h-2 w-2 bg-yellow-400 rounded-full animate-pulse shadow-xl shadow-yellow-400/50"></div>
                <span class="text-[9px] font-black uppercase text-gray-300">Live Status</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Identity</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Contact Info</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Request Date</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Permissions</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Master Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($businessAccounts as $user)
                        <tr class="hover:bg-gray-50/50 transition-all duration-300 group">
                            <td class="p-8">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-400 group-hover:bg-[#ff9933]/10 group-hover:text-[#ff9933] transition-all">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest leading-none mb-1">{{ $user->name }}</p>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider leading-none">@ {{ $user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-8">
                                <div class="space-y-1">
                                    <p class="text-[11px] text-gray-700 font-black leading-none">{{ $user->email }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">{{ $user->phone }}</p>
                                </div>
                            </td>
                            <td class="p-8">
                                <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ $user->created_at->format('d M, Y') }}</span>
                            </td>
                            <td class="p-8">
                                @if($user->is_approved)
                                    <span class="px-5 py-2 bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-green-100 shadow-sm shrink-0">Security Cleared</span>
                                @else
                                    <span class="px-5 py-2 bg-red-50 text-red-600 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-red-100 shadow-sm animate-pulse shrink-0">Access Blocked</span>
                                @endif
                            </td>
                            <td class="p-8 text-right">
                                @if(!Auth::user()->hasRole('employee'))
                                    @if(!$user->is_approved)
                                        <form action="{{ route('admin.approve_franchise', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button class="btn-luxury-saffron px-8 py-3 text-[10px] shadow-2xl">Elevate Status</button>
                                        </form>
                                    @else
                                        <button disabled class="px-8 py-3 bg-gray-100 text-gray-400 rounded-xl font-black uppercase tracking-[2px] text-[10px] cursor-not-allowed">Active Entity</button>
                                    @endif
                                @else
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Restricted Clearance</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-20 text-center">
                                <div class="flex flex-col items-center opacity-30">
                                    <svg class="h-20 w-20 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    <h4 class="text-xl font-black uppercase tracking-[10px] text-gray-400">Registry Clear</h4>
                                    <p class="text-[10px] font-bold uppercase tracking-widest mt-4">No pending business applications found.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
