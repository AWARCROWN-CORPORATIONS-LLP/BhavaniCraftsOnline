@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Expand Registry</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Category Master Node</p>
    </div>
@endsection

@section('content')

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.categories.store') }}" method="POST" class="card-premium p-12 space-y-12">
            @csrf

            <!-- CORE IDENTITY -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Node Identity</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">English Designation</label>
                        <input type="text" name="name" required placeholder="Copper Ritual Utensils" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Hierarchy Root</label>
                        <select name="parent_id" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                            <option value="">Set as Root Node</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- VISUAL REGISTRY (FOR FUTURE) -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Artifact Visual Logic</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Icon Registry URL (Optional)</label>
                    <input type="text" name="icon_url" placeholder="/assets/icons/divine-lamp.svg" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                </div>
            </div>

            <div class="pt-10 flex items-center justify-between">
                <a href="{{ route('admin.categories.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Abort Registry</a>
                <button type="submit" class="btn-luxury-saffron px-12 py-5 text-[11px] shadow-2xl">Confirm & Commit Node</button>
            </div>
        </form>
    </div>

@endsection
