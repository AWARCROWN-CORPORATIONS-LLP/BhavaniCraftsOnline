@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Store Branches</h2>
        <a href="{{ route('admin.branches.create') }}" class="btn-luxury-saffron px-6 py-2 text-[10px] shadow-xl">+ New Branch</a>
    </div>
@endsection

@section('content')

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-semibold">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Our Locations</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ count($branches) }} Branches</span>
        </div>

        <div class="p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50">
                            <th class="py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Sort</th>
                            <th class="py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Branch Name</th>
                            <th class="py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">City</th>
                            <th class="py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Contact</th>
                            <th class="py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">Status</th>
                            <th class="py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                <td class="py-4 font-mono text-xs text-gray-400">{{ $branch->sort_order }}</td>
                                <td class="py-4">
                                    <p class="text-sm font-bold text-gray-900">{{ $branch->name }}</p>
                                    <p class="text-[10px] text-gray-400 italic line-clamp-1 max-w-xs">{{ $branch->address }}</p>
                                </td>
                                <td class="py-4 text-xs font-semibold text-gray-600">{{ $branch->city ?? 'N/A' }}</td>
                                <td class="py-4">
                                    <p class="text-[10px] font-bold text-gray-500">{{ $branch->phone }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $branch->email }}</p>
                                </td>
                                <td class="py-4">
                                    @if($branch->is_active)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 text-[9px] font-black uppercase tracking-widest rounded-lg">Active</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-400 text-[9px] font-black uppercase tracking-widest rounded-lg">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-4 text-right space-x-2">
                                    <a href="{{ route('admin.branches.edit', $branch->id) }}" class="inline-flex h-8 w-8 items-center justify-center bg-white rounded-lg border border-gray-100 text-gray-400 hover:text-indigo-600 hover:border-indigo-100 shadow-sm transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.branches.destroy', $branch->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this branch?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="h-8 w-8 inline-flex items-center justify-center bg-white rounded-lg border border-gray-100 text-gray-400 hover:text-red-500 hover:border-red-100 shadow-sm transition-all">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-gray-300 italic">No branches found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
