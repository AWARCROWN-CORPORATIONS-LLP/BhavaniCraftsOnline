@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">User Management</h2>
        <span class="bg-gray-100 text-gray-400 text-[9px] font-black uppercase tracking-[3px] px-4 py-1.5 rounded-full border border-gray-200">All Users</span>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Active Users</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ count($users) }} Users</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">User Profile</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Role & Access</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Contact Info</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Date Registered</th>
                        <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-400 group-hover:bg-[#1e40af]/10 group-hover:text-[#1e40af] transition-all">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest leading-none mb-1">{{ $user->name }}</p>
                                            @if($user->is_blocked)
                                                <span class="bg-red-500 text-white text-[7px] font-black uppercase px-2 py-0.5 rounded-full tracking-tighter">Suspended</span>
                                            @endif
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider leading-none">@ {{ $user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->hasRole('super_admin'))
                                    <span class="px-5 py-2 bg-purple-50 text-purple-600 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-purple-100 italic">Super Admin</span>
                                @elseif($user->hasRole('admin'))
                                    <span class="px-5 py-2 bg-orange-50 text-orange-600 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-orange-100">Administrator</span>
                                @elseif($user->hasRole('franchise'))
                                    <span class="px-5 py-2 bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-blue-100">Franchise Partner</span>
                                @else
                                    <span class="px-5 py-2 bg-gray-50 text-gray-400 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-gray-200">Customer</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <p class="text-[11px] text-gray-700 font-black leading-none">{{ $user->email }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">{{ $user->phone }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-black text-gray-900 uppercase tracking-widest">{{ $user->created_at->format('d M, Y') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(Auth::id() !== $user->id)
                                    @if(Auth::user()->hasRole('employee') && ($user->hasRole('super_admin') || $user->hasRole('admin') || $user->hasRole('franchise')))
                                        <span class="text-[9px] text-gray-300 font-black uppercase tracking-widest">System Account</span>
                                    @else
                                        <form action="{{ route('admin.toggle_block', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                class="px-5 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all duration-300
                                                {{ $user->is_blocked ? 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white border border-green-200' : 'bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200' }}">
                                                {{ $user->is_blocked ? 'Resume Access' : 'Suspend Access' }}
                                            </button>
                                        </form>
                                    @endif
                                @else
                                    <span class="text-[9px] text-gray-300 font-black uppercase tracking-widest">Current User</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center opacity-30">
                                <h4 class="text-xl font-black uppercase tracking-[10px] text-gray-400">No Users Found</h4>
                            </td>
                        </tr>

                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
