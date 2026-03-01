@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Global Broadcast</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Transmission Registry</p>
    </div>
    
    <a href="{{ route('admin.broadcasts.create') }}" class="btn-luxury-saffron px-6 py-2.5 text-[10px]">
        Initiate New Broadcast
    </a>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Active Transmissions</h3>
            <span class="px-4 py-1.5 bg-[#ff9933]/10 text-[#ff9933] rounded-full text-[9px] font-black uppercase tracking-widest">{{ $broadcasts->total() }} Logged</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100">
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Broadcast Identity</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Target Audience</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Urgency Level</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Status</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[4px] text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50">
                    @forelse($broadcasts as $broadcast)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="p-8">
                                <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $broadcast->title }}</h4>
                                <p class="text-[10px] text-gray-400 font-medium truncate max-w-xs">{{ Str::limit($broadcast->message, 80) }}</p>
                            </td>
                            <td class="p-8">
                                @if($broadcast->target_audience == 'all')
                                    <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-md text-[9px] font-black uppercase tracking-widest">Global Network</span>
                                @elseif($broadcast->target_audience == 'exact:employee')
                                    <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-md text-[9px] font-black uppercase tracking-widest">Internal Employees</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-md text-[9px] font-black uppercase tracking-widest">Franchise Partners</span>
                                @endif
                            </td>
                            <td class="p-8">
                                @if($broadcast->urgency == 'critical')
                                    <span class="flex items-center space-x-2 text-red-500">
                                        <span class="h-2 w-2 rounded-full bg-red-500 animate-pulse"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-red-600">Critical</span>
                                    </span>
                                @elseif($broadcast->urgency == 'warning')
                                    <span class="flex items-center space-x-2 text-amber-500">
                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-amber-600">Warning</span>
                                    </span>
                                @else
                                    <span class="flex items-center space-x-2 text-blue-500">
                                        <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600">Info</span>
                                    </span>
                                @endif
                            </td>
                            <td class="p-8">
                                <form action="{{ route('admin.broadcasts.toggle', $broadcast->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[2px] transition-all 
                                        {{ $broadcast->is_active ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-gray-100 text-gray-400' }}">
                                        {{ $broadcast->is_active ? 'Transmitting' : 'Offline' }}
                                    </button>
                                </form>
                            </td>
                            <td class="p-8 text-right">
                                <div class="flex items-center justify-end space-x-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('admin.broadcasts.edit', $broadcast->id) }}" class="text-[#ff9933] hover:text-[#fb8c00] transition-colors p-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.broadcasts.destroy', $broadcast->id) }}" method="POST" class="inline" onsubmit="return confirm('Eradicate this transmission permanently?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-400 hover:text-red-600 transition-colors p-2">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-16 text-center text-gray-400">
                                <p class="text-xs uppercase tracking-[4px] font-black">No Active Transmissions</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 border-t border-gray-50 flex justify-between items-center bg-gray-50/30">
            {{ $broadcasts->links('pagination::tailwind') }}
        </div>
    </div>

@endsection
