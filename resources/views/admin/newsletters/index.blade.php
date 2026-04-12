@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Newsletter Subscribers</h2>
        <span class="bg-gray-100 text-gray-400 text-[9px] font-black uppercase tracking-[3px] px-4 py-1.5 rounded-full border border-gray-200">Sacred Digest Hub</span>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Registered Email Registry</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ $subscribers->total() }} Subscribers</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Subscriber Email</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Date Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($subscribers as $subscriber)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="px-6 py-4">
                                <span class="text-[12px] font-black text-gray-900 uppercase tracking-widest">{{ $subscriber->email }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($subscriber->status === 'active')
                                    <span class="px-5 py-2 bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-green-100 italic">Active</span>
                                @else
                                    <span class="px-5 py-2 bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-gray-200">Unsuscribed</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ $subscriber->created_at->format('d M, Y (H:i)') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center opacity-30">
                                <h4 class="text-xl font-black uppercase tracking-[10px] text-gray-400">No Subscribers Found</h4>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="p-6 border-t border-gray-50 bg-gray-50/10">
                {{ $subscribers->links() }}
            </div>
        @endif
    </div>

@endsection
