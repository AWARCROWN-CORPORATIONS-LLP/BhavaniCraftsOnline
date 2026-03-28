@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Broadcast Center</h2>
        <a href="{{ route('employee.broadcasts.create') }}" class="btn-luxury px-6 py-2 text-[10px] shadow-xl">+ New Announcement</a>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Internal Communications</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ count($broadcasts) }} Messages</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left uppercase tracking-widest">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Date</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Title</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Message</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Status</th>
                        <th class="px-8 py-5 text-[9px] font-black text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($broadcasts as $broadcast)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-6 text-[10px] font-bold text-gray-400">
                            {{ $broadcast->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-8 py-6 text-[11px] font-black text-gray-900">
                            {{ $broadcast->title }}
                        </td>
                        <td class="px-8 py-6 text-[10px] text-gray-500 normal-case">
                            {{ Str::limit($broadcast->message, 100) }}
                        </td>
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] 
                                {{ $broadcast->is_active ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                {{ $broadcast->is_active ? 'Published' : 'Offline' }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('employee.broadcasts.toggle', $broadcast->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-[9px] font-black uppercase text-sky-500 hover:text-sky-700">Toggle Access</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center text-gray-300 italic">No broadcast messages yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
