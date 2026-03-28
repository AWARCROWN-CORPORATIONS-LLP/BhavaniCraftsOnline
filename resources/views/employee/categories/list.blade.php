@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Categories</h2>
        <a href="{{ route('employee.categories.create') }}" class="btn-luxury px-6 py-2 text-[10px] shadow-xl">+ New Category</a>
    </div>
@endsection

@section('content')

    <div class="card-premium overflow-hidden">
        <div class="p-8 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Category Hierarchy</h3>
            <span class="text-[9px] font-black uppercase text-gray-300">Total: {{ count($categories) }} Categories</span>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @if($categories->count() > 0)
                    @foreach($categories as $category)
                        <div class="group relative bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 hover:border-sky-500/30 hover:shadow-lg transition-all duration-300">
                            <!-- ... category content ... -->
                            <div class="relative h-44 w-full bg-gray-100 overflow-hidden">
                                @if($category->display_image)
                                    <img src="{{ $category->display_image }}" alt="{{ $category->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-sky-50">
                                        <div class="text-5xl font-black text-sky-500/20">
                                            {{ strtoupper(substr($category->name, 0, 2)) }}
                                        </div>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3">
                                    @if($category->parent_id)
                                        <span class="px-2 py-1 bg-sky-100 text-sky-600 text-[9px] font-black uppercase tracking-widest rounded-lg">Sub</span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-900 text-white text-[9px] font-black uppercase tracking-widest rounded-lg">Main</span>
                                    @endif
                                </div>

                                <div class="absolute top-3 right-3 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('employee.categories.edit', $category->id) }}"
                                       class="h-8 w-8 bg-white/90 backdrop-blur-sm rounded-lg flex items-center justify-center text-gray-600 hover:text-sky-500 transition-colors shadow-sm">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                </div>
                            </div>

                            <div class="p-5">
                                <p class="text-sm font-black text-gray-900 uppercase tracking-wide mb-1">{{ $category->name }}</p>
                                @if($category->parent_id && $category->parent)
                                    <p class="text-[10px] text-gray-400 font-semibold italic">Part of: {{ $category->parent->name }}</p>
                                @endif
                                <p class="text-[10px] text-sky-500 font-bold mt-1 font-mono">/{{ $category->slug }}</p>
                                <p class="text-[9px] text-gray-400 font-bold mt-2 uppercase tracking-widest">{{ $category->products_count }} Products</p>
                            </div>

                            <a href="{{ route('employee.categories.edit', $category->id) }}" class="absolute inset-0" aria-label="Edit {{ $category->name }}"></a>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-4 p-20 text-center text-gray-300 italic">
                        No categories found.
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
