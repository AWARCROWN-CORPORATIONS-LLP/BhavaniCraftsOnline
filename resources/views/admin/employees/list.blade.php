@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Employee Portal</h2>
        <a href="{{ route('superadmin.employees.create') }}" class="btn-luxury-saffron px-6 py-2 text-[10px] shadow-xl">Grant Employee Access</a>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Internal Personnel</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ $employees->total() }} Employees</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Identity</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Contact Info</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Role</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Access Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50/50 transition-all group">
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
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 border border-blue-100 rounded text-[9px] font-black uppercase tracking-[2px]">Employee</span>
                            </td>
                            <td class="p-8 text-right">
                                <div class="flex items-center justify-end space-x-4">
                                    <form action="{{ route('superadmin.employees.toggle_block', $employee->id) }}" method="POST" class="inline" onsubmit="return confirm('Change access state for this employee?')">
                                        @csrf
                                        @method('PATCH')
                                        @if($employee->is_blocked)
                                            <button type="submit" class="px-4 py-2 bg-green-50 text-green-600 hover:bg-green-100 rounded-lg text-[9px] font-black uppercase tracking-widest transition-colors border border-green-200">
                                                Restore Access
                                            </button>
                                        @else
                                            <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-[9px] font-black uppercase tracking-widest transition-colors border border-red-200">
                                                Revoke Access
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-gray-400 lowercase italic opacity-30">no personnel registered.</td>
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
