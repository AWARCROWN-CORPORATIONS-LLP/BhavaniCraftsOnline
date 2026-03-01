@extends('layouts.public')

@section('content')
<div class="bg-white min-h-screen pt-12 pb-24" x-data="{ 
    mainImage: '{{ $product->images->where('is_main', true)->first() ? \Illuminate\Support\Facades\Storage::url($product->images->where('is_main', true)->first()->image_url) : ($product->images->first() ? \Illuminate\Support\Facades\Storage::url($product->images->first()->image_url) : '') }}',
    quantity: 1
}">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-12 text-[10px] font-black uppercase tracking-[3px] text-gray-400">
            <a href="{{ route('home') }}" class="hover:text-brand-500 transition-colors">Temple</a>
            <span class="mx-3 opacity-30">/</span>
            <span class="hover:text-brand-500 transition-colors capitalize">{{ $product->category->name ?? 'Artifacts' }}</span>
            <span class="mx-3 opacity-30">/</span>
            <span class="text-onyx-900">{{ $product->product_name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 xl:gap-24">
            <!-- Image Gallery -->
            <div class="space-y-6">
                <div class="relative aspect-square rounded-[3rem] overflow-hidden bg-gray-50 border border-gray-100 group shadow-2xl shadow-brand-900/5">
                    <img :src="mainImage" 
                         alt="{{ $product->product_name }}" 
                         class="w-full h-full object-cover transition-all duration-700">
                    
                    @if($product->discount_percent > 0)
                    <div class="absolute top-8 left-8 bg-brand-500 text-white px-6 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl">
                        {{ $product->discount_percent }}% Blessing
                    </div>
                    @endif
                </div>

                <!-- Thumbnails -->
                <div class="grid grid-cols-3 gap-6">
                    @foreach($product->images as $image)
                    <button @click="mainImage = '{{ \Illuminate\Support\Facades\Storage::url($image->image_url) }}'"
                            class="aspect-square rounded-2xl overflow-hidden border-2 transition-all duration-300 hover:scale-105"
                            :class="mainImage === '{{ \Illuminate\Support\Facades\Storage::url($image->image_url) }}' ? 'border-brand-500 shadow-lg shadow-brand-500/20' : 'border-transparent opacity-60 hover:opacity-100'">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->image_url) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <div class="mb-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <span class="h-1px w-10 bg-brand-500/30"></span>
                        <span class="text-[10px] font-black text-brand-500 uppercase tracking-[4px]">{{ $product->material_type ?? 'Handcrafted' }} Artifact</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-onyx-900 mb-4 italic leading-tight">
                        {{ $product->product_name }}
                    </h1>
                    
                    @if($product->telugu_name)
                    <p class="text-xl font-medium text-brand-600 mb-6 font-telugu italic opacity-80">
                        {{ $product->telugu_name }}
                    </p>
                    @endif

                    <div class="flex items-end space-x-4 mb-8">
                        <span class="text-3xl font-black text-onyx-900">₹{{ number_format($product->price, 2) }}</span>
                        @if($product->mrp > $product->price)
                        <span class="text-lg text-gray-300 line-through italic mb-1">₹{{ number_format($product->mrp, 2) }}</span>
                        @endif
                    </div>

                    <p class="text-gray-500 leading-relaxed font-medium mb-10 max-w-xl">
                        {{ $product->short_description }}
                    </p>

                    <!-- Features/Badges -->
                    <div class="grid grid-cols-2 gap-4 mb-10">
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-2xl border border-gray-100/50">
                            <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-brand-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <span class="text-[10px] font-bold text-onyx-900 uppercase tracking-widest">Ritual Verified</span>
                        </div>
                        <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-2xl border border-gray-100/50">
                            <div class="h-10 w-10 bg-white rounded-xl flex items-center justify-center shadow-sm text-brand-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            </div>
                            <span class="text-[10px] font-bold text-onyx-900 uppercase tracking-widest">Heritage Piece</span>
                        </div>
                    </div>
                </div>

                <!-- Purchase Section -->
                <div class="mt-auto space-y-6">
                    <div class="flex items-center space-x-6">
                        <!-- Quantity -->
                        <div class="flex items-center bg-gray-50 rounded-2xl border border-gray-100 p-2">
                            <button @click="if(quantity > 1) quantity--" class="h-10 w-10 flex items-center justify-center text-onyx-900 hover:text-brand-500 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                            </button>
                            <span class="w-12 text-center text-sm font-black text-onyx-900" x-text="quantity"></span>
                            <button @click="quantity++" class="h-10 w-10 flex items-center justify-center text-onyx-900 hover:text-brand-500 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>

                        <!-- Add to Cart -->
                        <button type="button" 
                                x-data="{ loading: false }"
                                :disabled="loading"
                                @click="loading = true; fetch('{{ route('cart.add') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                    body: JSON.stringify({ product_id: {{ $product->id }}, quantity: quantity })
                                }).then(r => r.json()).then(data => {
                                    $dispatch('cart-updated', { count: data.cart_count });
                                    $dispatch('notify', { message: data.message });
                                    loading = false;
                                })"
                                class="flex-1 bg-brand-500 text-white h-14 rounded-2xl text-[11px] font-black uppercase tracking-[3px] shadow-xl shadow-brand-500/20 hover:bg-brand-600 transition-all flex items-center justify-center space-x-3 group">
                            <span x-show="!loading">Add to Sacred Cart</span>
                            <svg x-show="loading" class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>
                    </div>

                    <!-- Buy Now / Wishlist -->
                    <div class="flex items-center space-x-4">
                        <form action="{{ route('cart.buy-now') }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="w-full bg-onyx-900 text-white h-14 rounded-2xl text-[11px] font-black uppercase tracking-[3px] shadow-xl shadow-onyx-900/20 hover:bg-black transition-all">
                                Divine Buy Now
                            </button>
                        </form>

                        <button type="button" 
                                x-data="{ loading: false }"
                                @click="loading = true; fetch('{{ route('wishlist.toggle') }}', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                    body: JSON.stringify({ product_id: {{ $product->id }} })
                                }).then(r => r.json()).then(data => {
                                    if(data.error) { window.location.href = '{{ route('login') }}'; return; }
                                    $dispatch('notify', { message: data.message });
                                    loading = false;
                                })"
                                class="h-14 w-14 bg-gray-50 text-onyx-900 rounded-2xl flex items-center justify-center border border-gray-100 hover:text-brand-500 transition-colors shadow-sm group">
                            <svg class="h-6 w-6 transform group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Long Description -->
        @if($product->full_description)
        <div class="mt-32 pt-24 border-t border-gray-100 max-w-4xl mx-auto">
            <h2 class="text-3xl font-serif font-bold text-onyx-900 mb-12 italic text-center">Artifact <span class="text-brand-500">Chronicle</span></h2>
            <div class="prose prose-lg max-w-none text-gray-500 font-medium leading-[2]">
                {!! $product->full_description !!}
            </div>
        </div>
        @endif

        <!-- Specifications Tab/Table -->
        <div class="mt-24 max-w-4xl mx-auto">
            <h3 class="text-xl font-bold text-onyx-900 mb-8 uppercase tracking-widest italic">Divine Specifications</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 border-t border-gray-100 pt-8">
                <div class="flex justify-between py-4 border-b border-gray-50">
                    <span class="text-[10px] font-black uppercase text-gray-400">Material Composition</span>
                    <span class="text-sm font-bold text-onyx-900">{{ $product->material_type ?? 'Superior Brass' }}</span>
                </div>
                <div class="flex justify-between py-4 border-b border-gray-50">
                    <span class="text-[10px] font-black uppercase text-gray-400">Creation Method</span>
                    <span class="text-sm font-bold text-onyx-900">{{ $product->made_type ?? 'Hand-forged' }}</span>
                </div>
                <div class="flex justify-between py-4 border-b border-gray-50">
                    <span class="text-[10px] font-black uppercase text-gray-400">Customization</span>
                    <span class="text-sm font-bold text-onyx-900">{{ $product->customizable ? 'Available' : 'Standard' }}</span>
                </div>
                <div class="flex justify-between py-4 border-b border-gray-50">
                    <span class="text-[10px] font-black uppercase text-gray-400">Sanctuary Shipping</span>
                    <span class="text-sm font-bold text-onyx-900">{{ $product->requires_shipping ? 'Global Logistics' : 'In-store Only' }}</span>
                </div>
                <div class="flex justify-between py-4 border-b border-gray-50">
                    <span class="text-[10px] font-black uppercase text-gray-400">Festival Alignment</span>
                    <span class="text-sm font-bold text-onyx-900">{{ $product->festival_use ?? 'Universal' }}</span>
                </div>
                <div class="flex justify-between py-4 border-b border-gray-50">
                    <span class="text-[10px] font-black uppercase text-gray-400">Replacement Guarantee</span>
                    <span class="text-sm font-bold text-onyx-900">{{ $product->replacement_available ? 'Protected' : 'Standard Sale' }}</span>
                </div>
            </div>
        </div>

        <!-- Related Artifacts -->
        @if($relatedProducts->count() > 0)
        <div class="mt-32 pt-24 border-t border-gray-100">
            <div class="flex items-center justify-between mb-12">
                <div>
                    <span class="text-[10px] font-black text-brand-500 uppercase tracking-[4px] mb-2 block">Heritage Collection</span>
                    <h2 class="text-3xl font-serif font-bold text-onyx-900 italic">Divine <span class="text-brand-500">Pairings</span></h2>
                </div>
                <a href="{{ route('home') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-brand-500 transition-colors">Explore Gallery</a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach($relatedProducts as $rel)
                <div class="group">
                    <a href="{{ route('artifact.show', $rel->id) }}" class="block aspect-[3/4] rounded-3xl overflow-hidden bg-gray-50 mb-6 border border-gray-100 group-hover:shadow-2xl group-hover:shadow-brand-900/10 transition-all duration-500">
                        <img src="{{ $rel->images->first() ? \Illuminate\Support\Facades\Storage::url($rel->images->first()->image_url) : '' }}" 
                             alt="{{ $rel->product_name }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    </a>
                    <h4 class="text-sm font-bold text-onyx-900 mb-1 truncate">{{ $rel->product_name }}</h4>
                    <p class="text-[11px] font-black text-brand-500">₹{{ number_format($rel->price, 2) }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
