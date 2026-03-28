@extends('layouts.employee')

@section('scripts')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.4.0/model-viewer.min.js"></script>
@endsection

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Edit Product</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-sky-500 uppercase tracking-[4px]">{{ $product->product_name }}</p>
    </div>
@endsection

@section('content')

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('employee.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="card-premium p-12 space-y-12">
            @csrf
            @method('PUT')

            <!-- CORE IDENTITY -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Product Info</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Product Name *</label>
                        <input type="text" name="product_name" required value="{{ $product->product_name }}" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Telugu Name (Optional)</label>
                        <input type="text" name="telugu_name" value="{{ $product->telugu_name }}" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Category</label>
                        <select name="category_id" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Visibility Status</label>
                        <select name="listed_status" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">
                            <option value="Listed" {{ $product->listed_status == 'Listed' ? 'selected' : '' }}>Listed (Active)</option>
                            <option value="Unlisted" {{ $product->listed_status == 'Unlisted' ? 'selected' : '' }}>Unlisted (Hidden)</option>
                            <option value="Draft" {{ $product->listed_status == 'Draft' ? 'selected' : '' }}>Draft (Private)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- VISUAL ARTIFACTS -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Product Gallery</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-3 gap-6">
                    @foreach($product->images as $image)
                        <div class="relative aspect-square rounded-2xl overflow-hidden shadow-xl border-4 border-white bg-gray-50">
                            <img src="{{ asset('storage/' . $image->image_url) }}" class="w-full h-full object-cover">
                            <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 backdrop-blur-md rounded-md text-[7px] font-black text-white uppercase tracking-widest">
                                {{ $image->is_main ? 'Primary' : 'Detail' }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="p-10 border-2 border-dashed border-gray-100 rounded-[30px] bg-gray-50/50 flex flex-col items-center text-center mt-8">
                    <div class="h-16 w-16 bg-sky-100 text-sky-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest mb-2">Update Product Images</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6">Uploading new images will replace all existing ones. Exactly 3 required.</p>
                    
                    <input type="file" name="images[]" id="imageInput" multiple accept="image/*" class="text-[10px] font-black uppercase tracking-widest text-sky-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-sky-500 file:text-white hover:file:bg-sky-600 transition-all cursor-pointer">

                    <div id="imagePreview" class="grid grid-cols-3 gap-6 mt-10 w-full hidden"></div>
                </div>

                <!-- 3D AR PRODUCT VAULT -->
                <div class="space-y-8 mt-12">
                    <div class="flex items-center space-x-6">
                        <h3 class="text-xs font-black text-orange-400 uppercase tracking-[6px] leading-none italic">3D AR Vault</h3>
                        <div class="flex-grow h-[1px] bg-orange-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- GLB Upload -->
                        <div class="p-8 bg-gray-50 border-2 border-dashed border-gray-100 rounded-[30px] flex flex-col items-center text-center">
                            <label class="text-[9px] font-black text-gray-400 tracking-[3px] uppercase mb-4 block leading-none">Web/Android (.GLB)</label>
                            @if($product->model_3d)
                                <div class="mb-4 text-[8px] font-black text-green-500 uppercase tracking-widest">✓ Current Model Linked</div>
                            @endif
                            <input type="file" name="model_3d" id="glb_input" accept=".glb" class="hidden">
                            <button type="button" onclick="document.getElementById('glb_input').click()" class="px-6 py-4 bg-white border border-gray-200 rounded-2xl text-[10px] font-black text-gray-600 uppercase tracking-widest hover:border-orange-500 transition-all">Replace GLB Model</button>
                            <div id="glb_status" class="mt-4 text-[9px] font-bold text-orange-500 hidden uppercase tracking-widest">New File Selected</div>
                        </div>

                        <!-- USDZ Upload -->
                        <div class="p-8 bg-gray-50 border-2 border-dashed border-gray-100 rounded-[30px] flex flex-col items-center text-center">
                            <label class="text-[9px] font-black text-gray-400 tracking-[3px] uppercase mb-4 block leading-none">iOS AR Support (.USDZ)</label>
                            @if($product->model_usdz)
                                <div class="mb-4 text-[8px] font-black text-green-500 uppercase tracking-widest">✓ Current AR Asset Linked</div>
                            @endif
                            <input type="file" name="model_usdz" id="usdz_input" accept=".usdz" class="hidden">
                            <button type="button" onclick="document.getElementById('usdz_input').click()" class="px-6 py-4 bg-white border border-gray-200 rounded-2xl text-[10px] font-black text-gray-600 uppercase tracking-widest hover:border-orange-500 transition-all">Replace USDZ Asset</button>
                            <div id="usdz_status" class="mt-4 text-[9px] font-bold text-orange-500 hidden uppercase tracking-widest">New File Selected</div>
                        </div>
                    </div>

                    <!-- PREVIEW ZONE -->
                    <div id="viewer_container" class="{{ $product->model_3d ? '' : 'hidden' }} p-10 bg-black/5 rounded-[40px] border-4 border-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute top-6 left-6 z-10">
                            <span class="px-4 py-2 bg-black/80 text-white text-[8px] font-black uppercase tracking-[4px] rounded-full backdrop-blur-md">Live 3D Preview</span>
                        </div>
                        <model-viewer id="admin_preview" 
                                      src="{{ $product->model_3d ? asset('storage/' . $product->model_3d) : '' }}"
                                      style="width: 100%; height: 400px; --poster-color: transparent;"
                                      camera-controls 
                                      auto-rotate 
                                      shadow-intensity="1">
                        </model-viewer>
                        <div class="mt-6 flex justify-center">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[4px]">Drag to orbit • Scroll to zoom</p>
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
                        const file = e.target.files[0];
                        if (file) {
                            document.getElementById('usdz_status').classList.remove('hidden');
                        }
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
                            alert('Policy Requirement: Please select exactly 3 images.');
                            this.value = '';
                            preview.classList.add('hidden');
                            return;
                        }

                        files.forEach((file, index) => {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                const div = document.createElement('div');
                                div.className = 'relative aspect-square rounded-2xl overflow-hidden shadow-2xl border-4 border-white';
                                div.innerHTML = `
                                    <img src="${event.target.result}" class="w-full h-full object-cover">
                                    <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 backdrop-blur-md rounded-md text-[7px] font-black text-white uppercase tracking-widest">
                                        View ${index + 1}
                                    </div>
                                `;
                                preview.appendChild(div);
                            }
                            reader.readAsDataURL(file);
                        });
                    } else {
                        preview.classList.add('hidden');
                    }
                });
            </script>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Net Price (₹) *</label>
                        <input type="number" name="price" step="0.01" required value="{{ $product->price }}" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all text-gray-900">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Market Price (MRP) (₹)</label>
                        <input type="number" name="mrp" step="0.01" value="{{ $product->mrp }}" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all text-gray-400">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-sky-500 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Discount (%)</label>
                        <input type="number" name="discount_percent" value="{{ $product->discount_percent }}" min="0" max="100" class="w-full bg-sky-50 border-none px-6 py-5 rounded-2xl text-sm font-black text-sky-600 focus:ring-2 focus:ring-sky-500/40 transition-all text-center">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Stock Level *</label>
                        <input type="number" name="stock" required value="{{ $product->stock }}" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Material / Type</label>
                        <input type="text" name="material_type" value="{{ $product->material_type }}" class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">
                    </div>
                </div>

            <!-- DESCRIPTION -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Product Chronicle</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Short Summary</label>
                    <textarea name="short_description" rows="3" placeholder="Handcrafted with pure brass, perfect for Deepawali rituals..." class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">{{ $product->short_description }}</textarea>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Full Description (Optional)</label>
                    <textarea name="full_description" rows="6" placeholder="The intricate design represents the blessing of the divine..." class="w-full bg-gray-50 border-none px-6 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">{{ $product->full_description }}</textarea>
                </div>
            </div>

            <div class="pt-10 flex items-center justify-between">
                <a href="{{ route('employee.products.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Discard Changes</a>
                <button type="submit" class="btn-luxury px-12 py-5 text-[11px] shadow-2xl">Confirm & Update Catalog</button>
            </div>
        </form>
    </div>

@endsection
