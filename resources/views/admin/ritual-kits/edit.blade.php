@extends('layouts.admin')

@section('header_extra')
<a href="{{ route('admin.ritual-kits.index') }}" class="text-[10px] font-black uppercase tracking-[3px] text-gray-400 hover:text-brand-500 transition-colors flex items-center space-x-2">
    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
    <span>Back to Registry</span>
</a>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-12 pb-24">
    <!-- Header -->
    <div>
        <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">Refine Assembly</span>
        <h1 class="text-4xl font-black text-gray-900 tracking-tight italic">Edit <span class="text-brand-500">Ritual Kit</span></h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-widest mt-2">Adjust the composition of your specialized ceremonial bundle</p>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.ritual-kits.update', $ritualKit) }}" method="POST" enctype="multipart/form-data" class="space-y-12">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left Column: Basic Info -->
            <div class="lg:col-span-1 space-y-8">
                <div class="card-premium p-8 bg-white border-none shadow-2xl shadow-gray-200/50">
                    <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-8 border-b border-gray-50 pb-6">Kit Identity</h3>
                    
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Kit Name</label>
                            <input type="text" name="name" value="{{ old('name', $ritualKit->name) }}" placeholder="Grihapravesh Starter Kit" required 
                                   class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-brand-500 focus:border-brand-500 transition-all uppercase tracking-widest">
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Bundle Price (₹)</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $ritualKit->price) }}" placeholder="Leave empty for auto-sum" 
                                   class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-brand-500 focus:border-brand-500 transition-all uppercase tracking-widest">
                             <p class="px-1 text-[8px] text-gray-400 font-bold uppercase tracking-widest mt-1 italic">SPECIAL DISCOUNTED PRICE FOR THE BUNDLE</p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Display Image</label>
                            <div class="relative group h-40 bg-gray-50 border-2 border-dashed border-gray-100 rounded-2xl flex flex-col items-center justify-center transition-all hover:border-brand-500 cursor-pointer overflow-hidden">
                                <input type="file" name="display_image" class="absolute inset-0 opacity-0 cursor-pointer z-10" id="imageInput">
                                <div class="text-center @if($ritualKit->display_image) opacity-0 @endif" id="imagePlaceholder">
                                    <svg class="h-10 w-10 text-gray-300 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Update Kit Cover</p>
                                </div>
                                <img id="imagePreview" src="{{ $ritualKit->display_image ? Storage::url($ritualKit->display_image) : '' }}" 
                                     class="absolute inset-0 w-full h-full object-cover @if(!$ritualKit->display_image) hidden @endif">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Status</label>
                            <select name="is_active" required 
                                    class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 h-14 px-4 focus:ring-brand-500 focus:border-brand-500 transition-all uppercase tracking-widest">
                                <option value="1" @if($ritualKit->is_active) selected @endif>RADIANTLY ACTIVE</option>
                                <option value="0" @if(!$ritualKit->is_active) selected @endif>DORMANT STATE</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Products Selection -->
            <div class="lg:col-span-2 space-y-8">
                <div class="card-premium p-8 bg-white border-none shadow-2xl shadow-gray-200/50">
                    <div class="flex items-center justify-between mb-8 border-b border-gray-50 pb-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-gray-900">Constituent Artifacts</h3>
                        <div class="relative w-64">
                             <input type="text" id="productSearch" placeholder="SEARCH CATALOG..." class="w-full bg-gray-50 border-none rounded-lg text-[9px] font-black uppercase tracking-widest h-10 px-4 focus:ring-brand-500 transition-all">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto no-scrollbar p-1">
                        @php $selectedProducts = $ritualKit->products->pluck('id')->toArray(); @endphp
                        @foreach($products as $product)
                        <label class="product-item flex items-center p-4 @if(in_array($product->id, $selectedProducts)) bg-brand-50/50 border-brand-500 @else bg-gray-50 border-transparent @endif rounded-2xl border hover:border-brand-500 cursor-pointer transition-all space-x-4">
                            <input type="checkbox" name="products[]" value="{{ $product->id }}" 
                                   @if(in_array($product->id, $selectedProducts)) checked @endif
                                   class="h-5 w-5 rounded-md text-brand-500 focus:ring-brand-500 border-gray-200">
                            <div class="h-12 w-12 rounded-xl overflow-hidden bg-white shrink-0">
                                @if($product->images->count() > 0)
                                    <img src="{{ Storage::url($product->images->first()->image_url) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-[8px] font-black text-gray-300">N/A</div>
                                @endif
                            </div>
                            <div class="flex-grow min-w-0">
                                <p class="text-[11px] font-black text-gray-900 uppercase tracking-widest truncate">{{ $product->product_name }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic">₹{{ number_format($product->price, 2) }}</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="card-premium p-8 bg-white border-none shadow-2xl shadow-gray-200/50">
                     <h3 class="text-sm font-black uppercase tracking-widest text-gray-900 mb-8 border-b border-gray-50 pb-6">Divine Purpose</h3>
                     <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 block px-1">Description</label>
                        <textarea name="description" rows="4" placeholder="Explain the ceremonial importance of this kit..."
                                  class="w-full bg-gray-50 border-gray-100 rounded-xl text-xs font-bold text-gray-900 p-4 focus:ring-brand-500 focus:border-brand-500 transition-all italic leading-relaxed">{{ old('description', $ritualKit->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end space-x-6 border-t border-gray-100 pt-10">
            <a href="{{ route('admin.ritual-kits.index') }}" class="text-[10px] font-black uppercase tracking-[3px] text-gray-400 hover:text-gray-900 transition-colors">Discard Ritual</a>
            <button type="submit" class="btn-luxury-saffron px-12 py-5 shadow-2xl shadow-brand-500/20 flex items-center space-x-4">
                <span class="text-xs">Seal Refinement Ritual</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
            </button>
        </div>
    </form>
</div>

<script>
    // Image Preview
    document.getElementById('imageInput').onchange = evt => {
        const [file] = evt.target.files
        if (file) {
            document.getElementById('imagePreview').src = URL.createObjectURL(file)
            document.getElementById('imagePreview').classList.remove('hidden')
            document.getElementById('imagePlaceholder').classList.add('opacity-0')
        }
    }

    // Live Search
    document.getElementById('productSearch').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.product-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(query) ? 'flex' : 'none';
        });
    });
</script>
@endsection
