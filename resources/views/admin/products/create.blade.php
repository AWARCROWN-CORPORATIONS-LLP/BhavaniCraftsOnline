@extends('layouts.admin')

@section('scripts')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
@endsection

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-bold text-gray-900 tracking-tight">Add New Product</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-bold text-blue-600 uppercase tracking-widest">Product Details</p>
    </div>
@endsection

@section('content')

    <div class="max-w-4xl mx-auto" x-data="{ 
        product_name: '{{ old('product_name') }}', 
        telugu_name: '{{ old('telugu_name') }}',
        short_description: '{{ old('short_description') }}',
        full_description: '{{ old('full_description') }}',
        material_type: '{{ old('material_type') }}',
        category_name: '',
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-gray-100 shadow-sm rounded-3xl p-12 space-y-12 transition-all">
            @csrf

            <!-- CORE IDENTITY -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Basic Information</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Product Name (English)</label>
                        <input type="text" name="product_name" x-model="product_name" @blur="translate()" required placeholder="e.g. Handmade Brass Diya" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Product Name (Telugu)</label>
                        <input type="text" name="telugu_name" x-model="telugu_name" placeholder="తెలగు పేరు" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Select Category</label>
                        <select name="category_id" x-on:change="category_name = $el.options[$el.selectedIndex].text" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all cursor-pointer">
                            <option value="">Choose a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Product Visibility</label>
                        <select name="listed_status" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all cursor-pointer">
                            <option value="Listed">Visible</option>
                            <option value="Unlisted">Hidden</option>
                            <option value="Draft">Draft</option>
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

                <div class="p-10 border-2 border-dashed border-gray-100 rounded-[30px] bg-gray-50/50 flex flex-col items-center text-center">
                    <div class="h-16 w-16 bg-blue-600/5 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="text-[12px] font-bold text-gray-800 uppercase tracking-widest mb-1">Upload Images</p>
                    <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest mb-6">Choose 3 photos of the product.</p>
                    
                    <input type="file" name="images[]" id="imageInput" multiple required accept="image/*" class="text-[10px] font-black uppercase tracking-widest text-blue-600 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition-all cursor-pointer">

                    <div id="imagePreview" class="grid grid-cols-3 gap-6 mt-10 w-full hidden"></div>
                </div>

                <!-- 3D AR PRODUCT VAULT -->
                <div class="space-y-8 mt-12 bg-gray-50/30 p-10 rounded-[40px] border border-gray-100/50">
                    <div class="flex items-center space-x-6">
                        <h3 class="text-xs font-bold text-blue-600 uppercase tracking-widest leading-none">3D Models (Optional)</h3>
                        <div class="flex-grow h-[1px] bg-blue-600/10"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-4 block leading-none">Android/Web Model (.GLB)</label>
                            <input type="file" name="model_3d" id="glb_input" accept=".glb" class="hidden">
                            <button type="button" onclick="document.getElementById('glb_input').click()" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl text-[10px] font-bold text-gray-600 uppercase tracking-widest hover:border-blue-600 transition-all">Select .GLB File</button>
                            <div id="glb_status" class="mt-4 text-[9px] font-black text-emerald-600 hidden uppercase tracking-widest flex items-center justify-center">
                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                Model Ready
                            </div>
                        </div>

                        <div>
                            <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-4 block leading-none">iOS/iPhone Model (.USDZ)</label>
                            <input type="file" name="model_usdz" id="usdz_input" accept=".usdz" class="hidden">
                            <button type="button" onclick="document.getElementById('usdz_input').click()" class="w-full px-6 py-4 bg-white border border-gray-200 rounded-2xl text-[10px] font-bold text-gray-600 uppercase tracking-widest hover:border-blue-600 transition-all">Select .USDZ File</button>
                            <div id="usdz_status" class="mt-4 text-[9px] font-black text-emerald-600 hidden uppercase tracking-widest flex items-center justify-center">
                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                AR Ready
                            </div>
                        </div>
                    </div>

                    <div id="viewer_container" class="hidden p-10 bg-black/10 rounded-[40px] border border-white shadow-sm relative overflow-hidden">
                        <model-viewer id="admin_preview" style="width: 100%; height: 400px;" camera-controls auto-rotate></model-viewer>
                        <div class="mt-6 flex justify-center">
                            <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">3D Preview</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8 mt-12 bg-gray-50/30 p-10 rounded-[40px] border border-gray-100/50">
                    <div class="flex items-center space-x-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none text-red-600">Product Video (Optional)</h3>
                        <div class="flex-grow h-[1px] bg-red-600/10"></div>
                    </div>

                    <div class="grid grid-cols-1 gap-8">
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 tracking-widest uppercase mb-4 block leading-none">YouTube Video URL</label>
                            <input type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-white border border-gray-100 px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-red-600/10 transition-all">
                            <p class="mt-4 text-[9px] text-gray-400 font-bold uppercase tracking-widest italic leading-relaxed">Copy and paste the YouTube link here. This video will show on the product page.</p>
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('glb_input').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            document.getElementById('glb_status').classList.remove('hidden');
                            document.getElementById('viewer_container').classList.remove('hidden');
                            const url = URL.createObjectURL(file);
                            document.getElementById('admin_preview').src = url;
                        }
                    });
                    document.getElementById('usdz_input').addEventListener('change', function(e) {
                        if (e.target.files[0]) document.getElementById('usdz_status').classList.remove('hidden');
                    });
                </script>
            </div>

            <script>
                document.getElementById('imageInput').addEventListener('change', function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = '';
                    const files = Array.from(e.target.files);
                    if (files.length > 0) {
                        preview.classList.remove('hidden');
                        if (files.length !== 3) {
                            alert('Please select exactly 3 images.');
                            this.value = '';
                            preview.classList.add('hidden');
                            return;
                        }
                        files.forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                const div = document.createElement('div');
                                div.className = 'relative aspect-square rounded-2xl overflow-hidden shadow-sm border border-gray-100';
                                div.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
                                preview.appendChild(div);
                            }
                            reader.readAsDataURL(file);
                        });
                    }
                });
            </script>

            <!-- SACRED VALUE & STOCK -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Price and Stock</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Sale Price (₹)</label>
                        <input type="number" name="price" step="0.01" required placeholder="599.00" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all text-gray-900">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Original MRP (₹)</label>
                        <input type="number" name="mrp" step="0.01" placeholder="999.00" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-blue-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Discount (%)</label>
                        <input type="number" name="discount_percent" placeholder="25" min="0" max="100" class="w-full bg-blue-50 border-none px-6 py-5 rounded-2xl text-sm font-bold text-blue-600 focus:ring-2 focus:ring-blue-600/20 transition-all text-center">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Items in Stock</label>
                        <input type="number" name="stock" required placeholder="100" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Material</label>
                        <input type="text" name="material_type" x-model="material_type" placeholder="e.g. Pure Brass, Wooden" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all">
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest leading-none">Product Description</h3>
                        <div class="flex-grow h-[1px] bg-gray-100"></div>
                    </div>
                    <button type="button" @click="generateAI()" :disabled="ai_loading" class="inline-flex items-center px-6 py-3 bg-blue-50 text-blue-700 text-[10px] font-bold uppercase tracking-widest rounded-xl hover:bg-blue-100 transition-all">
                        <span x-show="!ai_loading">AI Writer</span>
                        <span x-show="ai_loading" class="animate-pulse">Writing content...</span>
                    </button>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Quick Summary</label>
                    <textarea name="short_description" x-model="short_description" rows="3" placeholder="A brief summary about the product..." class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all"></textarea>
                </div>

                <div>
                    <label class="text-[10px] font-bold text-gray-600 tracking-widest uppercase mb-4 block ml-1 leading-none">Full Details</label>
                    <textarea name="full_description" x-model="full_description" rows="6" placeholder="Provide full details, history, and usage..." class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 transition-all"></textarea>
                </div>
            </div>

            <div class="pt-10 flex items-center justify-between">
                <a href="{{ route('admin.products.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-12 py-5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all">Save Product</button>
            </div>
        </form>
    </div>

@endsection
