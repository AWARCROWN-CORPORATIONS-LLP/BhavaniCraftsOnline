@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black text-onyx-900 uppercase tracking-widest">Partner Applications</h1>
            <p class="text-gray-400 font-bold text-[10px] uppercase tracking-[4px] mt-2">Managing potential franchise partners</p>
        </div>
    </div>

    <div class="card-premium p-8 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest">Applicant</th>
                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest">Location</th>
                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest">Status</th>
                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest">Applied On</th>
                    <th class="pb-6 text-[10px] font-black uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm font-medium">
                @foreach($applications as $app)
                <tr class="group hover:bg-gray-50/50 transition-colors">
                    <td class="py-6">
                        <p class="font-black text-onyx-900 uppercase tracking-widest">{{ $app->full_name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold tracking-wider">{{ $app->email }}</p>
                        <p class="text-[10px] text-gray-400 font-bold tracking-wider">{{ $app->phone }}</p>
                    </td>
                    <td class="py-6">
                        <span class="px-3 py-1 bg-gray-100 rounded-full text-[10px] uppercase font-black text-gray-600 tracking-tighter">{{ $app->location }}</span>
                    </td>
                    <td class="py-6">
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'reviewed' => 'bg-blue-100 text-blue-700',
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusColors[$app->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $app->status }}
                        </span>
                    </td>
                    <td class="py-6 text-gray-400 tabular-nums">
                        {{ $app->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-6 text-right">
                        <a href="{{ route('admin.franchise-applications.show', $app->id) }}" class="inline-flex items-center space-x-2 text-[#1e40af] hover:text-[#1e3a8a] transition-colors font-black uppercase tracking-widest text-[10px]">
                            <span>Review Details</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="mt-8">
            {{ $applications->links() }}
        </div>
    </div>
</div>
@endsection
