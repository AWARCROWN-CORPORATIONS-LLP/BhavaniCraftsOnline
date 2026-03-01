@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Category Registry</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn-luxury-saffron px-6 py-2 text-[10px] shadow-xl">Add New Master Category</a>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Catalog Structure</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ count($categories) }} Nodes</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Mapping</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Level</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px]">Slug Registry</th>
                        <th class="p-8 text-[10px] font-black text-gray-400 uppercase tracking-[3px] text-right">Master Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50/50 transition-all group">
                            <td class="p-8">
                                <div class="flex items-center space-x-4">
                                    <div class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-400 group-hover:bg-[#ff9933]/10 group-hover:text-[#ff9933] transition-all">
                                        {{ strtoupper(substr($category->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest leading-none">{{ $category->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-8">
                                @if($category->parent_id)
                                    <span class="px-5 py-2 bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-[3px] rounded-full border border-blue-100">Child Node</span>
                                    <p class="mt-1 text-[9px] text-gray-300 font-bold uppercase tracking-widest leading-none">Parent: {{ $category->parent->name }}</p>
                                @else
                                    <span class="px-5 py-2 bg-[#ff9933]/5 text-[#ff9933] text-[9px] font-black uppercase tracking-[3px] rounded-full border border-[#ff9933]/20">Root Level</span>
                                @endif
                            </td>
                            <td class="p-8">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $category->slug }}</span>
                            </td>
                            <td class="p-8 text-right">
                                <div class="flex items-center justify-end space-x-4">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-gray-400 hover:text-[#ff9933] transition-colors"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg></a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Eradicate node from registry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors"><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-20 text-center text-gray-300 lowercase italic opacity-30">registry empty. begin foundation.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
