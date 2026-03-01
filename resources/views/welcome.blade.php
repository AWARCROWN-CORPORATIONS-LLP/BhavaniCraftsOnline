@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-onyx-900 overflow-hidden min-h-[85vh] flex items-center">
        <!-- Background Imagery / Texture -->
        <div class="absolute inset-0 z-0">
            <!-- For now using a dark gradient, but we can replace this with an actual image URL later -->
            <div class="absolute inset-0 bg-gradient-to-r from-onyx-900 via-onyx-900/80 to-transparent z-10"></div>
            <!-- Decorative circle blur -->
            <div class="absolute top-1/4 right-1/4 w-[500px] h-[500px] bg-brand-500/20 blur-[120px] rounded-full mix-blend-screen pointer-events-none"></div>
            <!-- Optional image placeholder -->
            <div class="h-full w-full bg-[url('https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=2670&auto=format&fit=crop')] bg-cover bg-center opacity-40 mix-blend-overlay"></div>
        </div>

        <div class="container mx-auto px-4 lg:px-8 relative z-20">
            <div class="max-w-3xl">
                <span class="inline-block py-1.5 px-3 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-400 text-[10px] font-black uppercase tracking-[4px] mb-6">
                    Handcrafted Heritage
                </span>
                
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-serif font-bold text-white leading-[1.1] tracking-tight mb-8">
                    Divine Artifacts, <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-300 to-brand-500">Masterfully</span> Forged.
                </h1>
                
                <p class="text-lg md:text-xl text-gray-300 font-light max-w-2xl leading-relaxed mb-10">
                    Discover exclusive brass idols, exquisite pooja mandirs, and premium corporate gifts. Each piece is meticulously crafted by generational artisans, bringing eternal grace into your modern sanctuary.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="#" class="w-full sm:w-auto px-8 py-4 bg-brand-500 hover:bg-brand-400 text-white text-xs font-black uppercase tracking-[3px] rounded-full transition-all shadow-[0_0_30px_rgba(245,130,28,0.3)] hover:shadow-[0_0_40px_rgba(245,130,28,0.5)] transform hover:-translate-y-1 text-center">
                        Explore Collection
                    </a>
                    <a href="#" class="w-full sm:w-auto px-8 py-4 bg-transparent border border-white/20 text-white hover:bg-white/5 text-xs font-black uppercase tracking-[3px] rounded-full transition-all text-center">
                        B2B Wholesale
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-20 animate-bounce flex flex-col items-center">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Scroll</span>
            <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
        </div>
    </div>

    <!-- Features / Value Prop -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                
                <!-- Prop 1 -->
                <div class="text-center group">
                    <div class="h-16 w-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-6 text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all transform group-hover:-translate-y-2 duration-300 shadow-sm">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl text-onyx-900 mb-3">Authentic Craftsmanship</h3>
                    <p class="text-sm text-gray-500 leading-relaxed px-4">Every artifact is completely hand-forged by generational artisans using traditional techniques.</p>
                </div>
                
                <!-- Prop 2 -->
                <div class="text-center group">
                    <div class="h-16 w-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-6 text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all transform group-hover:-translate-y-2 duration-300 shadow-sm">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl text-onyx-900 mb-3">Global Shipping</h3>
                    <p class="text-sm text-gray-500 leading-relaxed px-4">Securely packaged and exported worldwide. We ensure divine artifacts reach your door safely.</p>
                </div>

                <!-- Prop 3 -->
                <div class="text-center group">
                    <div class="h-16 w-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-6 text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all transform group-hover:-translate-y-2 duration-300 shadow-sm">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl text-onyx-900 mb-3">B2B & Wholesale Dropshipping</h3>
                    <p class="text-sm text-gray-500 leading-relaxed px-4">Exclusive partner portals featuring automated restocks and zero-inventory fulfillment systems.</p>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Placeholder for Products grid -->
    <section class="py-24 bg-gray-50/50">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">Featured Artifacts</span>
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-onyx-900">Curated Masterpieces</h2>
                </div>
                <a href="#" class="hidden md:flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-brand-500 transition-colors">
                    <span>View All</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($products as $product)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 group relative">
                        <!-- Image Container -->
                        <div class="relative w-full h-64 rounded-xl mb-4 overflow-hidden bg-gray-50 flex items-center justify-center">
                            @if($product->images->count() > 0)
                                <a href="{{ route('artifact.show', $product->id) }}" class="block w-full h-full">
                                    <img src="{{ Storage::url($product->images->first()->image_url) }}" alt="{{ $product->product_name }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                </a>
                            @else
                                <div class="text-gray-300">
                                    <svg class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            
                            <!-- Badges -->
                            @if($product->discount_percent > 0)
                                <div class="absolute top-4 left-4 z-20">
                                    <div class="bg-red-500 text-white text-[10px] font-black uppercase tracking-widest px-2.5 py-1.5 rounded-lg shadow-lg animate-pulse">
                                        {{ $product->discount_percent }}% OFF
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Quick Action Overlay -->
                            <div class="absolute inset-x-0 bottom-0 p-4 opacity-0 group-hover:opacity-100 transition-all duration-300 flex justify-center space-x-2 bg-gradient-to-t from-onyx-900/60 to-transparent translate-y-2 group-hover:translate-y-0">
                                <!-- Wishlist Button -->
                                <button type="button" 
                                         x-data="{ loading: false }"
                                         :disabled="loading"
                                         @click="loading = true; fetch('{{ route('wishlist.toggle') }}', {
                                             method: 'POST',
                                             headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                                             body: JSON.stringify({ product_id: {{ $product->id }} })
                                         }).then(r => r.json()).then(data => {
                                             if(data.error) {
                                                 $dispatch('notify', { message: data.error, type: 'error' });
                                             } else {
                                                 $dispatch('wishlist-updated', { count: data.wishlist_count });
                                                 $dispatch('notify', { message: data.message });
                                             }
                                             loading = false;
                                         }).catch(() => { loading = false; })"
                                         class="relative z-10 h-10 w-10 bg-white text-onyx-900 rounded-full flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors shadow-lg disabled:opacity-50" title="Add to Wishlist">
                                    <template x-if="!loading">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    </template>
                                    <template x-if="loading">
                                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </template>
                                </button>

                                <!-- Add to Cart Button -->
                                <button type="button" 
                                        x-data="{ loading: false }"
                                        :disabled="loading"
                                        @click="loading = true; fetch('{{ route('cart.add') }}', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                            body: JSON.stringify({ product_id: {{ $product->id }} })
                                        }).then(r => r.json()).then(data => {
                                            $dispatch('cart-updated', { count: data.cart_count });
                                            $dispatch('notify', { message: data.message });
                                            loading = false;
                                        }).catch(() => { loading = false; })"
                                        class="relative z-10 h-10 w-10 bg-brand-600 text-white rounded-full flex items-center justify-center hover:bg-brand-500 transition-colors shadow-lg disabled:opacity-50" title="Add to Cart">
                                    <template x-if="!loading">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                    </template>
                                    <template x-if="loading">
                                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </template>
                                </button>
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="px-1">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-[10px] font-bold uppercase tracking-widest text-brand-500 leading-none">
                                    {{ $product->category->name ?? 'Artifact' }}
                                </p>
                                <div class="flex items-center space-x-1">
                                    <span class="text-[10px] font-bold text-gray-400 italic">Code: {{ $product->product_code }}</span>
                                </div>
                            </div>
                            
                            <h3 class="text-sm font-bold text-onyx-900 leading-tight mb-3 truncate">
                                <a href="{{ route('artifact.show', $product->id) }}" class="hover:text-brand-500 transition-colors">
                                    {{ $product->product_name }}
                                </a>
                            </h3>

                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-base font-black text-onyx-900">₹{{ number_format($product->price, 2) }}</span>
                                    @if($product->mrp > $product->price)
                                        <span class="text-[11px] font-bold text-gray-400 line-through leading-none">₹{{ number_format($product->mrp, 2) }}</span>
                                    @endif
                                </div>
                                                                <!-- Buy Now Button -->
                                 <button type="button"
                                         id="buy-now-{{ $product->id }}"
                                         onclick="buyNow({{ $product->id }}, this)"
                                         class="px-4 py-2 bg-brand-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-brand-600 transition-all shadow-sm flex items-center gap-2">
                                     <span class="btn-text">Buy Now</span>
                                     <svg class="btn-spinner animate-spin h-3 w-3 hidden" viewBox="0 0 24 24" fill="none">
                                         <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                         <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                     </svg>
                                 </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-20 border-2 border-dashed border-gray-200 rounded-2xl">
                        <svg class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        <h4 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-2">Masterpieces arriving soon</h4>
                        <p class="text-xs text-gray-400 font-medium">Our artisans are currently crafting new divine additions.</p>
                    </div>
                @endforelse
            </div>
            
             <a href="#" class="md:hidden mt-8 flex items-center justify-center space-x-2 text-xs font-bold uppercase tracking-widest text-gray-500 w-full py-4 border border-gray-200 rounded-xl hover:text-brand-500 hover:border-brand-500 transition-all">
                <span>View All Masterpieces</span>
            </a>
        </div>
    </section>


