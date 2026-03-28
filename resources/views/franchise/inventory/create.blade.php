@extends('layouts.franchise')

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Register Artifact</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-[#ff9933] uppercase tracking-[4px]">Private Collection</p>
    </div>
@endsection

@section('content')

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('franchise.inventory.store') }}" method="POST" enctype="multipart/form-data" class="card-premium p-12 space-y-12">
            @csrf

            <!-- CORE IDENTITY -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Artifact Identity</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">English Name</label>
                        <input type="text" name="product_name" required placeholder="Divine Brass Diya" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Category Registry</label>
                        <select name="category_id" required class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- VISUAL ARTIFACTS -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Sacred Trinity - Visual Artifacts</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="p-10 border-2 border-dashed border-gray-100 rounded-[30px] bg-gray-50/50 flex flex-col items-center text-center">
                    <div class="h-16 w-16 bg-[#ff9933]/10 text-[#ff9933] rounded-2xl flex items-center justify-center mb-6">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="text-[12px] font-black text-gray-900 uppercase tracking-widest mb-2">Primary & Support Imagery</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-6">Select exactly 3 images. Order: (1) Master View, (2) Alt View, (3) Detail View.</p>
                    
                    <input type="file" name="images[]" id="imageInput" multiple required accept="image/*" class="text-[10px] font-black uppercase tracking-widest text-[#ff9933] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-[#ff9933] file:text-white hover:file:bg-[#fb8c00] transition-all cursor-pointer">

                    <div id="imagePreview" class="grid grid-cols-3 gap-6 mt-10 w-full hidden"></div>
                </div>
            </div>

            <script>
                document.getElementById('imageInput').addEventListener('change', function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = '';
                    const files = Array.from(e.target.files);

                    if (files.length > 0) {
                        preview.classList.remove('hidden');
                        if (files.length !== 3) {
                            alert('Sacred Trinity Requirement: Please select exactly 3 images.');
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
                                        ${index === 0 ? 'Primary' : 'Detail'}
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

            <!-- PRICING & STOCK -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Sacred Value & Stock</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Wholesale Price (₹)</label>
                        <input type="number" name="price" step="0.01" required placeholder="599.00" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all text-gray-900">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Market Price (MRP) (₹)</label>
                        <input type="number" name="mrp" step="0.01" placeholder="999.00" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all text-gray-400">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-[#ff9933] tracking-[3px] uppercase mb-4 block ml-1 leading-none">Manual Deflation (%)</label>
                        <input type="number" name="discount_percent" placeholder="25" min="0" max="100" class="w-full bg-[#ff9933]/5 border-none px-6 py-5 rounded-2xl text-sm font-black text-[#ff9933] focus:ring-2 focus:ring-[#ff9933]/40 transition-all text-center">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Initial Stock Units</label>
                        <input type="number" name="stock" required placeholder="10" class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">
                    </div>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <div class="space-y-8">
                <div class="flex items-center space-x-6">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Artifact Chronicle</h3>
                    <div class="flex-grow h-[1px] bg-gray-100"></div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Short Description</label>
                    <textarea name="short_description" rows="3" placeholder="Handcrafted with pure brass, perfect for Deepawali rituals..." class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">{{ old('short_description') }}</textarea>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-4 block ml-1 leading-none">Full Narrative Description (Optional)</label>
                    <textarea name="full_description" rows="6" placeholder="The intricate design represents the blessing of the divine..." class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-[#ff9933]/20 transition-all">{{ old('full_description') }}</textarea>
                </div>
            </div>

            <div class="pt-10 flex items-center justify-between">
                <a href="{{ route('franchise.inventory.index') }}" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">Abort Registry</a>
                <button type="submit" class="btn-luxury-saffron px-12 py-5 text-[11px] shadow-2xl">Confirm & Register Artifact</button>
            </div>
        </form>
    </div>

@endsection
