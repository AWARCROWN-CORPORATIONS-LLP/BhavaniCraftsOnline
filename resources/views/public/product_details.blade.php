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
            <!-- Image Gallery (Amazon/Flipkart Style Layout) -->
            <div class="flex flex-col-reverse md:flex-row gap-6 h-fit sticky top-24">
                <!-- Thumbnails (Vertical on desktop, horizontal on mobile) -->
                <div class="flex md:flex-col gap-4 overflow-x-auto md:overflow-y-auto md:w-24 shrink-0 pb-2 md:pb-0 hide-scrollbar" style="max-height: calc(100vh - 120px)">
                    @foreach($product->images as $image)
                    <button @click="mainImage = '{{ \Illuminate\Support\Facades\Storage::url($image->image_url) }}'"
                            class="aspect-square w-20 md:w-full rounded-2xl overflow-hidden border-2 transition-all duration-300 hover:scale-[1.02] shrink-0"
                            :class="mainImage === '{{ \Illuminate\Support\Facades\Storage::url($image->image_url) }}' ? 'border-brand-500 shadow-xl shadow-brand-500/20' : 'border-transparent opacity-60 hover:opacity-100 hover:border-gray-200 bg-gray-50'">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($image->image_url) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>

                <!-- Main Image -->
                <div class="relative w-full aspect-[4/5] md:aspect-auto md:h-[calc(100vh-120px)] rounded-[2rem] overflow-hidden bg-gray-50 border border-gray-100 group shadow-2xl shadow-brand-900/5">
                    <img :src="mainImage" 
                         alt="{{ $product->product_name }}" 
                         class="w-full h-full object-contain md:object-cover transition-all duration-700 bg-white">
                    
                    @if($product->discount_percent > 0)
                    <div class="absolute top-6 left-6 bg-brand-500 text-white px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl">
                        {{ $product->discount_percent }}% OFF
                    </div>
                    @endif

                    <!-- Zoom Hint -->
                    <div class="absolute bottom-6 right-6 bg-white/90 backdrop-blur px-4 py-2 rounded-xl text-[10px] font-bold text-gray-500 shadow-lg pointer-events-none hidden md:block">
                        Hover to inspect artifact
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="flex flex-col">
                <div class="mb-10">
                    <div class="flex items-center space-x-3 mb-6">
                        <span class="h-1px w-10 bg-brand-500/30"></span>
                        <span class="text-[10px] font-black text-brand-500 uppercase tracking-[4px]">{{ $product->material_type ?? 'Handcrafted' }} Artifact</span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-onyx-900 mb-2 leading-tight">
                        {{ $product->product_name }}
                    </h1>
                    
                    @if($product->telugu_name)
                    <p class="text-lg font-medium text-brand-600 mb-4 font-telugu opacity-80">
                        {{ $product->telugu_name }}
                    </p>
                    @endif

                    <!-- Review Aggregate Summary -->
                    @if($product->reviews->count() > 0)
                        <div class="flex items-center space-x-2 mb-6">
                            @php
                                $avgRating = round($product->reviews->avg('rating'), 1);
                                $totalReviews = $product->reviews->count();
                            @endphp
                            <div class="flex text-brand-500 text-sm">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="h-4 w-4 {{ $i <= round($avgRating) ? 'text-brand-500' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-sm font-bold text-onyx-900">{{ $avgRating }}</span>
                            <span class="text-xs font-bold text-gray-400">({{ $totalReviews }} Testimonies)</span>
                        </div>
                    @endif

                    <div class="flex items-end space-x-3 mb-6">
                        <span class="text-4xl font-black text-onyx-900 tracking-tight">₹{{ number_format($product->price, 2) }}</span>
                        @if($product->mrp > $product->price)
                            <div class="flex flex-col pb-1">
                                <span class="text-sm text-gray-400 font-bold">M.R.P: <span class="line-through italic">₹{{ number_format($product->mrp, 2) }}</span></span>
                            </div>
                        @endif
                    </div>
                    
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">Inclusive of all taxes</p>

                    <div class="w-full h-px bg-gray-100 mb-8"></div>

                    <p class="text-gray-600 leading-relaxed font-medium mb-10 max-w-xl text-sm border-l-4 border-brand-500 pl-4 bg-brand-50/50 py-3 pr-3 rounded-r-2xl">
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

                <!-- Unified Purchase Action Box -->
                <div class="border border-gray-200 rounded-[2rem] p-6 lg:p-8 bg-white shadow-xl shadow-brand-900/5 mt-auto sticky bottom-0 z-10 lg:static">
                    
                    <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-6">
                        <span class="text-sm font-bold text-onyx-900">Total Selection</span>
                        <span class="text-2xl font-black text-onyx-900" x-text="'₹' + ({{ $product->price }} * quantity).toLocaleString()"></span>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Quantity</span>
                            <!-- Quantity -->
                            <div class="flex items-center bg-gray-50 rounded-xl border border-gray-200 p-1">
                                <button @click="if(quantity > 1) quantity--" class="h-8 w-8 flex items-center justify-center text-onyx-900 hover:text-brand-500 hover:bg-white rounded-lg transition-all shadow-sm shadow-transparent hover:shadow-gray-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                </button>
                                <span class="w-10 text-center text-sm font-black text-onyx-900" x-text="quantity"></span>
                                <button @click="quantity++" class="h-8 w-8 flex items-center justify-center text-onyx-900 hover:text-brand-500 hover:bg-white rounded-lg transition-all shadow-sm shadow-transparent hover:shadow-gray-200">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                </button>
                            </div>
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
                                class="w-full bg-brand-50 text-brand-600 border-2 border-brand-500 h-14 rounded-[1rem] text-[11px] font-black uppercase tracking-[2px] transition-all flex items-center justify-center space-x-3 group hover:bg-brand-500 hover:text-white">
                            <span x-show="!loading">Add to Cart</span>
                            <svg x-show="loading" class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </button>

                        <div class="flex items-center space-x-3">
                            <form action="{{ route('cart.buy-now') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="w-full bg-brand-500 hover:bg-brand-600 text-white h-14 rounded-[1rem] text-[11px] font-black uppercase tracking-[2px] shadow-lg shadow-brand-500/20 transition-all">
                                    Buy Now
                                </button>
                            </form>

                            <button type="button" 
                                    x-data="{ loading: false }"
                                    title="Add to Collection"
                                    @click="loading = true; fetch('{{ route('wishlist.toggle') }}', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                                        body: JSON.stringify({ product_id: {{ $product->id }} })
                                    }).then(r => r.json()).then(data => {
                                        if(data.error) { window.location.href = '{{ route('login') }}'; return; }
                                        $dispatch('notify', { message: data.message });
                                        loading = false;
                                    })"
                                    class="h-14 w-14 shrink-0 bg-gray-50 text-gray-500 rounded-[1rem] flex items-center justify-center border border-gray-200 hover:text-brand-500 hover:border-brand-300 hover:bg-brand-50 transition-colors group">
                                <svg class="h-6 w-6 transform group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </div>
                        
                        <!-- Trust Signals -->
                        <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-between px-2 opacity-70">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-6 h-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span class="text-[9px] font-bold uppercase text-gray-500 text-center">Secure<br>Transaction</span>
                            </div>
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-6 h-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-[9px] font-bold uppercase text-gray-500 text-center">Dispatched<br>in 48h</span>
                            </div>
                            @if($product->replacement_available)
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <svg class="w-6 h-6 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                <span class="text-[9px] font-bold uppercase text-gray-500 text-center">Easy<br>Returns</span>
                            </div>
                            @endif
                        </div>
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

        <!-- Reviews Section -->
        <div class="mt-24 pt-24 border-t border-gray-100 max-w-4xl mx-auto">
            <h3 class="text-3xl font-serif font-bold text-onyx-900 mb-12 italic text-center">Sacred <span class="text-brand-500">Testimonies</span></h3>

            <!-- Review Form -->
            @auth
                <div class="bg-gray-50 rounded-[2rem] p-8 mb-16 border border-gray-100">
                    <h4 class="text-lg font-bold text-onyx-900 mb-6 font-serif italic">Share your Divine Experience</h4>
                    <form action="{{ route('artifact.reviews.store', $product->encryptedId()) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Sacred Rating (1-5)</label>
                            <input type="number" name="rating" min="1" max="5" value="5" class="w-full h-12 bg-white border border-gray-200 rounded-xl px-4 text-sm font-bold focus:ring-brand-500 focus:border-brand-500" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Your Testimony</label>
                            <textarea name="comment" rows="4" class="w-full bg-white border border-gray-200 rounded-xl p-4 text-sm focus:ring-brand-500 focus:border-brand-500" placeholder="Chronicle your experience with this artifact..."></textarea>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase text-gray-400 mb-2">Artifact Evidence (Optional Image)</label>
                            <input type="file" name="image" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-colors">
                        </div>
                        <button type="submit" class="px-8 py-3 bg-brand-500 text-white text-[11px] font-black uppercase tracking-[2px] rounded-xl hover:bg-brand-600 transition-colors shadow-lg shadow-brand-500/20">
                            Offer Testimony
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center p-8 bg-gray-50 rounded-[2rem] border border-gray-100 mb-16">
                    <p class="text-sm font-bold text-gray-500 mb-4">You must enter the sanctuary to leave a testimony.</p>
                    <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-onyx-900 text-white text-[11px] font-black uppercase tracking-[2px] rounded-xl hover:bg-black transition-colors">
                        Authenticate Symbol
                    </a>
                </div>
            @endauth

            <!-- Display Reviews -->
            <div class="space-y-8">
                @forelse($product->reviews as $review)
                <div class="flex space-x-6 p-8 bg-white rounded-[2rem] border border-gray-50 shadow-sm">
                    <div class="h-12 w-12 bg-brand-50 rounded-full flex items-center justify-center shrink-0">
                        <span class="text-brand-500 font-bold uppercase text-lg">{{ substr($review->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h5 class="font-bold text-onyx-900 text-sm">{{ $review->user->name }}</h5>
                                <div class="flex text-brand-500 text-xs mt-1">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-brand-500' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <span class="text-[9px] font-black uppercase text-gray-400 tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500 leading-relaxed">{{ $review->comment }}</p>
                        @if($review->image_url)
                            <div class="mt-4 mt-6">
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($review->image_url) }}" class="h-32 w-32 object-cover rounded-2xl border border-gray-100 shadow-sm cursor-pointer hover:scale-105 transition-transform" onclick="window.open(this.src)">
                            </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12">
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">No testimonies registered yet.</p>
                </div>
                @endforelse
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
                    <a href="{{ route('artifact.show', $rel->encryptedId()) }}" class="block aspect-[3/4] rounded-3xl overflow-hidden bg-gray-50 mb-6 border border-gray-100 group-hover:shadow-2xl group-hover:shadow-brand-900/10 transition-all duration-500">
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
