@extends('layouts.admin')

@section('header_extra')
    <h2 class="text-xl lg:text-2xl font-black text-gray-900 uppercase tracking-tight">Audit Registry</h2>
@endsection

@section('content')
<main class="lg:ml-[280px] p-6 lg:p-10 transition-all duration-300">
    <!-- Header Section -->
    <div class="mb-8 border-b pl-2 pb-4 border-gray-200">
        <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tight">System Telemetry</h1>
        <p class="text-gray-500 font-medium tracking-wide mt-1">Immutable ledger of platform activities and data mutations.</p>
    </div>

    <!-- Filters -->
    <div class="card-premium p-6 mb-8 bg-white/80 backdrop-blur-xl border border-gray-100 shadow-xl rounded-3xl">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Actor ID (User)</label>
                <div class="relative">
                    <input type="text" name="user_id" value="{{ request('user_id') }}" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-gray-900 focus:ring-4 focus:ring-[#ff9933]/20 focus:border-[#ff9933] transition-all font-semibold outline-none shadow-inner" placeholder="Enter User ID">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Action Vector</label>
                <div class="relative">
                    <select name="action" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-gray-900 focus:ring-4 focus:ring-[#ff9933]/20 focus:border-[#ff9933] transition-all font-semibold outline-none appearance-none shadow-inner cursor-pointer">
                        <option value="">All Actions</option>
                        <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>Created</option>
                        <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>Updated</option>
                        <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>Deleted</option>
                    </select>
                    <svg class="h-5 w-5 text-gray-400 absolute right-4 top-3.5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Target Entity Class</label>
                <input type="text" name="auditable_type" value="{{ request('auditable_type') }}" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl px-4 py-3 text-gray-900 focus:ring-4 focus:ring-[#ff9933]/20 focus:border-[#ff9933] transition-all font-semibold outline-none shadow-inner" placeholder="e.g. Order">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-[#ff9933] hover:bg-[#e68a2e] text-white px-6 py-3 rounded-2xl font-black uppercase tracking-widest transition-all shadow-[0_10px_20px_rgba(255,153,51,0.3)] hover:shadow-[0_15px_30px_rgba(255,153,51,0.4)] hover:-translate-y-1">
                    Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="card-premium overflow-hidden bg-white shadow-2xl rounded-3xl border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Timestamp</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Actor Profile</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Action</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Target Entity</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Payload Diff</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <span class="text-gray-900 font-bold whitespace-nowrap">{{ $log->created_at->format('M d, Y') }}</span><br>
                                <span class="text-gray-400 text-xs font-medium">{{ $log->created_at->format('h:i:s A') }}</span><br>
                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-gray-500 rounded text-[9px] font-mono tracking-wider">{{ $log->ip_address }}</span>
                            </td>
                            <td class="px-8 py-6">
                                @if($log->user)
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 flex-shrink-0 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black uppercase">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-gray-900 font-bold">{{ $log->user->name }}</span>
                                            <span class="text-gray-500 text-xs">{{ $log->user->email }}</span>
                                            <span class="text-[#ff9933] text-[9px] font-black uppercase tracking-widest mt-1">{{ $log->user->role }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center space-x-3">
                                        <div class="h-10 w-10 flex-shrink-0 bg-gray-100 text-gray-400 rounded-xl flex items-center justify-center">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        </div>
                                        <span class="text-gray-400 italic font-medium">System Core</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $actionColors = [
                                        'created' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                        'updated' => 'bg-[#ff9933]/10 text-[#ff9933] border-[#ff9933]/30',
                                        'deleted' => 'bg-red-50 text-red-600 border-red-200',
                                    ];
                                    $colorClass = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                                @endphp
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-[2px] border {{ $colorClass }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-bold mb-1">{{ class_basename($log->auditable_type) }}</span>
                                    <span class="text-gray-500 text-xs font-mono bg-gray-100 px-2 py-1 rounded inline-flex w-max shadow-inner">ID: {{ $log->auditable_id }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 max-w-sm">
                                <div x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center space-x-2 text-[#ff9933] hover:text-[#e68a2e] bg-[#ff9933]/10 hover:bg-[#ff9933]/20 px-3 py-2 rounded-xl text-[10px] uppercase tracking-widest font-black transition-colors">
                                        <span>Inspect Diff</span>
                                        <svg x-show="!open" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                                        <svg x-show="open" class="h-3 w-3" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" /></svg>
                                    </button>
                                    
                                    <div x-show="open" class="mt-4 p-5 bg-gray-900 rounded-2xl overflow-x-auto custom-scrollbar shadow-xl border border-gray-800" style="display: none;" x-transition>
                                        @if($log->old_values)
                                            <div class="mb-4">
                                                <span class="text-red-400 text-[9px] uppercase font-black tracking-[3px] flex items-center">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500 mr-2"></span> Previous State
                                                </span>
                                                <pre class="text-gray-300 text-[11px] font-mono mt-2 leading-relaxed opacity-90">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </div>
                                        @endif
                                        
                                        @if($log->new_values)
                                            <div class="pt-3 @if($log->old_values) border-t border-gray-800 @endif">
                                                <span class="text-emerald-400 text-[9px] uppercase font-black tracking-[3px] flex items-center">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 mr-2"></span> New State
                                                </span>
                                                <pre class="text-white text-[11px] font-mono mt-2 leading-relaxed drop-shadow-sm">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="h-16 w-16 bg-gray-50 text-gray-300 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">No telemetry recorded yet.</p>
                                    <p class="text-sm text-gray-500 mt-1 font-medium">The system is currently observing a period of stasis.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($logs->hasPages())
            <div class="bg-gray-50 p-6 border-t border-gray-100 flex justify-end">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
