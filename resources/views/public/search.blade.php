@extends('layouts.public')

@section('content')

{{-- ══════════════════════════════════════════════════════════════════════
    Bhavani Crafts — Artifact Search & Discovery Page
    ══════════════════════════════════════════════════════════════════════ --}}

<div class="min-h-screen bg-gray-50" x-data="searchPage()">

    {{-- Hero Search Header --}}
    <div class="bg-onyx-900 py-14 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#c5a021 1px, transparent 1px); background-size: 24px 24px;"></div>
        <div class="absolute top-0 left-1/3 w-96 h-96 bg-brand-500/20 rounded-full blur-3xl -translate-y-1/2"></div>
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center mb-10">
                <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-400 block mb-3">Product Search</span>
                <h1 class="text-4xl lg:text-5xl font-serif font-bold text-white italic mb-4">
                    Find Your <span class="text-brand-400">Product</span>
                </h1>
                <p class="text-gray-400 text-sm font-medium">Search through our collection of handcrafted brass idols, pooja items & gifts</p>
            </div>

            {{-- Main Search Bar --}}
            <form method="GET" action="{{ route('search') }}" class="max-w-2xl mx-auto" 
                  onsubmit="if(!this.q.value.trim()){ event.preventDefault(); return false; }">
                <div class="flex rounded-2xl overflow-hidden border-2 border-brand-500/30 bg-white/10 backdrop-blur-sm focus-within:border-brand-500 transition-all duration-300 shadow-2xl shadow-onyx-900/50">
                    <input type="text"
                           name="q"
                           id="hero-search"
                           value="{{ request('q') }}"
                           placeholder="Search brass idols, Ganesha, Lakshmi, pooja sets..."
                           autocomplete="off"
                           class="flex-1 h-16 px-6 bg-transparent text-white text-sm font-medium placeholder-white/30 border-none outline-none focus:ring-0">
                    {{-- Preserve active filters on search --}}
                    @foreach(request()->except('q') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <button type="submit"
                            class="px-8 bg-brand-500 hover:bg-brand-400 text-white font-black uppercase tracking-widest text-[11px] transition-all duration-300 flex items-center space-x-2 shrink-0">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <span class="hidden sm:block">Search</span>
                    </button>
                </div>
            </form>

            {{-- Quick Category Pills --}}
            <div class="flex flex-wrap gap-2 justify-center mt-6">
                <a href="{{ route('search') }}"
                   class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all
                          {{ !request('category') && !request('q') ? 'bg-brand-500 text-white' : 'bg-white/10 text-white/60 hover:bg-white/20 hover:text-white' }}">
                    All Products
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('search', ['category' => $cat->id, 'q' => request('q')]) }}"
                   class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all
                          {{ request('category') == $cat->id ? 'bg-brand-500 text-white' : 'bg-white/10 text-white/60 hover:bg-white/20 hover:text-white' }}">
                    {{ $cat->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-4 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- ────────────────────────────────────────────
                LEFT: Filters Sidebar
            ──────────────────────────────────────────── --}}
            <aside class="w-full lg:w-72 shrink-0">

                {{-- Mobile filter toggle --}}
                <button @click="filtersOpen = !filtersOpen"
                        class="lg:hidden w-full flex items-center justify-between p-4 bg-white rounded-2xl shadow-sm border border-gray-100 mb-4">
                    <span class="text-sm font-black uppercase tracking-widest text-onyx-900">Filters</span>
                    <div class="flex items-center space-x-2">
                        @if(request()->hasAny(['category','material','min_price','max_price','in_stock']))
                            <span class="h-5 w-5 bg-brand-500 text-white text-[9px] font-black rounded-full flex items-center justify-center">!</span>
                        @endif
                        <svg :class="filtersOpen ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </div>
                </button>

                <div :class="filtersOpen ? 'block' : 'hidden lg:block'">
                    <form method="GET" action="{{ route('search') }}" id="filter-form">
                        {{-- Preserve search query --}}
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif

                        {{-- Results count + Clear --}}
                        <div class="flex items-center justify-between mb-6">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                {{ $products->total() }} Results Found
                            </p>
                            @if(request()->hasAny(['category','material','min_price','max_price','in_stock']))
                            <a href="{{ route('search', request()->only('q', 'sort')) }}"
                               class="text-[10px] font-black uppercase tracking-widest text-brand-500 hover:text-onyx-900 transition-colors">
                                Clear All
                            </a>
                            @endif
                        </div>

                        {{-- ── Category ─────────────────────────────── --}}
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-4">
                            <h3 class="text-[10px] font-black uppercase tracking-[3px] text-onyx-900 mb-5 flex items-center space-x-2">
                                <svg class="h-3.5 w-3.5 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" /></svg>
                                <span>Category</span>
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="category" value=""
                                           {{ !request('category') ? 'checked' : '' }}
                                           class="h-4 w-4 text-brand-500 border-gray-200 focus:ring-brand-500/20"
                                           onchange="this.form.submit()">
                                    <span class="text-sm font-medium text-gray-600 group-hover:text-onyx-900 transition-colors">All Categories</span>
                                </label>
                                @foreach($categories as $cat)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="category" value="{{ $cat->id }}"
                                           {{ request('category') == $cat->id ? 'checked' : '' }}
                                           class="h-4 w-4 text-brand-500 border-gray-200 focus:ring-brand-500/20"
                                           onchange="this.form.submit()">
                                    <span class="text-sm font-medium text-gray-600 group-hover:text-onyx-900 transition-colors">{{ $cat->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- ── Material ──────────────────────────────── --}}
                        @if($materials->count() > 0)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-4">
                            <h3 class="text-[10px] font-black uppercase tracking-[3px] text-onyx-900 mb-5 flex items-center space-x-2">
                                <svg class="h-3.5 w-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                                <span>Material</span>
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="material" value=""
                                           {{ !request('material') ? 'checked' : '' }}
                                           class="h-4 w-4 text-brand-500 border-gray-200 focus:ring-brand-500/20"
                                           onchange="this.form.submit()">
                                    <span class="text-sm font-medium text-gray-600 group-hover:text-onyx-900 transition-colors">All Materials</span>
                                </label>
                                @foreach($materials as $mat)
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio" name="material" value="{{ $mat }}"
                                           {{ request('material') === $mat ? 'checked' : '' }}
                                           class="h-4 w-4 text-brand-500 border-gray-200 focus:ring-brand-500/20"
                                           onchange="this.form.submit()">
                                    <span class="text-sm font-medium text-gray-600 group-hover:text-onyx-900 transition-colors capitalize">{{ $mat }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        {{-- ── Price Range ───────────────────────────── --}}
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-4" x-data="priceSlider()">
                            <h3 class="text-[10px] font-black uppercase tracking-[3px] text-onyx-900 mb-5 flex items-center space-x-2">
                                <svg class="h-3.5 w-3.5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Price Range</span>
                            </h3>

                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-black text-brand-500" x-text="'₹' + minVal.toLocaleString()"></span>
                                <span class="text-sm font-black text-brand-500" x-text="'₹' + maxVal.toLocaleString()"></span>
                            </div>

                            {{-- Dual range inputs stacked --}}
                            <div class="relative h-2 mb-6">
                                <div class="absolute inset-0 bg-gray-100 rounded-full"></div>
                                <div class="absolute h-2 bg-brand-500 rounded-full"
                                     :style="`left: ${minPct}%; right: ${100 - maxPct}%`"></div>
                                <input type="range"
                                       :min="absMin" :max="absMax" x-model.number="minVal"
                                       @input="if(minVal > maxVal - 100) minVal = maxVal - 100"
                                       class="absolute w-full h-2 opacity-0 cursor-pointer z-10 thumb-range">
                                <input type="range"
                                       :min="absMin" :max="absMax" x-model.number="maxVal"
                                       @input="if(maxVal < minVal + 100) maxVal = minVal + 100"
                                       class="absolute w-full h-2 opacity-0 cursor-pointer z-20 thumb-range">
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Min (₹)</label>
                                    <input type="number" name="min_price" x-model.number="minVal"
                                           class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
                                </div>
                                <div>
                                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 block">Max (₹)</label>
                                    <input type="number" name="max_price" x-model.number="maxVal"
                                           class="w-full bg-gray-50 border border-gray-100 rounded-xl px-3 py-2 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
                                </div>
                            </div>
                            <button type="submit"
                                    class="w-full py-2.5 bg-brand-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-600 transition-all">
                                Apply Price
                            </button>
                        </div>

                        {{-- ── In Stock ──────────────────────────────── --}}
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-6">
                            <label class="flex items-center justify-between cursor-pointer">
                                <div class="flex items-center space-x-3">
                                    <svg class="h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-sm font-bold text-onyx-900">In Stock Only</span>
                                </div>
                                <input type="checkbox" name="in_stock" value="1"
                                       {{ request('in_stock') ? 'checked' : '' }}
                                       class="h-4 w-4 text-brand-500 border-gray-200 rounded focus:ring-brand-500/20"
                                       onchange="this.form.submit()">
                            </label>
                        </div>
                    </form>
                </div>
            </aside>

            {{-- ────────────────────────────────────────────
                RIGHT: Results
            ──────────────────────────────────────────── --}}
            <div class="flex-1 min-w-0">

                {{-- Results toolbar --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        @if(request('q'))
                            <h2 class="text-xl font-black text-onyx-900">
                                Results for <span class="text-brand-500 italic">"{{ request('q') }}"</span>
                            </h2>
                        @elseif(request('category'))
                            <h2 class="text-xl font-black text-onyx-900">
                                {{ $categories->find(request('category'))?->name ?? 'Category' }}
                            </h2>
                        @else
                            <h2 class="text-xl font-black text-onyx-900">All Products</h2>
                        @endif
                        <p class="text-xs text-gray-400 font-medium mt-1">{{ $products->total() }} products found</p>
                    </div>

                    {{-- Sort --}}
                    <form method="GET" action="{{ route('search') }}" class="flex items-center space-x-3" data-no-loader>
                        @foreach(request()->except('sort') as $k => $v)
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endforeach
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Sort:</label>
                        <select name="sort" onchange="this.form.submit()"
                                class="bg-white border border-gray-100 rounded-xl px-4 py-2.5 text-sm font-bold text-onyx-900 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400 cursor-pointer shadow-sm">
                            <option value="newest"     {{ request('sort') === 'newest'     ? 'selected' : '' }}>Newest First</option>
                            <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular"    {{ request('sort') === 'popular'    ? 'selected' : '' }}>Most Popular</option>
                        </select>
                    </form>
                </div>

                {{-- Active filter tags --}}
                @if(request()->hasAny(['category','material','min_price','max_price','in_stock']))
                <div class="flex flex-wrap gap-2 mb-6">
                    @if(request('category'))
                        <a href="{{ route('search', request()->except('category')) }}"
                           class="flex items-center space-x-1.5 px-3 py-1.5 bg-brand-50 border border-brand-200 rounded-full text-[10px] font-black uppercase tracking-widest text-brand-600 hover:bg-brand-100 transition-colors">
                            <span>Category: {{ $categories->find(request('category'))?->name }}</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                        </a>
                    @endif
                    @if(request('material'))
                        <a href="{{ route('search', request()->except('material')) }}"
                           class="flex items-center space-x-1.5 px-3 py-1.5 bg-brand-50 border border-brand-200 rounded-full text-[10px] font-black uppercase tracking-widest text-brand-600 hover:bg-brand-100 transition-colors">
                            <span>Material: {{ request('material') }}</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                        </a>
                    @endif
                    @if(request('min_price') || request('max_price'))
                        <a href="{{ route('search', request()->except('min_price','max_price')) }}"
                           class="flex items-center space-x-1.5 px-3 py-1.5 bg-brand-50 border border-brand-200 rounded-full text-[10px] font-black uppercase tracking-widest text-brand-600 hover:bg-brand-100 transition-colors">
                            <span>₹{{ request('min_price', 0) }} – ₹{{ request('max_price', '∞') }}</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                        </a>
                    @endif
                    @if(request('in_stock'))
                        <a href="{{ route('search', request()->except('in_stock')) }}"
                           class="flex items-center space-x-1.5 px-3 py-1.5 bg-brand-50 border border-brand-200 rounded-full text-[10px] font-black uppercase tracking-widest text-brand-600 hover:bg-brand-100 transition-colors">
                            <span>In Stock</span>
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                        </a>
                    @endif
                </div>
                @endif

                @if(count($products) > 0)
                    {{-- ── Product Grid ────────────────────────── --}}
                    <div x-data="infiniteScroll()" class="space-y-12">
                        <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                @include('public.partials.product_card', ['product' => $product])
                            @endforeach
                            
                            {{-- JS Dynamic Artifacts will be appended here --}}
                            <template x-for="pItem in dynamicProducts" :key="pItem.id">
                                <div x-html="renderProductCard(pItem)"></div>
                            </template>
                        </div>

                        {{-- Loading / Infinite Scroll Trigger --}}
                        <div x-show="hasMore" x-intersect="loadMore()" class="py-12 flex flex-col items-center justify-center min-h-[100px]">
                            <template x-if="loading">
                                <div class="flex flex-col items-center animate-fadeIn">
                                    <div class="flex space-x-2 mb-4">
                                        <div class="h-2 w-2 bg-brand-500 rounded-full animate-bounce [animation-delay:-0.3s]"></div>
                                        <div class="h-2 w-2 bg-brand-500 rounded-full animate-bounce [animation-delay:-0.15s]"></div>
                                        <div class="h-2 w-2 bg-brand-500 rounded-full animate-bounce"></div>
                                    </div>
                                    <span class="text-[10px] font-black uppercase tracking-[3px] text-gray-400">Revealing more items...</span>
                                </div>
                            </template>
                        </div>

                        <div x-show="!hasMore && dynamicProducts.length > 0" class="py-12 text-center">
                            <span class="h-px w-12 bg-gray-200 inline-block align-middle mr-4"></span>
                            <span class="text-[10px] font-black uppercase tracking-[3px] text-gray-300 italic">No more products to show</span>
                            <span class="h-px w-12 bg-gray-200 inline-block align-middle ml-4"></span>
                        </div>
                    </div>
                @else
                    {{-- No Results --}}
                    <div class="py-24 text-center flex flex-col items-center justify-center">
                        <div class="h-24 w-24 bg-gray-100 rounded-full flex items-center justify-center mb-8 mx-auto">
                            <svg class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-onyx-900 italic mb-3">No Products Found</h3>
                        <p class="text-gray-400 text-sm font-medium mb-8 max-w-sm">
                            @if(request('q'))
                                No results for <strong>"{{ request('q') }}"</strong>. Try different keywords or browse our full collection.
                            @else
                                No artifacts match your current filters. Try adjusting your search criteria.
                            @endif
                        </p>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('search') }}" class="px-8 py-3 bg-brand-500 text-white font-black uppercase tracking-[3px] text-[10px] rounded-xl hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20">
                                View All Products
                            </a>
                            <a href="{{ route('home') }}" class="px-8 py-3 border border-gray-200 text-onyx-900 font-black uppercase tracking-[3px] text-[10px] rounded-xl hover:border-brand-500 hover:text-brand-500 transition-all">
                                Back to Home
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.thumb-range::-webkit-slider-thumb { -webkit-appearance: none; width: 20px; height: 20px; background: #c5a021; border-radius: 50%; cursor: pointer; box-shadow: 0 2px 8px rgba(197,160,33,0.4); }
.thumb-range::-moz-range-thumb { width: 20px; height: 20px; background: #c5a021; border-radius: 50%; cursor: pointer; border: none; }
</style>

<script>
function searchPage() {
    return { filtersOpen: false };
}

function priceSlider() {
    return {
        absMin: {{ $priceRange['min'] }},
        absMax: {{ $priceRange['max'] }},
        minVal: {{ request('min_price', $priceRange['min']) }},
        maxVal: {{ request('max_price', $priceRange['max']) }},
        get minPct() { return ((this.minVal - this.absMin) / (this.absMax - this.absMin)) * 100; },
        get maxPct() { return ((this.maxVal - this.absMin) / (this.absMax - this.absMin)) * 100; },
    };
}

async function addToCart(productId, btn) {
    if (!btn) btn = event?.currentTarget;
    BcLoader.show('Adding to cart...');
    if (btn) { btn._orig = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>'; }
    try {
        const r = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        });
        const data = await r.json();
        window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
        window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Added to Cart!', type: 'success' } }));
    } finally {
        BcLoader.hide();
        if (btn) { btn.disabled = false; btn.innerHTML = btn._orig; }
    }
}

async function toggleWishlist(productId, btn) {
    BcLoader.show('Saving to wishlist...');
    try {
        const r = await fetch('{{ route("wishlist.toggle") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        });
        const data = await r.json();
        const svg = btn.querySelector('svg');
        if (data.status === 'added') {
            svg.setAttribute('fill', '#c5a021');
            svg.setAttribute('stroke', '#c5a021');
            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Saved to Wishlist', type: 'success' } }));
        } else {
            svg.setAttribute('fill', 'none');
            svg.setAttribute('stroke', 'currentColor');
            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Removed from Wishlist', type: 'info' } }));
        }
        window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: { count: data.count ?? 0 } }));
    } finally {
        BcLoader.hide();
    }
}

