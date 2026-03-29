@extends('layouts.admin')

@section('scripts')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
@endsection

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-bold text-gray-900 tracking-tight">Edit Product</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-bold text-blue-600 uppercase tracking-widest">Update Catalog</p>
    </div>
@endsection

@section('content')

    <div class="max-w-4xl mx-auto" x-data="{ 
        product_name: '{{ old('product_name', $product->product_name) }}', 
        telugu_name: '{{ old('telugu_name', $product->telugu_name) }}',
        short_description: `{!! old('short_description', $product->short_description) !!}`,
        full_description: `{!! old('full_description', $product->full_description) !!}`,
        material_type: '{{ old('material_type', $product->material_type) }}',
        category_name: '{{ $product->category->name ?? '' }}',
        ai_loading: false,

        async translate() {
            if (!this.product_name || this.product_name.length < 3) return;
            try {
                const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(this.product_name)}&langpair=en|te`);
                const data = await response.json();
                if (data.responseData.translatedText) {
                    this.telugu_name = data.responseData.translatedText;
                }
            } catch (e) { console.error(e); }
        },

        async generateAI() {
            if (!this.product_name) { alert('Please enter product name first'); return; }
            this.ai_loading = true;
            try {
                const response = await fetch('{{ route('admin.ai.generate') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({
                        name: this.product_name,
                        category: this.category_name,
                        material: this.material_type
                    })
                });
                const result = await response.json();
                if (result.success) {
                    this.short_description = result.data.english_short + '\n\n' + result.data.telugu_short;
                    this.full_description = result.data.english_full + '\n\n' + result.data.telugu_full;
                } else {
                    alert('AI Generation Failed: ' + result.error);
                }
            } catch (e) { console.error(e); }
            finally { this.ai_loading = false; }
        }
    }">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-100 shadow-sm rounded-3xl p-12 space-y-12 transition-all">
            @csrf
            @method('PUT')

            <!-- CORE IDENTITY -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Product Details</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Product Title (English)</label>
                        <input type="text" name="product_name" x-model="product_name" @blur="translate()" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Telugu Title (Auto)</label>
                        <input type="text" name="telugu_name" x-model="telugu_name" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Select Category</label>
                        <select name="category_id" x-on:change="category_name = $el.options[$el.selectedIndex].text" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all cursor-pointer">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Listing Mode</label>
                        <select name="listed_status" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all cursor-pointer">
                            <option value="Listed" {{ $product->listed_status == 'Listed' ? 'selected' : '' }}>Active Listing</option>
                            <option value="Unlisted" {{ $product->listed_status == 'Unlisted' ? 'selected' : '' }}>Hidden from Public</option>
                            <option value="Draft" {{ $product->listed_status == 'Draft' ? 'selected' : '' }}>Save as Draft</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- VISUAL ARTIFACTS -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Product Images</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    @foreach($product->images as $image)
                        <div class="relative aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-100 bg-gray-50">
                            <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>

                <div class="p-10 border-2 border-dashed border-gray-100 rounded-[30px] bg-gray-50/50 flex flex-col items-center text-center">
                    <p class="text-[12px] font-bold text-gray-800 uppercase tracking-widest mb-1">Update Photos</p>
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest mb-6">Uploading new files will replace existing ones. Select 3 images.</p>
                    <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="text-[10px] font-black uppercase tracking-widest text-blue-600 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">
                    <div id="imagePreview" class="grid grid-cols-3 gap-6 mt-10 w-full hidden"></div>
                </div>
            </div>

            <!-- PRICING & STOCK -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Pricing & Inventory</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Selling Rate (₹)</label>
                        <input type="number" name="price" step="0.01" required value="{{ $product->price }}" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all text-gray-900">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">MRP (Original) (₹)</label>
                        <input type="number" name="mrp" step="0.01" value="{{ $product->mrp }}" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-blue-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Global Discount (%)</label>
                        <input type="number" name="discount_percent" value="{{ $product->discount_percent }}" min="0" max="100" class="w-full bg-blue-50 border-none px-6 py-5 rounded-2xl text-sm font-bold text-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all text-center">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Stock Units Available</label>
                        <input type="number" name="stock" required value="{{ $product->stock }}" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Material Description</label>
                        <input type="text" name="material_type" x-model="material_type" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Product Narrative</h3>
                        <div class="flex-grow h-[1px] bg-gray-100"></div>
                    </div>
                    <button type="button" @click="generateAI()" :disabled="ai_loading" class="inline-flex items-center px-6 py-3 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-blue-100 transition-all">
                        <span x-show="!ai_loading">Auto-Rewrite with AI</span>
                        <span x-show="ai_loading" class="animate-pulse">Updating content...</span>
                    </button>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Short Information</label>
                    <textarea name="short_description" x-model="short_description" rows="3" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all"></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Full Background Description</label>
                    <textarea name="full_description" x-model="full_description" rows="6" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all"></textarea>
                </div>
            </div>

            <div class="pt-10 flex items-center justify-between">
                <a href="{{ route('admin.products.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Discard</a>
                <button type="submit" class="bg-blue-700 text-white px-12 py-5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl hover:bg-blue-800 transition-all">Update Product</button>
            </div>
        </form>
    </div>

@endsection
