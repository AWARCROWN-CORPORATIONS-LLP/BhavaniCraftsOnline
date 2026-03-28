@extends('layouts.employee')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">New Category</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] font-black text-sky-500 uppercase tracking-[4px]">Management Portal</p>
    </div>
@endsection

@section('content')

    <div class="max-w-3xl mx-auto">
        <form action="{{ route('employee.categories.store') }}" method="POST" enctype="multipart/form-data" class="card-premium p-10 space-y-10">
            @csrf

            <div class="space-y-6">
                <div class="flex items-center space-x-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Category Info</h3>
                    <div class="flex-grow h-px bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Category Name *</label>
                        <input type="text" name="name" required value="{{ old('name') }}"
                               placeholder="e.g. Divine Brass Idols"
                               class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/30">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block leading-none">Parent Category</label>
                        <select name="parent_id" class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/30">
                            <option value="">— No Parent / Top-level —</option>
                            @foreach($categories as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-4">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Visual Image</h3>
                    <div class="flex-grow h-px bg-gray-100"></div>
                </div>

                <div x-data="{ preview: null }" class="space-y-4">
                    <label for="image-upload"
                           class="relative flex flex-col items-center justify-center w-full h-56 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-sky-500/40 transition-all group overflow-hidden">

                        <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-cover rounded-2xl">
                        <div x-show="preview" class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-xs font-bold">Change Image</span>
                        </div>

                        <div x-show="!preview" class="flex flex-col items-center space-y-3 text-gray-400">
                            <div class="h-14 w-14 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-sky-500/10 transition-colors">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-gray-600">Select Image File</p>
                                <p class="text-xs text-gray-400 mt-1 italic">PNG, JPG, WebP — Max 2MB</p>
                            </div>
                        </div>

                        <input id="image-upload" type="file" name="image" accept="image/*" class="sr-only"
                               @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>

                    <div>
                        <label class="text-[10px] font-black text-gray-500 tracking-[2px] uppercase mb-2 block leading-none">External Image URL</label>
                        <input type="url" name="icon_url" value="{{ old('icon_url') }}"
                               placeholder="https://example.com/image.jpg"
                               class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/30">
                    </div>
                </div>
            </div>

            <div class="pt-6 flex items-center justify-between border-t border-gray-50">
                <a href="{{ route('employee.categories.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">← Cancel Process</a>
                <button type="submit" class="btn-luxury px-10 py-4 text-[11px] shadow-lg">Create Category</button>
            </div>
        </form>
    </div>

@endsection
