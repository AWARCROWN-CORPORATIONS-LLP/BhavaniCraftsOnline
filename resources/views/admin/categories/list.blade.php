@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Category Registry</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn-luxury-saffron px-6 py-2 text-[10px] shadow-xl">+ New Category</a>
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
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Store Hierarchy</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ count($categories) }} Categories</span>
        </div>

        <!-- Category Grid with Images -->
        <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($categories as $category)
                    <div class="group relative bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 hover:border-[#1e40af]/30 hover:shadow-lg transition-all duration-300">
                        <!-- Image -->
                        <div class="relative h-44 w-full bg-gray-100 overflow-hidden">
                            @if($category->display_image)
                                <img src="{{ $category->display_image }}" alt="{{ $category->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                                    <div class="text-5xl font-black text-[#1e40af]/20">
                                        {{ strtoupper(substr($category->name, 0, 2)) }}
                                    </div>
                                </div>
                            @endif

                            <!-- Level Badge -->
                            <div class="absolute top-3 left-3">
                                @if($category->parent_id)
                                    <span class="px-2 py-1 bg-blue-500 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Sub</span>
                                @else
                                    <span class="px-2 py-1 bg-indigo-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Main</span>
                                @endif
                            </div>

                            <!-- Actions Overlay -->
                            <div class="absolute top-3 right-3 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                   class="h-8 w-8 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center text-gray-600 hover:text-[#1e40af] transition-colors shadow-sm">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this category?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="h-8 w-8 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center text-gray-600 hover:text-red-500 transition-colors shadow-sm">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="p-5">
                            <p class="text-sm font-black text-gray-900 uppercase tracking-wide mb-1">{{ $category->name }}</p>
                            @if($category->parent_id && $category->parent)
                                <p class="text-[10px] text-gray-400 font-semibold">↳ {{ $category->parent->name }}</p>
                            @endif
                            <p class="text-[10px] text-gray-300 font-bold mt-1 font-mono">/{{ $category->slug }}</p>
                        </div>

                        <!-- Edit Link (full card) -->
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="absolute inset-0" aria-label="Edit {{ $category->name }}"></a>
                    </div>
                @empty
                    <div class="col-span-4 p-20 text-center text-gray-300 italic">
                        No categories yet. <a href="{{ route('admin.categories.create') }}" class="text-[#1e40af] underline">Create your first category</a>.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

@endsection