function infiniteScroll() {
    return {
        dynamicProducts: [],
        page: 1,
        hasMore: {{ (is_object($products) && method_exists($products, 'hasMorePages')) ? ($products->hasMorePages() ? 'true' : 'false') : 'false' }},
        loading: false,
        async loadMore() {
            if (this.loading || !this.hasMore) return;
            this.loading = true;
            this.page++;
            
            const gqlQuery = `
                query Search($q: String, $cat: ID, $page: Int) {
                    searchProducts(q: $q, category: $cat, page: $page) {
                        data {
                            id
                            product_name
                            slug
                            price
                            mrp
                            discount_percent
                            stock
                            material_type
                            category { name }
                            images { image_url is_main }
                        }
                        paginatorInfo {
                            hasMorePages
                        }
                    }
                }
            `;
            
            try {
                const r = await fetch('/graphql', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        query: gqlQuery,
                        variables: { 
                            q: "{{ request('q') }}", 
                            cat: "{{ request('category') }}",
                            page: this.page 
                        }
                    })
                });
                const res = await r.json();
                if (res.data && res.data.searchProducts) {
                    this.dynamicProducts.push(...res.data.searchProducts.data);
                    this.hasMore = res.data.searchProducts.paginatorInfo.hasMorePages;
                }
            } catch (err) {
                console.error('Infinite Scroll Error:', err);
                this.hasMore = false;
            } finally {
                this.loading = false;
            }
        },
        renderProductCard(p) {
            const img = p.images.find(i => i.is_main) || p.images[0];
            const imgUrl = img ? `/storage/${img.image_url}` : '';
            const discountBadge = p.discount_percent > 0 ? `<span class="bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">${p.discount_percent}% OFF</span>` : '';
            const stockBadge = (p.stock > 0 && p.stock <= 5) ? `<span class="bg-orange-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full animate-pulse">Only ${p.stock} left</span>` : (p.stock == 0 ? `<span class="bg-gray-800/80 text-white text-[9px] font-black px-2 py-0.5 rounded-full">Out of Stock</span>` : '');

            return `
                <a href="/${window.AppLocale}/artifact/${p.slug}" class="group bg-white rounded-[1.5rem] border border-gray-100 hover:border-brand-500/30 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-500 flex flex-col h-full">
                    <div class="relative aspect-square overflow-hidden bg-gray-50">
                        <img src="${imgUrl}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-3 left-3 flex flex-col gap-1">${discountBadge}${stockBadge}</div>
                    </div>
                    <div class="p-5 flex flex-col flex-1">
                        <span class="text-[9px] font-black uppercase tracking-[2px] text-brand-500 mb-1">${p.category ? p.category.name : 'Artifact'}</span>
                        <h3 class="text-sm font-bold text-onyx-900 leading-snug mb-1 group-hover:text-brand-600 transition-colors line-clamp-2">${p.product_name}</h3>
                        <div class="mt-auto flex items-center justify-between">
                            <div>
                                <p class="text-lg font-black text-onyx-900">${window.AppCurrency.symbol}${(p.price * window.AppCurrency.rate).toLocaleString()}</p>
                            </div>
                            <button onclick="event.preventDefault(); addToCart(${p.id})" class="h-9 w-9 bg-brand-500 text-white rounded-xl flex items-center justify-center hover:bg-brand-600 transition-all shadow-md shadow-brand-500/20 hover:scale-110">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            </button>
                        </div>
                    </div>
                </a>
            `;
        }
    };
}
</script>
@endsection
