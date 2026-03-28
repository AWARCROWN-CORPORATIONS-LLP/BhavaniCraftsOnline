@extends('layouts.public')

@section('content')

    <!-- DYNAMIC SACRED HERO SECTION -->
    <section class="relative h-[600px] md:h-[750px] w-full flex items-center justify-center overflow-hidden bg-onyx-950">
        <!-- Background Img with Parallax-ready feel -->
        <div class="absolute inset-0 z-0">
            <img src="{{ $pageContent['hero_bg_image'] ?? 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop' }}" 
                 class="w-full h-full object-cover opacity-50 scale-105" 
                 alt="Bhavani Crafts Ritual Heritage">
            <div class="absolute inset-0 bg-gradient-to-b from-onyx-950/80 via-onyx-950/40 to-white"></div>
        </div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center flex flex-col items-center">
            <!-- Animated Title Badge -->
            <div class="mb-8 flex items-center space-x-4 animate-fadeInDown">
                <div class="h-[1px] w-12 bg-amber-400"></div>
                <span class="text-[10px] md:text-[12px] font-black uppercase tracking-[6px] text-amber-400 italic">Established 1993 • Sacred Artisans</span>
                <div class="h-[1px] w-12 bg-amber-400"></div>
            </div>

            <h1 class="font-serif text-5xl md:text-8xl font-bold text-white leading-none tracking-tight mb-8 drop-shadow-2xl animate-fadeIn">
                @if(isset($pageContent['hero_title']))
                    {!! nl2br(e($pageContent['hero_title'])) !!}
                @else
                    Sacred Rituals,<br><span class="text-brand-500">Ancient Craft.</span>
                @endif
            </h1>

            <p class="max-w-2xl text-lg md:text-2xl text-gray-200/90 font-medium leading-relaxed mb-12 animate-fadeInUp delay-200">
                {{ $pageContent['hero_subtitle'] ?? 'Your global hub for authentic Brass Idols, Pasupu-Kunkuma, and Marriage Ceremony Essentials. Wholesale quality, Retail devotion.' }}
            </p>

            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-8 animate-fadeInUp delay-300">
                <!-- Retail Path -->
                <a href="{{ route('search') }}" class="group relative px-10 py-5 bg-white rounded-2xl overflow-hidden shadow-2xl transition-all duration-500 hover:scale-105 active:scale-95">
                    <div class="absolute inset-0 bg-gradient-to-r from-amber-400 to-amber-600 opacity-0 group-hover:opacity-10 transition-opacity"></div>
                    <span class="relative text-[11px] font-black uppercase tracking-[4px] text-onyx-950">{{ $pageContent['hero_cta_retail'] ?? 'Retail Collection' }}</span>
                </a>

                <!-- Wholesale Path -->
                <a href="{{ route('register') }}" class="group px-10 py-5 bg-brand-600/90 backdrop-blur-md rounded-2xl border border-brand-500/30 shadow-2xl transition-all duration-500 hover:bg-brand-500 hover:scale-105 active:scale-95">
                    <span class="text-[11px] font-black uppercase tracking-[4px] text-white">{{ $pageContent['hero_cta_wholesale'] ?? 'Wholesale Portal' }}</span>
                </a>
            </div>

            <!-- Trust Markers -->
            <div class="mt-20 grid grid-cols-3 gap-8 md:gap-16 opacity-60">
                <div class="flex flex-col items-center">
                    <span class="text-lg md:text-2xl font-black text-white">10k+</span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Global Deliveries</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-lg md:text-2xl font-black text-white">Pure</span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Brass Guarantee</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-lg md:text-2xl font-black text-white">100%</span>
                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Hand-Forged</span>
                </div>
            </div>
        </div>
    </section>

    <!-- SACRED CATEGORY WHEEL: STAGGERED ENTRANCE -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true" 
             class="py-12 bg-white border-b border-gray-50 overflow-hidden no-scrollbar">
        <div class="container mx-auto px-4 lg:px-8 no-scrollbar">
            <div class="flex items-center space-x-10 md:space-x-16 overflow-x-auto no-scrollbar pb-6">
                @foreach($categories as $index => $category)
                    <a wire:navigate href="{{ route('search', ['category' => $category->id]) }}"
                       x-data="{ shown: false }"
                       x-intersect.half.once="shown = true"
                       :class="shown ? 'opacity-100 translate-y-0 scale-100' : 'opacity-0 translate-y-12 scale-90'"
                       style="transition-delay: {{ $index * 120 }}ms"
                       class="flex-none flex flex-col items-center group cursor-pointer transition-all duration-1000 cubic-bezier(0.34, 1.56, 0.64, 1)">

                        {{-- Circle Image with Golden Ring --}}
                        <div class="relative h-20 w-20 md:h-32 md:w-32 rounded-full p-[4px] bg-gradient-to-br from-brand-100 to-brand-300 group-hover:from-amber-400 group-hover:to-brand-600 transition-all duration-500 shadow-sm group-hover:shadow-3xl group-hover:shadow-brand-500/40 group-hover:-rotate-6">
                            <div class="h-full w-full rounded-full overflow-hidden bg-gray-100 ring-4 ring-white shadow-inner">
                                @if($category->display_image)
                                    <img src="{{ $category->display_image }}"
                                         alt="{{ $category->name }}"
                                         class="h-full w-full object-cover group-hover:scale-125 transition-transform duration-[2000ms] opacity-90 group-hover:opacity-100">
                                @else
                                    <div class="h-full w-full flex items-center justify-center bg-gradient-to-br from-brand-50 to-amber-50">
                                        <span class="text-brand-600 text-sm font-black uppercase tracking-widest select-none">
                                            {{ substr($category->name, 0, 2) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Elegant Label --}}
                        <span class="mt-5 text-[10px] md:text-[13px] font-black uppercase tracking-[4px] text-gray-800 group-hover:text-brand-600 transition-colors text-center max-w-[120px] leading-tight drop-shadow-sm">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Featured Products Grid (Amazon Style - Direct Access) -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-12 bg-white no-scrollbar transition-all duration-1000 ease-out">
        <div class="container mx-auto px-4 lg:px-8 no-scrollbar">
            <div class="flex items-end justify-between mb-10 border-b border-gray-100 pb-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">{{ $pageContent['products_badge'] ?? 'Our Collection' }}</span>
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-onyx-900">{{ $pageContent['products_title'] ?? 'Featured Products' }}</h2>
                </div>
                <a href="{{ route('search') }}" class="hidden md:flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-brand-500 transition-colors">
                    <span>Explore All</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <div x-data="infiniteScroll()" class="space-y-12">
                <div id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @forelse($products as $product)
                        @include('public.partials.product_card', ['product' => $product])
                    @empty
                        <div class="col-span-1 sm:col-span-2 lg:col-span-4 text-center py-20 border-2 border-dashed border-gray-200 rounded-2xl">
                            <svg class="h-12 w-12 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            <h4 class="text-sm font-bold text-gray-900 tracking-wider uppercase mb-2">Products arriving soon</h4>
                            <p class="text-xs text-gray-400 font-medium">Our artisans are currently crafting new additions.</p>
                        </div>
                    @endforelse

                    {{-- Dynamic products appended here --}}
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
                            <span class="text-[10px] font-black uppercase tracking-[3px] text-gray-400 italic">Revealing more items...</span>
                        </div>
                    </template>
                </div>
            </div>
            
             <a href="{{ route('search') }}" class="md:hidden mt-8 flex items-center justify-center space-x-2 text-xs font-bold uppercase tracking-widest text-gray-500 w-full py-4 border border-gray-200 rounded-xl hover:text-brand-500 hover:border-brand-500 transition-all">
                <span>View All Collection</span>
            </a>
        </div>
    </section>

    @if($ritualKits->count() > 0)
    <!-- THE RITUAL BUNDLE SUITE -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-24 bg-brand-50/30 transition-all duration-1000 ease-out">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="mb-16 border-l-4 border-brand-500 pl-8">
                <span class="text-[10px] font-black uppercase tracking-[5px] text-brand-500 block mb-2">Curated Solutions</span>
                <h2 class="font-serif text-4xl md:text-5xl font-bold text-onyx-900 leading-tight italic">Sacred Ritual Kits</h2>
                <p class="text-gray-500 max-w-xl mt-4 leading-relaxed font-medium">Expertly assembled bundles for specific ceremonies. Every artifact you need, hand-selected for spiritual accuracy.</p>
            </div>

            <div class="flex pb-12 overflow-x-auto gap-8 no-scrollbar snap-x snap-mandatory scroll-smooth">
                @foreach($ritualKits as $kit)
                <div class="flex-none w-[320px] md:w-[450px] snap-start">
                    <div class="bg-white rounded-[40px] shadow-2xl overflow-hidden group border border-brand-100 hover:border-brand-500 transition-all duration-500">
                        <div class="relative h-[250px] md:h-[300px]">
                            <img src="{{ $kit->display_image ? Storage::url($kit->display_image) : 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2000ms]">
                            <div class="absolute inset-0 bg-gradient-to-t from-onyx-950 via-onyx-950/20 to-transparent"></div>
                            <div class="absolute bottom-6 left-8">
                                <h3 class="text-2xl text-white font-serif font-bold italic">{{ $kit->name }}</h3>
                                <p class="text-xs text-white/80 font-medium uppercase tracking-widest mt-1">{{ $kit->products->count() }} Ritual Artifacts</p>
                            </div>
                        </div>
                        <div class="p-8">
                             <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-2 italic">"{{ $kit->description }}"</p>
                             
                             <div class="flex items-center justify-between">
                                 <div>
                                     <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block">Bundle Price</span>
                                     <span class="text-2xl font-black text-onyx-900">₹{{ number_format($kit->price ?? $kit->products->sum('price'), 0) }}</span>
                                 </div>
                                 <form action="{{ route('cart.buy-kit', ['locale' => app()->getLocale()]) }}" method="POST">
                                     @csrf
                                     <input type="hidden" name="ritual_kit_id" value="{{ $kit->id }}">
                                     <button type="submit" class="px-8 py-4 bg-brand-500 text-onyx-950 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-600 transition-all shadow-md shadow-brand-500/20">Get The Kit</button>
                                 </form>
                             </div>
                             
                             <div class="mt-8 pt-6 border-t border-gray-100">
                                 <span class="text-[9px] font-black uppercase tracking-widest text-brand-500 mb-4 block">Inside this bundle</span>
                                 <div class="flex -space-x-3">
                                     @foreach($kit->products->take(5) as $kitProduct)
                                         <div class="h-10 w-10 rounded-full border-2 border-white overflow-hidden bg-gray-100 shadow-sm" title="{{ $kitProduct->product_name }}">
                                             @if($kitProduct->images->count() > 0)
                                                 <img src="{{ Storage::url($kitProduct->images->first()->image_url) }}" class="w-full h-full object-cover">
                                             @else
                                                 <div class="w-full h-full flex items-center justify-center text-[8px] font-black">{{ substr($kitProduct->product_name, 0, 1) }}</div>
                                             @endif
                                         </div>
                                     @endforeach
                                     @if($kit->products->count() > 5)
                                         <div class="h-10 w-10 rounded-full border-2 border-white bg-onyx-100 flex items-center justify-center text-onyx-900 text-[9px] font-black">+{{ $kit->products->count() - 5 }}</div>
                                     @endif
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
    <!-- Sacred Picks Section -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-24 bg-onyx-950 relative overflow-hidden no-scrollbar transition-all duration-1000 ease-out">
        <div class="absolute top-0 right-0 w-1/3 h-1/2 bg-brand-500/5 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 w-1/3 h-1/2 bg-brand-500/5 blur-[120px] rounded-full"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 no-scrollbar">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="h-[1px] w-8 bg-brand-500"></span>
                        <span class="text-[10px] font-black uppercase tracking-[5px] text-brand-500">
                            {{ $pageContent['recommendation_mode'] === 'Festive' ? 'Season\'s High Velocity' : 'Hand-Picked Heritage' }}
                        </span>
                    </div>
                    <h2 class="font-serif text-4xl md:text-5xl font-bold text-white leading-tight">
                        {{ $pageContent['recommendation_title'] ?? 'Recommended for You' }}
                    </h2>
                </div>
            </div>

            <!-- Horizontal Scrollable Grid -->
            <div class="flex pb-12 overflow-x-auto gap-8 no-scrollbar snap-x snap-mandatory scroll-smooth">
                @foreach($recommendedProducts as $product)
                    <div class="flex-none w-[280px] md:w-[320px] snap-start">
                        <div class="bg-white/5 border border-white/10 rounded-3xl p-5 group hover:bg-white/10 hover:border-brand-500/30 transition-all duration-500 transform hover:-translate-y-2">
                            <div class="relative aspect-square rounded-2xl overflow-hidden mb-6 bg-onyx-900 flex items-center justify-center">
                                @if($product->images->count() > 0)
                                    <a href="{{ route('artifact.show', $product->slug) }}" class="block w-full h-full">
                                        <img src="{{ Storage::url($product->images->first()->image_url) }}" alt="{{ $product->product_name }}" class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                                    </a>
                                @endif
                                
                                @if($product->discount_percent > 0)
                                    <div class="absolute top-4 right-4 bg-brand-500 text-onyx-900 text-[9px] font-black px-2 py-1 rounded-md shadow-lg">
                                        {{ $product->discount_percent }}% OFF
                                    </div>
                                @endif

                                <div class="absolute bottom-4 inset-x-4 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                    <button onclick="buyNow({{ $product->id }}, this)" class="w-full py-3 bg-white/10 backdrop-blur-md border border-white/20 text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-500 hover:border-brand-500 transition-all">
                                        Quick Checkout
                                    </button>
                                </div>
                            </div>

                            <div class="text-center">
                                <p class="text-[9px] font-bold text-brand-500 uppercase tracking-widest mb-2">{{ $product->category->name }}</p>
                                <h3 class="text-sm font-bold text-white mb-4 line-clamp-1 group-hover:text-brand-400 transition-colors">{{ $product->product_name }}</h3>
                                <div class="flex items-center justify-center space-x-3">
                                    <span class="text-lg font-black text-white">₹{{ number_format($product->price, 0) }}</span>
                                    @if($product->mrp > $product->price)
                                        <span class="text-[11px] text-gray-500 line-through">₹{{ number_format($product->mrp, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- THE SACRED PARTNERSHIP: POOJARI & MARRIAGE LOGISTICS -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-24 bg-white transition-all duration-1000 ease-out">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Visual Story -->
                <div class="relative group h-[500px] md:h-[600px] rounded-[40px] overflow-hidden shadow-2xl">
                    <img src="/storage/poojari_booking_banner_1774718250281.png" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[2000ms]">
                    <div class="absolute inset-0 bg-gradient-to-t from-onyx-950 via-onyx-950/20 to-transparent"></div>
                    <div class="absolute bottom-10 left-10 right-10">
                        <span class="px-4 py-2 bg-amber-500 text-onyx-950 text-[10px] font-black uppercase tracking-widest rounded-lg mb-4 inline-block">Vedic Excellence</span>
                        <h3 class="text-4xl text-white font-serif font-bold mb-4">The Vedic Poojari Hub</h3>
                        <p class="text-gray-200 text-sm leading-relaxed max-w-sm">Authentic Vedic Priests for Marriages, Griha-Pravesh, and Sacred Pujas. Book a verified Acharya for your auspicious day.</p>
                    </div>
                </div>

                <!-- Features & CTA -->
                <div class="space-y-12 pl-0 lg:pl-12">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[5px] text-brand-500 block mb-4">Complete Marriage Supply</span>
                        <h2 class="text-4xl md:text-5xl font-serif font-bold text-onyx-900 mb-6 italic leading-tight">Every Ritual, Every Need, Fully Supplied.</h2>
                        <p class="text-gray-500 text-lg leading-relaxed">From the first engagement Diya to the final marriage Mantapam items—we are the largest wholesale source for bridal and ritual needs in the country.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="p-8 bg-gray-50 rounded-3xl border border-gray-100 hover:border-amber-400 transition-all group">
                            <div class="h-12 w-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-amber-500 mb-6 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <h4 class="text-sm font-black uppercase tracking-widest text-onyx-900 mb-2">Book a Priest</h4>
                            <p class="text-xs text-gray-400 leading-relaxed italic">Verified qualified priests with deep ritual knowledge.</p>
                            <a href="#" class="mt-4 inline-block text-[10px] font-black uppercase tracking-widest text-brand-500 hover:tracking-[3px] transition-all">Check Timings →</a>
                        </div>

                        <div class="p-8 bg-gray-50 rounded-3xl border border-gray-100 hover:border-amber-400 transition-all group">
                            <div class="h-12 w-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-amber-500 mb-6 group-hover:bg-amber-500 group-hover:text-white transition-all">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <h4 class="text-sm font-black uppercase tracking-widest text-onyx-900 mb-2">Bulk Logistics</h4>
                            <p class="text-xs text-gray-400 leading-relaxed italic">Marriage kits, Brassware, and Pasupu-Kunkuma in bulk.</p>
                            <a href="#" class="mt-4 inline-block text-[10px] font-black uppercase tracking-widest text-brand-500 hover:tracking-[3px] transition-all">View Packs →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIVAHA CONCIERGE -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-24 bg-brand-50 transition-all duration-1000 ease-out">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex flex-col lg:flex-row-reverse items-center bg-white rounded-[40px] shadow-2xl overflow-hidden">
                <div class="lg:w-1/2 h-[400px] lg:h-[600px] relative shrink-0">
                     <img src="/storage/vivaha_concierge_consultation_1774719357715.png" class="w-full h-full object-cover">
                     <div class="absolute inset-0 bg-brand-900/10"></div>
                </div>
                <div class="lg:w-1/2 p-12 lg:p-20">
                     <span class="text-[10px] font-black uppercase tracking-[6px] text-brand-500 block mb-6">Expert Guidance</span>
                     <h2 class="text-4xl lg:text-5xl font-serif font-bold text-onyx-900 mb-8 italic leading-tight">Vivaha Concierge</h2>
                     <p class="text-lg text-gray-500 mb-10 leading-relaxed">Planning a grand ritual? Our spiritual consultants provide personalized guidance on Vastu, ritual brassware selection, and complete marriage logistics to ensure divine perfection.</p>
                     
                     <div class="space-y-6">
                         <div class="flex items-start space-x-4">
                             <div class="h-6 w-6 text-brand-500 mt-1"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" /></svg></div>
                             <p class="text-sm font-bold text-onyx-900 uppercase tracking-widest leading-none pt-1">Personalized Ritual Planning</p>
                         </div>
                         <div class="flex items-start space-x-4">
                             <div class="h-6 w-6 text-brand-500 mt-1"><svg fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" /></svg></div>
                             <p class="text-sm font-bold text-onyx-900 uppercase tracking-widest leading-none pt-1">Vastu-Aligned Artifact Selection</p>
                         </div>
                     </div>
                     
                     <button class="mt-12 px-10 py-5 bg-onyx-900 text-white text-[11px] font-black uppercase tracking-[4px] rounded-2xl hover:bg-brand-600 transition-all shadow-xl shadow-onyx-900/20">Book a Consultation</button>
                </div>
            </div>
        </div>
    </section>

    <!-- CORPORATE GIFTING EXCELLENCE -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-24 bg-onyx-950 text-white transition-all duration-1000 ease-out relative overflow-hidden">
        <div class="absolute inset-0 z-0">
             <img src="/storage/corporate_gifting_showcase_1774718273819.png" class="w-full h-full object-cover opacity-20">
             <div class="absolute inset-0 bg-gradient-to-r from-onyx-950 via-onyx-950/80 to-transparent"></div>
        </div>
        
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="max-w-3xl">
                <span class="text-[10px] font-black uppercase tracking-[6px] text-amber-400 block mb-6">B2B Mastery</span>
                <h2 class="text-5xl md:text-7xl font-serif font-bold italic mb-8 drop-shadow-lg leading-tight">Corporate Gifting &<br>Institutional Supply.</h2>
                <p class="text-xl text-gray-400 mb-12 leading-relaxed">Impress your clients and stakeholders with the elegance of pure brass. Custom branding, premium velvet packaging, and global multi-unit shipping available.</p>
                
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-8">
                     <button class="px-12 py-5 bg-white text-onyx-950 text-[11px] font-black uppercase tracking-[4px] rounded-2xl hover:bg-amber-500 transition-all">Request Corporate Catalog</button>
                     <div class="flex items-center space-x-4">
                         <div class="h-10 w-10 border border-white/20 rounded-full flex items-center justify-center">
                             <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7zm10 5a5 5 0 1 0 0-10 5 5 0 0 0 0 10z"/></svg>
                         </div>
                         <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Trusted by 500+ Corporations</span>
                     </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features / Value Prop -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-20 bg-white no-scrollbar transition-all duration-1000 ease-out">
        <div class="container mx-auto px-4 lg:px-8 no-scrollbar">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center group">
                    <div class="h-16 w-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-6 text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all transform group-hover:-translate-y-2 duration-300 shadow-sm">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl text-onyx-900 mb-3">{{ $pageContent['feature_1_title'] ?? 'Authentic Craftsmanship' }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed px-4">{{ $pageContent['feature_1_description'] ?? 'Every item is completely hand-forged by generational artisans using traditional techniques.' }}</p>
                </div>
                
                <div class="text-center group">
                    <div class="h-16 w-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-6 text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all transform group-hover:-translate-y-2 duration-300 shadow-sm">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl text-onyx-900 mb-3">{{ $pageContent['feature_2_title'] ?? 'Global Shipping' }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed px-4">{{ $pageContent['feature_2_description'] ?? 'Securely packaged and exported worldwide. We ensure products reach your door safely.' }}</p>
                </div>

                <div class="text-center group">
                    <div class="h-16 w-16 mx-auto bg-brand-50 rounded-2xl flex items-center justify-center mb-6 text-brand-500 group-hover:bg-brand-500 group-hover:text-white transition-all transform group-hover:-translate-y-2 duration-300 shadow-sm">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="font-serif font-bold text-xl text-onyx-900 mb-3">{{ $pageContent['feature_3_title'] ?? 'B2B & Wholesale Dropshipping' }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed px-4">{{ $pageContent['feature_3_description'] ?? 'Exclusive partner portals featuring automated restocks and zero-inventory fulfillment systems.' }}</p>
                </div>
            </div>
        </div>
    </section>

    @if(isset($pageContent['social_proof_enabled']) && $pageContent['social_proof_enabled'] == '1')
    <!-- Social Proof Marquee -->
    <div class="bg-gray-50 py-12 border-y border-gray-100 overflow-hidden group no-scrollbar">
        <div class="container mx-auto px-4 mb-8 text-center no-scrollbar">
            <span class="text-[9px] font-black uppercase tracking-[3px] text-gray-400">Trusted Worldwide By Customers & Corporate Partners</span>
        </div>
        <div class="relative flex overflow-x-hidden no-scrollbar">
            <div class="flex animate-marquee-fast pause-on-hover items-center">
                @for($i=1; $i<=5; $i++)
                    @php $logoKey = 'trust_logo_' . $i; @endphp
                    @if(isset($pageContent[$logoKey]))
                        <img src="{{ $pageContent[$logoKey] }}" class="h-12 md:h-16 mx-12 grayscale opacity-30 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                    @endif
                @endfor
            </div>
            <div class="absolute top-0 flex animate-marquee-fast2 pause-on-hover items-center">
                @for($i=1; $i<=5; $i++)
                    @php $logoKey = 'trust_logo_' . $i; @endphp
                    @if(isset($pageContent[$logoKey]))
                        <img src="{{ $pageContent[$logoKey] }}" class="h-12 md:h-16 mx-12 grayscale opacity-30 hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                    @endif
                @endfor
            </div>
        </div>
    </div>
    @endif

    <!-- Gallery Section -->
    <section x-data="{ visible: false }" x-intersect.once="visible = true"
             :class="visible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'"
             class="py-24 bg-white overflow-hidden no-scrollbar transition-all duration-1000 ease-out">
        <div class="container mx-auto px-4 lg:px-8 mb-16 text-center">
             <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">Vivaha & Mangalam</span>
             <h2 class="font-serif text-3xl md:text-5xl font-bold text-onyx-900">{{ $pageContent['gallery_title'] ?? 'The Wedding & Marriage Suite' }}</h2>
        </div>

        <div class="relative flex overflow-x-hidden group no-scrollbar">
            <div class="py-4 animate-marquee-fast flex whitespace-nowrap pause-on-hover px-4 no-scrollbar">
                @for($i=1; $i<=6; $i++)
                    @php 
                        $imgKey = 'gallery_image_' . $i; 
                        $fallbacks = [
                            1 => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=2670&auto=format&fit=crop',
                            2 => 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop',
                            3 => 'https://images.unsplash.com/photo-1603412470732-bc66033866b1?q=80&w=2670&auto=format&fit=crop',
                            4 => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=2670&auto=format&fit=crop',
                            5 => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=2670&auto=format&fit=crop',
                            6 => 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop',
                        ];
                    @endphp
                    <div class="mx-4 w-[300px] md:w-[450px] h-[300px] md:h-[400px] rounded-2xl overflow-hidden shadow-2xl transition-all duration-500 hover:scale-105 border border-gray-100">
                        <img src="{{ !empty($pageContent[$imgKey]) ? $pageContent[$imgKey] : $fallbacks[$i] }}" class="w-full h-full object-cover">
                    </div>
                @endfor
            </div>

            <div class="absolute top-0 py-4 animate-marquee-fast2 flex whitespace-nowrap pause-on-hover no-scrollbar">
                @for($i=1; $i<=6; $i++)
                    @php 
                        $imgKey = 'gallery_image_' . $i; 
                        $fallbacks = [
                            1 => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=2670&auto=format&fit=crop',
                            2 => 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop',
                            3 => 'https://images.unsplash.com/photo-1603412470732-bc66033866b1?q=80&w=2670&auto=format&fit=crop',
                            4 => 'https://images.unsplash.com/photo-1600093463592-8e36ae95ef56?q=80&w=2670&auto=format&fit=crop',
                            5 => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?q=80&w=2670&auto=format&fit=crop',
                            6 => 'https://images.unsplash.com/photo-1590739225287-bd20498ded45?q=80&w=2670&auto=format&fit=crop',
                        ];
                    @endphp
                    <div class="mx-4 w-[300px] md:w-[450px] h-[300px] md:h-[400px] rounded-2xl overflow-hidden shadow-2xl transition-all duration-500 hover:scale-105 border border-gray-100">
                        <img src="{{ !empty($pageContent[$imgKey]) ? $pageContent[$imgKey] : $fallbacks[$i] }}" class="w-full h-full object-cover">
                    </div>
                @endfor
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
function infiniteScroll() {
    return {
        dynamicProducts: [],
        page: 1,
        hasMore: {{ $products->hasMorePages() ? 'true' : 'false' }},
        loading: false,
        async loadMore() {
            if (this.loading || !this.hasMore) return;
            this.loading = true;
            this.page++;
            
            const gqlQuery = `
                query FetchProducts($page: Int) {
                    searchProducts(page: $page, first: 12) {
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
                        variables: { page: this.page }
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

async function buyNow(productId, btn) {
    if (btn.disabled) return;

    btn.disabled = true;
    const textEl    = btn.querySelector('.btn-text');
    const spinnerEl = btn.querySelector('.btn-spinner');
    if (textEl)    textEl.textContent = 'Adding...';
    if (spinnerEl) spinnerEl.classList.remove('hidden');

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
            window.dispatchEvent(new CustomEvent('notify', {
                detail: { message: data.error, type: 'error' }
            }));
            btn.disabled = false;
            if (textEl)    textEl.textContent = 'Buy Now';
            if (spinnerEl) spinnerEl.classList.add('hidden');
            if (window.BcLoader) { BcLoader.bar.style.opacity = '0'; }
            return;
        }

        window.dispatchEvent(new CustomEvent('cart-updated', {
            detail: { count: data.cart_count ?? data.count }
        }));

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

