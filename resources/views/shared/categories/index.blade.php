@extends('layouts.admin')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Category Images</h2>
        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[4px]">Manage Category Photos</span>
    </div>
@endsection

@section('content')

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-semibold flex items-center space-x-2">
            <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6 p-5 bg-amber-50 border border-amber-100 rounded-2xl text-amber-700 text-sm">
        <p class="font-semibold">📸 You can update images for any category below. Category names and slugs are managed by administrators.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($categories as $category)
            @php
                // Build correct edit route based on which portal the user is in
                $user = auth()->user();
                $roles = $user->roles->pluck('name')->toArray();
                if (in_array('franchise', $roles)) {
                    $editRoute = route('franchise.category-images.edit', $category->id);
                } elseif (in_array('super_admin', $roles) || in_array('admin', $roles) || in_array('employee', $roles)) {
                    $editRoute = route('employee.category-images.edit', $category->id);
                } else {
                    $editRoute = '#';
                }
            @endphp
            <a href="{{ $editRoute }}"
               class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-brand-400 hover:shadow-lg transition-all duration-300 block">

                <!-- Image -->
                <div class="relative h-40 w-full bg-gray-100 overflow-hidden">
                    @if($category->display_image)
                        <img src="{{ $category->display_image }}" alt="{{ $category->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors"></div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                            <div class="text-center text-gray-300">
                                <svg class="h-10 w-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <p class="text-xs font-bold">No Image</p>
                            </div>
                        </div>
                    @endif

                    <!-- Edit Button overlay -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-800 text-xs font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-lg">
                            ✎ Update Image
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-4">
                    <p class="text-sm font-black text-gray-900">{{ $category->name }}</p>
                    <p class="text-[10px] text-gray-400 mt-0.5 font-mono">/{{ $category->slug }}</p>
                    @if($category->image_path)
                        <span class="mt-2 inline-block px-2 py-0.5 bg-green-50 text-green-600 text-[9px] font-bold rounded-full">✓ Has Image</span>
                    @else
                        <span class="mt-2 inline-block px-2 py-0.5 bg-gray-50 text-gray-400 text-[9px] font-bold rounded-full">No Image Yet</span>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

@endsection