@endsection

@push('scripts')
<script>
async function buyNow(productId, btn) {
    // Guard: prevent double clicks
    if (btn.disabled) return;

    // Show button spinner
    btn.disabled = true;
    const textEl    = btn.querySelector('.btn-text');
    const spinnerEl = btn.querySelector('.btn-spinner');
    if (textEl)    textEl.textContent = 'Adding...';
    if (spinnerEl) spinnerEl.classList.remove('hidden');

    // Show global overlay — but we need to bypass bc-busy for the redirect,
    // so we do NOT set bc-busy here — just the progress bar
    if (window.BcLoader) {
        BcLoader.bar.style.opacity = '1';
        BcLoader.bar.style.width   = '40%';
    }

    try {
        const response = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     '{{ csrf_token() }}',
                'Accept':           'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ product_id: productId }),
        });

        const data = await response.json();

        if (data.error) {
            // Show error notification
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { message: data.error, type: 'error' }
            }));
            // Reset button
            btn.disabled = false;
            if (textEl)    textEl.textContent = 'Buy Now';
            if (spinnerEl) spinnerEl.classList.add('hidden');
            if (window.BcLoader) { BcLoader.bar.style.opacity = '0'; }
            return;
        }

        // Update cart badge
        window.dispatchEvent(new CustomEvent('cart-updated', {
            detail: { count: data.cart_count ?? data.count }
        }));

        // Now go to checkout — show full loader for the navigation
        if (window.BcLoader) BcLoader.show('Preparing checkout...');
        window.location.href = '{{ route("checkout") }}';

    } catch (err) {
        console.error('Buy Now failed:', err);
        btn.disabled = false;
        if (textEl)    textEl.textContent = 'Buy Now';
        if (spinnerEl) spinnerEl.classList.add('hidden');
        if (window.BcLoader) { BcLoader.bar.style.opacity = '0'; BcLoader.hide(); }
        window.dispatchEvent(new CustomEvent('notify', {
            detail: { message: 'Something went wrong. Please try again.', type: 'error' }
        }));
    }
}
</script>
@endpush
