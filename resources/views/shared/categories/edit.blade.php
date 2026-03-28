@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Update Image</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] font-black text-[#ff9933] uppercase tracking-[4px]">{{ $category->name }}</p>
    </div>
@endsection

@section('content')

    <div class="max-w-2xl mx-auto">
        @php
            $user = auth()->user();
            $roles = $user->roles->pluck('name')->toArray();
            if (in_array('franchise', $roles)) {
                $updateRoute = route('franchise.category-images.update', $category->id);
                $backRoute   = route('franchise.category-images.index');
            } else {
                $updateRoute = route('employee.category-images.update', $category->id);
                $backRoute   = route('employee.category-images.index');
            }
        @endphp

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-semibold">
                ✓ {{ session('success') }}
            </div>
        @endif

        <form action="{{ $updateRoute }}" method="POST" enctype="multipart/form-data" class="card-premium p-10 space-y-8">
            @csrf

            @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <!-- Category Info -->
            <div class="flex items-center space-x-4 pb-6 border-b border-gray-100">
                @if($category->display_image)
                    <div class="h-16 w-16 rounded-2xl overflow-hidden border border-gray-100">
                        <img src="{{ $category->display_image }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center text-2xl font-black text-gray-300">
                        {{ strtoupper(substr($category->name, 0, 2)) }}
                    </div>
                @endif
                <div>
                    <p class="text-lg font-black text-gray-900">{{ $category->name }}</p>
                    <p class="text-xs text-gray-400 font-mono">/{{ $category->slug }}</p>
                </div>
            </div>

            <!-- Image Upload -->
            <div class="space-y-5">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[4px]">Upload New Image</h3>

                <div x-data="{ preview: '{{ $category->display_image }}' }">
                    <label for="cat-image-upload"
                           class="relative flex flex-col items-center justify-center w-full h-64 rounded-2xl border-2 border-dashed border-gray-200 cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-[#ff9933]/40 transition-all group overflow-hidden">

                        <img x-show="preview" :src="preview" class="absolute inset-0 w-full h-full object-cover rounded-2xl">
                        <div x-show="preview" class="absolute inset-0 bg-black/40 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white text-sm font-bold bg-black/30 px-4 py-2 rounded-xl backdrop-blur-sm">Click to replace</span>
                        </div>

                        <div x-show="!preview" class="flex flex-col items-center space-y-3 text-gray-400">
                            <div class="h-16 w-16 bg-gray-100 rounded-2xl flex items-center justify-center group-hover:bg-[#ff9933]/10 transition-colors">
                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-bold text-gray-600">Drop image here or click to upload</p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG, GIF, WebP — Max 2MB</p>
                            </div>
                        </div>

                        <input id="cat-image-upload" type="file" name="image" accept="image/*" class="sr-only"
                               @change="preview = URL.createObjectURL($event.target.files[0])">
                    </label>

                    @if($category->image_path)
                    <label class="flex items-center space-x-3 cursor-pointer mt-4">
                        <input type="checkbox" name="clear_image" value="1" class="h-4 w-4 text-red-500 rounded border-gray-200 focus:ring-red-400"
                               @change="if ($event.target.checked) preview = null">
                        <span class="text-xs font-semibold text-gray-500">Remove current image</span>
                    </label>
                    @endif
                </div>

                <!-- Icon URL Fallback -->
                <div>
                    <label class="text-[10px] font-black text-gray-500 tracking-[2px] uppercase mb-2 block">Or use an external image URL</label>
                    <input type="url" name="icon_url" value="{{ $category->icon_url }}"
                           placeholder="https://example.com/category-image.jpg"
                           class="w-full bg-gray-50 border-none px-6 py-4 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/30">
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-6 flex items-center justify-between border-t border-gray-50">
                <a href="{{ $backRoute }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">← Back</a>
                <button type="submit" class="btn-luxury-saffron px-10 py-4 text-[11px] shadow-lg">Save Image</button>
            </div>
        </form>
    </div>

@endsection
