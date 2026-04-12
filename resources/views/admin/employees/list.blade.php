@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Staff Members</h2>
        <a href="{{ route('superadmin.employees.create') }}" class="btn-luxury-saffron px-6 py-2 text-[10px] shadow-xl">Add New Staff</a>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Internal Staff</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ $employees->total() }} Employees</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Name</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Contact</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Role</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $employee)
                        <tr x-data="{ 
                            isBlocked: {{ $employee->is_blocked ? 'true' : 'false' }}, 
                            loadingToggle: false, 
                            loadingDelete: false,
                            deleted: false,
                            async toggleAccess() {
                                if (this.loadingToggle || this.loadingDelete) return;
                                if (!confirm('Change access for this employee?')) return;
                                
                                this.loadingToggle = true;
                                try {
                                    const resp = await fetch('{{ route('superadmin.employees.toggle_block', [app()->getLocale(), $employee->id]) }}', {
                                        method: 'PATCH',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    });
                                    const body = await resp.json();
                                    if (body.success) {
                                        this.isBlocked = body.is_blocked;
                                    }
                                } catch (e) { console.error(e); } finally { this.loadingToggle = false; }
                            },
                            async deleteEmployee() {
                                if (this.loadingDelete || this.loadingToggle) return;
                                if (!confirm('Permanently DELETE this employee account?')) return;
                                
                                this.loadingDelete = true;
                                try {
                                    const resp = await fetch('{{ route('superadmin.employees.destroy', [app()->getLocale(), $employee->id]) }}', {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    });
                                    const body = await resp.json();
                                    if (body.success) {
                                        this.deleted = true;
                                    }
                                } catch (e) { console.error(e); } finally { this.loadingDelete = false; }
                            }
                        }" 
                        x-show="!deleted" 
                        x-transition.duration.500ms
                        class="hover:bg-gray-50/50 transition-all group">
                            <td class="p-8">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-400 group-hover:bg-[#ff9933]/10 group-hover:text-[#ff9933] transition-all overflow-hidden border border-gray-100 uppercase">
                                        {{ substr($employee->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest leading-none mb-1">{{ $employee->name }}</p>
                                        <p class="mt-1 text-[9px] text-gray-400 font-bold tracking-widest">{{ $employee->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-8">
                                <p class="text-[11px] font-bold text-gray-900 mb-1">{{ $employee->email }}</p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">{{ $employee->phone }}</p>
                            </td>
                            <td class="p-8">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded text-[9px] font-black uppercase tracking-[2px]" 
                                      :class="isBlocked ? 'opacity-50 grayscale' : ''">
                                    {{ str_replace('_', ' ', $employee->roles->first()?->name ?? 'None') }}
                                </span>
                            </td>
                            <td class="p-8 text-right">
                                <div class="flex items-center justify-end space-x-4">
                                    <!-- Instant Toggle -->
                                    <button @click="toggleAccess()" class="relative px-6 py-2.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all border min-w-[100px] flex items-center justify-center overflow-hidden"
                                            :class="isBlocked ? 'bg-green-50 text-green-600 border-green-200 hover:bg-green-100' : 'bg-red-50 text-red-600 border-red-200 hover:bg-red-100'">
                                        <div x-show="loadingToggle" class="absolute inset-0 flex items-center justify-center bg-inherit">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        </div>
                                        <span x-show="!loadingToggle" x-text="isBlocked ? 'Activate' : 'Block'"></span>
                                    </button>

                                    <!-- Instant Delete -->
                                    <button @click="deleteEmployee()" class="relative p-3 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors flex items-center justify-center" title="Delete Member">
                                        <div x-show="loadingDelete" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                        </div>
                                        <svg x-show="!loadingDelete" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                             <td colspan="4" class="p-20 text-center text-gray-400 lowercase italic opacity-30">no staff members found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($employees->hasPages())
            <div class="p-8 border-t border-gray-50">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

@endsection
