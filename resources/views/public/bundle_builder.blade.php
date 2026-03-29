@extends('layouts.public')

@section('meta_title', 'Build Your Sacred Kit | Bhavani Crafts Custom Bundles')

@section('content')
<div x-data="{ 
    step: 1,
    selection: {
        idols: null,
        accessories: null,
        samagri: []
    },
    total() {
        let sum = 0;
        if(this.selection.idols) sum += parseFloat(this.selection.idols.price);
        if(this.selection.accessories) sum += parseFloat(this.selection.accessories.price);
        this.selection.samagri.forEach(s => sum += parseFloat(s.price));
        return sum;
    },
    discountedTotal() {
        const t = this.total();
        // 15% discount if bundle is complete (Idol + Accessory + at least 1 Samagri)
        if(this.selection.idols && this.selection.accessories && this.selection.samagri.length > 0) {
            return t * 0.85;
        }
        return t;
    },
    toggleSamagri(item) {
        const index = this.selection.samagri.findIndex(s => s.id === item.id);
        if(index > -1) this.selection.samagri.splice(index, 1);
        else this.selection.samagri.push(item);
    },
    async addToCart() {
        const items = [];
        if(this.selection.idols) items.push({ id: this.selection.idols.id, qty: 1 });
        if(this.selection.accessories) items.push({ id: this.selection.accessories.id, qty: 1 });
        this.selection.samagri.forEach(s => items.push({ id: s.id, qty: 1 }));

        BcLoader.show('Initializing Cart Sync...');
        try {
            for(let item of items) {
                await fetch('{{ route('cart.add', ['locale' => 'en-in']) }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ product_id: item.id, quantity: 1 })
                });
            }
            window.location.href = '{{ route('cart.index', ['locale' => 'en-in']) }}';
        } catch(e) {
            console.error(e);
            BcLoader.hide();
        }
    }
}" class="bg-white min-h-screen">

    <!-- Header / Progress -->
    <div class="bg-gray-50 border-b border-gray-100 py-16 sticky top-0 z-40 backdrop-blur-xl bg-white/80">
        <div class="container mx-auto px-4 lg:px-8 max-w-7xl">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h1 class="text-3xl font-black text-onyx-950 uppercase tracking-[4px] mb-2">Sacred Kit Builder</h1>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Curate your heritage collection & save 15% on complete bundles.</p>
                </div>

                <!-- Progress Pipeline -->
                <div class="flex items-center space-x-4">
                    <template x-for="s in [1, 2, 3, 4]">
                        <div class="flex items-center">
                            <div :class="step >= s ? 'bg-brand-600 border-brand-600 text-white shadow-lg shadow-brand-500/20' : 'bg-white border-gray-200 text-gray-300'" 
                                 class="h-10 w-10 rounded-full border-2 flex items-center justify-center font-black text-xs transition-all duration-500"
                                 x-text="s"></div>
                            <div x-show="s < 4" :class="step > s ? 'bg-brand-600' : 'bg-gray-100'" class="h-0.5 w-8 mx-2 transition-all duration-500"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 py-20 max-w-7xl">
        <div class="flex flex-col lg:flex-row gap-16">
            
            <!-- Main Selection Area -->
            <div class="flex-1 min-h-[500px]">
                
                <!-- Step 1: Idols -->
                <div x-show="step === 1" x-transition:enter="duration-500 ease-out" x-transition:enter-start="opacity-0 translate-y-8">
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-brand-500 uppercase tracking-[4px] mb-2 block">Step 01 / 04</span>
                        <h2 class="text-4xl font-black text-onyx-950 uppercase tracking-widest">Primary Artifact</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($products['idols'] as $product)
                            <div @click="selection.idols = { id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ Storage::url($product->images->first()->image_path ?? '') }}' }" 
                                 :class="selection.idols?.id === {{ $product->id }} ? 'border-brand-500 ring-2 ring-brand-500/20' : 'border-gray-100 hover:border-brand-200'"
                                 class="group cursor-pointer bg-white border rounded-[2.5rem] p-6 transition-all relative overflow-hidden">
                                <template x-if="selection.idols?.id === {{ $product->id }}">
                                    <div class="absolute top-6 right-6 h-8 w-8 bg-brand-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                </template>
                                <div class="aspect-square bg-gray-50 rounded-[1.5rem] mb-6 overflow-hidden">
                                    <img src="{{ Storage::url($product->images->first()->image_path ?? '') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                </div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-onyx-950 mb-1">{{ $product->name }}</h3>
                                <p class="text-brand-600 text-sm font-black uppercase tracking-widest">₹{{ number_format($product->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 2: Accessories -->
                <div x-show="step === 2" x-transition:enter="duration-500 ease-out" x-transition:enter-start="opacity-0 translate-y-8" style="display: none;">
                    <button @click="step = 1" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8 hover:text-brand-600">← Back to Artifacts</button>
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-brand-500 uppercase tracking-[4px] mb-2 block">Step 02 / 04</span>
                        <h2 class="text-4xl font-black text-onyx-950 uppercase tracking-widest">Sacred Plate</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach($products['accessories'] as $product)
                            <div @click="selection.accessories = { id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ Storage::url($product->images->first()->image_path ?? ' ') }}' }" 
                                 :class="selection.accessories?.id === {{ $product->id }} ? 'border-brand-500 ring-2 ring-brand-500/20' : 'border-gray-100 hover:border-brand-200'"
                                 class="group cursor-pointer bg-white border rounded-[2.5rem] p-6 transition-all relative overflow-hidden">
                                <template x-if="selection.accessories?.id === {{ $product->id }}">
                                    <div class="absolute top-6 right-6 h-8 w-8 bg-brand-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="h-4 w-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                </template>
                                <div class="aspect-video bg-gray-50 rounded-[1.5rem] mb-6 overflow-hidden">
                                    <img src="{{ Storage::url($product->images->first()->image_path ?? '') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                </div>
                                <h3 class="text-xs font-black uppercase tracking-widest text-onyx-950 mb-1">{{ $product->name }}</h3>
                                <p class="text-brand-600 text-sm font-black uppercase tracking-widest">₹{{ number_format($product->price, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 3: Samagri -->
                <div x-show="step === 3" x-transition:enter="duration-500 ease-out" x-transition:enter-start="opacity-0 translate-y-8" style="display: none;">
                    <button @click="step = 2" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8 hover:text-brand-600">← Back to Plates</button>
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-brand-500 uppercase tracking-[4px] mb-2 block">Step 03 / 04</span>
                        <h2 class="text-4xl font-black text-onyx-950 uppercase tracking-widest">Ritual Samagri</h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($products['samagri'] as $product)
                            @php 
                                $img = Storage::url($product->images->first()->image_path ?? '');
                            @endphp
                            <div @click="toggleSamagri({ id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', price: {{ $product->price }}, image: '{{ $img }}' })" 
                                 :class="selection.samagri.some(s => s.id === {{ $product->id }}) ? 'border-brand-500 ring-2 ring-brand-500/20' : 'border-gray-100 hover:border-brand-200'"
                                 class="group cursor-pointer bg-white border rounded-3xl p-5 transition-all relative overflow-hidden">
                                <template x-if="selection.samagri.some(s => s.id === {{ $product->id }})">
                                    <div class="absolute top-4 right-4 h-6 w-6 bg-brand-600 rounded-full flex items-center justify-center shadow-lg">
                                        <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                </template>
                                <div class="aspect-square bg-gray-50 rounded-2xl mb-4 overflow-hidden">
                                    <img src="{{ $img }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                </div>
                                <h3 class="text-[10px] font-black uppercase tracking-widest text-onyx-950 mb-1 truncate">{{ $product->name }}</h3>
                                <p class="text-brand-600 text-[11px] font-black uppercase tracking-widest">₹{{ $product->price }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 4: Summary -->
                <div x-show="step === 4" x-transition:enter="duration-500 ease-out" x-transition:enter-start="opacity-0 translate-y-8" style="display: none;">
                    <button @click="step = 3" class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-8 hover:text-brand-600">← Back to Samagri</button>
                    <div class="mb-12">
                        <span class="text-[10px] font-black text-brand-500 uppercase tracking-[4px] mb-2 block">Step 04 / 04</span>
                        <h2 class="text-4xl font-black text-onyx-950 uppercase tracking-widest">Final Summary</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-[2.5rem] p-10 flex flex-col md:flex-row items-center gap-10 border border-gray-100 shadow-sm relative overflow-hidden group">
                             <div class="absolute top-0 right-0 p-10 opacity-5 group-hover:rotate-12 transition-transform duration-1000">
                                 <svg class="h-32 w-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L4.5 18H12m0-16l7.5 16H12"/></svg>
                             </div>
                             
                             <div class="flex-1 space-y-8 relative">
                                 <div x-show="selection.idols" class="flex items-center space-x-6">
                                     <img :src="selection.idols?.image" class="h-20 w-20 rounded-2xl object-cover shadow-xl">
                                     <div>
                                         <p class="text-[9px] font-black text-brand-500 uppercase tracking-widest mb-1">Primary Artifact</p>
                                         <p class="text-xs font-black text-onyx-950 uppercase tracking-widest" x-text="selection.idols?.name"></p>
                                     </div>
                                 </div>

                                 <div x-show="selection.accessories" class="flex items-center space-x-6">
                                     <img :src="selection.accessories?.image" class="h-20 w-20 rounded-2xl object-cover shadow-xl">
                                     <div>
                                         <p class="text-[9px] font-black text-brand-500 uppercase tracking-widest mb-1">Sacred Plate</p>
                                         <p class="text-xs font-black text-onyx-950 uppercase tracking-widest" x-text="selection.accessories?.name"></p>
                                     </div>
                                 </div>

                                 <div x-show="selection.samagri.length > 0" class="pt-6 border-t border-gray-200">
                                     <p class="text-[9px] font-black text-gray-400 uppercase tracking-[4px] mb-4">Included Samagri</p>
                                     <div class="flex flex-wrap gap-4">
                                         <template x-for="item in selection.samagri">
                                            <div class="flex items-center space-x-3 bg-white px-4 py-2 rounded-full border border-gray-100 shadow-sm">
                                                <img :src="item.image" class="h-6 w-6 rounded-full object-cover">
                                                <span class="text-[9px] font-black uppercase tracking-widest" x-text="item.name"></span>
                                            </div>
                                         </template>
                                     </div>
                                 </div>
                             </div>
                        </div>

                        <div class="p-10 bg-onyx-950 rounded-[2.5rem] text-white overflow-hidden relative">
                             <div class="absolute inset-0 opacity-10">
                                 <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100"><rect width="100" height="100" /></svg>
                             </div>
                             
                             <div class="relative flex flex-col md:flex-row items-center justify-between gap-8">
                                 <div>
                                     <h3 class="text-xs font-black uppercase tracking-[5px] text-brand-500 mb-4">Total Ritual Investment</h3>
                                     <p class="text-4xl font-black tracking-widest flex items-end">
                                         <span x-text="`₹` + discountedTotal().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                         <template x-if="discountedTotal() < total()">
                                             <span class="text-sm line-through text-gray-500 ml-4 mb-1" x-text="`₹` + total().toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                         </template>
                                     </p>
                                     <template x-if="discountedTotal() < total()">
                                        <p class="text-[10px] font-bold text-green-400 mt-2 uppercase tracking-widest">Artisan Bundle Discount Applied (15%)</p>
                                     </template>
                                 </div>
                                 <button @click="addToCart" class="px-12 py-5 bg-brand-600 text-white text-xs font-black uppercase tracking-[4px] rounded-2xl hover:bg-brand-500 transition-all shadow-2xl shadow-brand-500/40 active:scale-95">Complete Sacred Bundle</button>
                             </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidbar Summary (Sticky) -->
            <div class="w-full lg:w-[400px]">
                <div class="bg-gray-50 rounded-[2.5rem] p-10 sticky top-48 border border-gray-100 shadow-sm">
                    <h3 class="text-xs font-black text-onyx-950 uppercase tracking-[4px] mb-8 border-b border-gray-200 pb-4">Bundle Status</h3>
                    
                    <div class="space-y-6 mb-12">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Artifact Selection</span>
                            <span :class="selection.idols ? 'text-green-500' : 'text-red-400'" class="text-[10px] font-black uppercase tracking-widest" x-text="selection.idols ? 'Set' : 'Missing'"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Sacred Plate</span>
                            <span :class="selection.accessories ? 'text-green-500' : 'text-red-400'" class="text-[10px] font-black uppercase tracking-widest" x-text="selection.accessories ? 'Set' : 'Missing'"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Ritual Samagri</span>
                            <span :class="selection.samagri.length > 0 ? 'text-green-500' : 'text-gray-300'" class="text-[10px] font-black uppercase tracking-widest" x-text="selection.samagri.length + ' Items'"></span>
                        </div>
                    </div>

                    <!-- Next Step Button -->
                    <div class="space-y-3">
                        <button x-show="step < 4" 
                                @click="step++" 
                                :disabled="(step === 1 && !selection.idols) || (step === 2 && !selection.accessories)"
                                class="w-full py-5 rounded-2xl text-[10px] font-black uppercase tracking-[4px] transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-xl"
                                :class="((step === 1 && !selection.idols) || (step === 2 && !selection.accessories)) ? 'bg-gray-200 text-gray-400 shadow-none' : 'bg-onyx-950 text-white shadow-onyx-900/30 font-black tracking-[4px]'">
                            Next Ritual Step →
                        </button>
                        
                        <div class="text-center">
                            <p x-show="step < 3" class="text-[9px] font-bold text-gray-400 uppercase tracking-widest italic flex items-center justify-center">
                                <svg class="h-3 w-3 mr-2 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                More selections unlock bundle discount
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
