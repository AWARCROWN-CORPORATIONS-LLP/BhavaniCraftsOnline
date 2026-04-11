<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO & Social OpenGraph: Enhanced Artifact Visibility -->
    <meta name="title" content="@yield('meta_title', 'Bhavani Crafts | Exquisite Handmade Artifacts')">
    <meta name="description" content="@yield('meta_description', 'Discover exquisite handmade pooja articles, brass idols, and corporate gifts, forging heritage and devotion into premium products for modern homes.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('meta_title', 'Bhavani Crafts | Exquisite Handmade Artifacts')">
    <meta property="og:description" content="@yield('meta_description', 'Discover exquisite handmade pooja articles, brass idols, and corporate gifts.')">
    <meta property="og:image" content="@yield('meta_image', asset('favicon.png'))">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('meta_title', 'Bhavani Crafts | Exquisite Handmade Artifacts')">
    <meta property="twitter:description" content="@yield('meta_description', 'Discover exquisite handmade pooja articles, brass idols, and corporate gifts.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('favicon.png'))">

    <title>@yield('meta_title', config('app.name', 'Bhavani Crafts | Premium Products'))</title>
    
    <!-- High-Fidelity Professional Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, h4, .font-serif { font-family: 'Cormorant Garamond', serif !important; }
    </style>
    
    <!-- SEO: Multi-Locale Support -->
    <link rel="alternate" hreflang="en-in" href="{{ url('/en-in' . Request::getPathInfo()) }}" />
    <link rel="alternate" hreflang="en-us" href="{{ url('/en-us' . Request::getPathInfo()) }}" />
    <link rel="alternate" hreflang="en-gb" href="{{ url('/en-gb' . Request::getPathInfo()) }}" />
    <link rel="alternate" hreflang="x-default" href="{{ url('/en-in' . Request::getPathInfo()) }}" />

    <!-- Google Model Viewer (3D/AR Support) -->
    <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
    <script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>

    <style>
        html { scroll-behavior: smooth; }
        * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { display: none; }
        
        @keyframes tickerLoop {
            0% { transform: translateY(0); }
            20% { transform: translateY(0); }
            25% { transform: translateY(-100%); }
            45% { transform: translateY(-100%); }
            50% { transform: translateY(-200%); }
            70% { transform: translateY(-200%); }
            75% { transform: translateY(-300%); }
            95% { transform: translateY(-300%); }
            100% { transform: translateY(-400%); }
        }
        .animate-tickerLoop {
            animation: tickerLoop 15s infinite;
        }
    </style>

    <!-- Tailwind CSS (via CDN for immediate dev flexibility, ideally compiled in production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['"Cormorant Garamond"', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fef2f2',
                            100: '#fee2e2',
                            200: '#fecaca',
                            300: '#fca5a5',
                            400: '#f87171',
                            500: '#c62828', 
                            600: '#b71c1c',
                            700: '#991b1b',
                            800: '#7f1d1d',
                            900: '#450a0a',
                            950: '#2d0606',
                        },
                        amber: {
                            400: '#d4af37', // Imperial Gold
                            500: '#c5a021', // Primary Brass
                            600: '#a6841c',
                        },
                        onyx: {
                            950: '#0f0d0c',
                            900: '#1a1614', // Deep Warm Charcoal
                            800: '#26211e', 
                            DEFAULT: '#1a1614',
                        }
                    }
                }
            }
        }
    </script>
    
    @livewireStyles
    
    <script>
        window.AppLocale = "{{ app()->getLocale() }}";
        window.AppCurrency = {
            symbol: "{{ config('app.currency_symbol', '₹') }}",
            rate: {{ (float)config('app.currency_rate', 1) }}
        };
    </script>
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('appState', () => ({
            cartOpen: false,
            mobileMenuOpen: false,
            searchOpen: false,
            activeTab: 'cart',
            cartItems: [],
            wishlistItems: [],
            loading: false,
            cartCount: 0,
            wishlistCount: 0,
            corporateModalOpen: false,

            async fetchCart() {
                this.loading = true;
                try {
                    const res = await fetch('{{ route("cart.index") }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    this.cartItems = data.items;
                    this.cartCount = data.count;
                } catch (e) {
                    console.error('Cart fetch failed', e);
                } finally {
                    this.loading = false;
                }
            },

            async fetchWishlist() {
                this.loading = true;
                try {
                    const res = await fetch('{{ route("wishlist.index") }}', {
                        headers: { 'Accept': 'application/json' }
                    });
                    const data = await res.json();
                    this.wishlistItems = data.items;
                    this.wishlistCount = data.count;
                } catch (e) {
                    console.error('Wishlist fetch failed', e);
                } finally {
                    this.loading = false;
                }
            },
            
            async updateCart(itemId, qty) {
                if(qty < 1) return;
                this.loading = true;
                try {
                    const res = await fetch('{{ route("cart.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ cart_item_id: itemId, quantity: qty })
                    });
                    const data = await res.json();
                    this.cartItems = data.items;
                    this.cartCount = data.count;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                } catch (e) {
                    console.error('Cart update failed', e);
                } finally {
                    this.loading = false;
                }
            },

            async removeFromCart(itemId) {
                this.loading = true;
                try {
                    const res = await fetch('{{ route("cart.remove") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ cart_item_id: itemId })
                    });
                    const data = await res.json();
                    this.cartItems = data.items;
                    this.cartCount = data.count;
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.count } }));
                } catch (e) {
                    console.error('Cart remove failed', e);
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
    </script>
</head>
<body class="bg-white text-onyx-950 flex flex-col min-h-screen antialiased overflow-x-hidden" 
      x-data="appState"
      @cart-updated.window="cartCount = $event.detail.count; cartOpen = true; activeTab = 'cart'; fetchCart()"
      @wishlist-updated.window="wishlistCount = $event.detail.count; cartOpen = true; activeTab = 'wishlist'; fetchWishlist()"
      @notify.window="console.log('Notification:', $event.detail)">

    <!-- Global Page Loader Overlay -->
    <div id="bc-main-loader" class="fixed inset-0 z-[200] bg-white flex items-center justify-center transition-opacity duration-700 pointer-events-none opacity-0">
        <div class="flex flex-col items-center">
            <div class="h-20 w-20 relative">
                <div class="absolute inset-0 border-4 border-brand-50 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-t-brand-500 rounded-full animate-spin"></div>
            </div>
            <p class="mt-6 text-[10px] font-black uppercase tracking-[4px] text-onyx-900 animate-pulse">Bhavani Crafts</p>
        </div>
    </div>

    <script>
        window.BcLoader = {
            get mainLoader() { return document.getElementById('bc-main-loader'); },
            get progressBar() { return document.getElementById('bc-progress-bar'); },
            show(text = '') {
                const loader = this.mainLoader;
                const bar = this.progressBar;
                if(loader) {
                    const p = loader.querySelector('p');
                    if(p && text) p.innerText = text;
                    loader.classList.remove('pointer-events-none', 'opacity-0');
                    loader.classList.add('opacity-100');
                }
                if(bar) {
                    bar.style.width = '40%';
                    bar.style.opacity = '1';
                }
            },
            hide() {
                const loader = this.mainLoader;
                const bar = this.progressBar;
                if(loader) {
                    loader.classList.add('pointer-events-none', 'opacity-0');
                    loader.classList.remove('opacity-100');
                }
                if(bar) {
                    bar.style.width = '100%';
                    setTimeout(() => {
                        const currentBar = this.progressBar;
                        if(currentBar) {
                            currentBar.style.opacity = '0';
                            setTimeout(() => { 
                                const finalBar = this.progressBar;
                                if(finalBar) finalBar.style.width = '0%'; 
                            }, 300);
                        }
                    }, 500);
                }
            }
        };

        // Turbo Interceptors
        document.addEventListener('turbo:visit', () => BcLoader.show('Loading content...'));
        document.addEventListener('turbo:load', () => BcLoader.hide());
        document.addEventListener('turbo:submit-start', () => BcLoader.show('Processing request...'));
        
        window.addEventListener('load', () => BcLoader.hide());
    </script>

    <!-- Dynamic Announcement Heading Bar -->
    <div class="bg-brand-50 text-brand-900 py-1.5 px-4 lg:px-8 border-b border-brand-100 relative z-[110]">
        <div class="container mx-auto flex items-center justify-between">
            <!-- Left side: Google Translate (Higher priority to show) -->
            <div class="flex items-center shrink-0 border-r border-brand-100 pr-4 mr-4">
                <div id="google_translate_element" class="scale-[0.8] origin-left"></div>
            </div>

            <!-- Center: Dynamic Ticker (Flexible area) -->
            <div class="flex items-center flex-1 min-w-0 overflow-hidden">
                <div class="flex items-center space-x-2 mr-3 shrink-0">
                    <span class="flex h-1 w-1 rounded-full bg-brand-500 animate-pulse"></span>
                    <span class="text-[8px] font-black uppercase tracking-[2px] text-brand-600">Special</span>
                </div>
                <div class="flex-1 overflow-hidden h-4 relative">
                    <div class="flex flex-col animate-tickerLoop">
                        @forelse($announcements as $msg)
                            <span class="text-[9px] font-bold uppercase tracking-[1px] text-brand-800 h-4 leading-4 flex items-center truncate">
                                {{ $msg }}
                            </span>
                        @empty
                            <span class="text-[9px] font-bold uppercase tracking-[1px] text-brand-800 h-4 leading-4 flex items-center truncate">
                                Authentic Sacred Crafts & Ritual Essentials
                            </span>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right: Quick Contact & Essentials -->
            <div class="hidden md:flex items-center space-x-8 shrink-0">
                <a href="https://wa.me/{{ $footerSettings['contact_whatsapp_number'] ?? '#' }}" target="_blank" class="flex items-center space-x-2 group">
                    <svg class="h-3 w-3 text-brand-500 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.631 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <span class="text-[10px] font-bold tracking-widest text-brand-700 hover:text-brand-500 transition-colors uppercase">Support: {{ $footerSettings['contact_whatsapp_number'] ?? 'whatsapp' }}</span>
                </a>
                <a href="{{ route('franchise.register') }}" class="px-3 py-1 bg-brand-500 rounded text-[9px] font-black uppercase tracking-widest hover:bg-brand-600 transition-all text-white">Apply Franchise</a>
            </div>
        </div>
    </div>

    <!-- Top Navigation -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-[100] border-b border-gray-100 transition-all duration-300" 
            x-data="{ scrolled: false }" 
            @scroll.window="scrolled = (window.pageYOffset > 20)"
            :class="scrolled ? 'py-1 shadow-lg shadow-gray-200/20' : 'py-0'">
        
        <!-- Premium Progress Bar (BC Loader) -->
        <div id="bc-progress-bar" class="absolute bottom-0 left-0 h-[2px] bg-gradient-to-r from-brand-600 to-brand-400 transition-all duration-300 ease-out" style="width: 0%; opacity: 0;"></div>

        <nav class="container mx-auto px-4 lg:px-8 py-4 lg:py-5 flex items-center justify-between">
            
            <!-- Left: Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 -ml-2 text-gray-800 hover:text-brand-500 transition-colors focus:outline-none">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center shrink-0 group">
                <div class="h-16 w-auto group-hover:scale-105 transition-all duration-300">
                    <img src="{{ $siteLogo }}" alt="Bhavani Crafts" class="h-full w-auto object-contain">
                </div>
            </a>

            <!-- Central Search Bar (Desktop - Live GraphQL Search) -->
            <div x-data="liveSearch" @click.away="results = []" class="hidden lg:flex flex-1 max-w-2xl mx-12 h-12 relative group rounded-full border border-gray-200 bg-gray-50/50 hover:bg-white hover:border-brand-200 transition-all duration-300 focus-within:ring-2 focus-within:ring-brand-500/20 focus-within:bg-white focus-within:border-brand-400">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                
                <form action="{{ route('search') }}" method="GET" class="w-full h-full flex items-center xl:pl-44" 
                      @submit="if(!query.trim() && !selectedCategory) { $event.preventDefault(); return false; }">
                    <!-- Category dropdown inside the form -->
                    <select name="category" x-model="selectedCategory" class="absolute inset-y-0 left-12 w-32 bg-transparent text-xs font-semibold text-gray-500 border-none outline-none focus:ring-0 cursor-pointer hidden xl:flex items-center">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::all() as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-2 left-44 w-px bg-gray-200 hidden xl:block"></div>
                    
                    <input type="text" name="q" 
                           x-model="query"
                           @input.debounce.300ms="performSearch"
                           autocomplete="off"
                           placeholder="Search for brass idols, products..." 
                           class="w-full h-full pl-12 xl:pl-4 pr-16 bg-transparent text-sm font-medium text-gray-900 border-none outline-none focus:ring-0 placeholder-gray-400">
                    
                    <button type="submit" class="absolute inset-y-1.5 right-1.5 px-4 bg-brand-600 hover:bg-brand-500 text-white text-[11px] font-bold uppercase tracking-wider rounded-full transition-all duration-300 shadow-sm">
                        Search
                    </button>
                </form>

                <!-- Live Results Dropdown -->
                <div x-show="results.length > 0 || loading"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute top-14 left-0 w-full bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden py-2" style="display: none;">

                    <!-- Loading State -->
                    <div x-show="loading" class="px-4 py-6 flex items-center justify-center space-x-3 text-gray-400">
                        <svg class="animate-spin h-4 w-4 text-brand-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span class="text-xs font-semibold">Searching...</span>
                    </div>

                    <!-- Results -->
                    <div x-show="!loading && results.length > 0">
                        <div class="px-4 py-2 border-b border-gray-50 flex items-center justify-between">
                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">Instant Results</span>
                            <span class="text-[9px] font-bold text-brand-500" x-text="results.length + ' found'"></span>
                        </div>

                        <div class="max-h-[400px] overflow-y-auto no-scrollbar">
                            <template x-for="product in results" :key="product.id">
                                <a :href="'/' + window.AppLocale + '/artifact/' + product.slug" class="flex items-center space-x-4 p-4 hover:bg-brand-50 transition-all border-b border-gray-50 last:border-0 group">
                                    <div class="h-12 w-12 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                        <template x-if="product.image_url">
                                            <img :src="product.image_url" :alt="product.product_name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        </template>
                                        <template x-if="!product.image_url">
                                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                                <svg class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs font-bold text-onyx-900 group-hover:text-brand-600 transition-colors truncate" x-text="product.product_name"></h4>
                                        <div class="flex items-center space-x-2 mt-0.5">
                                            <span class="text-[10px] text-gray-400 font-bold" x-text="product.category ? product.category.name : ''"></span>
                                            <span class="h-1 w-1 bg-gray-200 rounded-full"></span>
                                            <span class="text-[11px] text-onyx-900 font-black" x-text="'₹' + Number(product.price).toLocaleString()"></span>
                                        </div>
                                    </div>
                                    <svg class="h-4 w-4 text-gray-300 group-hover:text-brand-500 transform group-hover:translate-x-1 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </template>
                        </div>

                        <div class="p-3 bg-gray-50 text-center border-t border-gray-100">
                            <a :href="'{{ route('search') }}?q=' + query" class="text-[9px] font-black uppercase tracking-[2px] text-gray-500 hover:text-brand-500 transition-colors">
                                View All Results →
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Actions (Cart, Wishlist, Auth) -->
            <div class="flex items-center space-x-1 sm:space-x-4 shrink-0">
                
                <!-- Mobile Search Toggle -->
                <button @click="searchOpen = !searchOpen" class="lg:hidden p-2 text-gray-600 hover:text-brand-500 transition-colors rounded-full hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>

                <!-- Wishlist Icon -->
                <button @click="cartOpen = true; activeTab = 'wishlist'; fetchWishlist()" class="relative p-2 text-gray-600 hover:text-brand-500 transition-colors rounded-full hover:bg-gray-50 group hidden sm:block">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span x-show="wishlistCount > 0" 
                          class="absolute top-0 right-0 h-4 w-4 bg-brand-500 text-white flex items-center justify-center text-[9px] font-bold rounded-full transform translate-x-1/2 -translate-y-1/4 border-2 border-white"
                          x-text="wishlistCount">
                    </span>
                </button>

                <!-- Cart Icon -->
                <button @click="cartOpen = true; activeTab = 'cart'; fetchCart()" class="relative p-2 text-gray-600 hover:text-brand-500 transition-colors rounded-full hover:bg-gray-50 flex items-center space-x-2 group">
                    <div class="relative">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span class="absolute top-0 right-0 h-4 w-4 bg-brand-600 group-hover:bg-brand-500 text-white flex items-center justify-center text-[9px] font-bold rounded-full transform translate-x-1/3 -translate-y-1/3 border-2 border-white transition-colors"
                              x-text="cartCount">
                        </span>
                    </div>
                </button>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-200 mx-2 hidden lg:block"></div>

                <!-- Auth Buttons -->
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('poojari.index') }}" class="text-[11px] font-black uppercase tracking-[2px] text-onyx-900 border-b-2 {{ Request::is('*/poojari*') ? 'border-brand-500' : 'border-transparent' }} hover:text-brand-500 transition-all">Ritual Services</a>
                    @auth
                        <a href="{{ route('customer.dashboard') }}" class="text-xs font-bold text-gray-700 hover:text-brand-500 uppercase tracking-wider transition-colors">My Profile</a>
                    @else
                        <a href="{{ route('login') }}" class="text-[11px] font-bold text-gray-700 hover:text-brand-500 uppercase tracking-widest transition-colors">Sign In</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-brand-600 hover:bg-brand-500 text-white text-[11px] font-bold uppercase tracking-wider rounded-xl transition-all duration-300 shadow-md shadow-brand-900/10 hover:shadow-brand-500/30">
                            Create Account
                        </a>
                    @endauth
                </div>
                
                <!-- Mobile Auth Initial (Icon) -->
                <a href="{{ route('login') }}" class="lg:hidden p-2 text-gray-600 hover:text-brand-500 transition-colors rounded-full hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </a>
            </div>
        </nav>

        <!-- Mobile Search Dropdown -->
        <div x-show="searchOpen" x-collapse class="lg:hidden border-t border-gray-100 bg-white" style="display: none;">
            <div class="container mx-auto px-4 py-3">
                <form action="{{ route('search') }}" method="GET" class="relative" 
                      onsubmit="if(!this.q.value.trim()){ event.preventDefault(); return false; }">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search products..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
                    <button type="submit" class="absolute inset-y-0 right-0 px-4 text-gray-400 hover:text-brand-500">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Mobile Menu (Full screen overlay) -->
        <div x-show="mobileMenuOpen" x-transition.opacity class="fixed inset-0 z-50 bg-onyx-900/95 backdrop-blur-sm lg:hidden pt-20 flex flex-col items-center pb-8 overflow-y-auto" style="display: none;">
            <button @click="mobileMenuOpen = false" class="absolute top-6 right-6 p-2 text-white/50 hover:text-white rounded-full bg-white/5 hover:bg-white/10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            
            <nav class="flex flex-col items-center space-y-6 text-center w-full px-8 flex-1 mt-10">
                <a href="{{ route('home') }}" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Home</a>
                <a href="{{ route('poojari.index') }}" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Ritual Services</a>
                <a href="#" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Categories</a>
                <a href="{{ route('franchise.register') }}" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Apply Franchise</a>
                <a href="#" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Our Heritage</a>
                <a href="#" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Contact</a>
            </nav>

            <div class="mt-auto w-full px-8 space-y-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block w-full py-4 bg-brand-500 hover:bg-brand-600 text-white text-center font-bold uppercase tracking-widest text-sm rounded-xl transition-colors shadow-lg shadow-brand-500/30">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block w-full py-4 bg-white/10 hover:bg-white/20 text-white text-center font-bold uppercase tracking-widest text-sm rounded-xl transition-colors border border-white/10 hover:border-white/30">Sign In</a>
                    <a href="{{ route('register') }}" class="block w-full py-4 bg-brand-500 hover:bg-brand-600 text-white text-center font-bold uppercase tracking-widest text-sm rounded-xl transition-colors shadow-lg shadow-brand-500/30">Create Account</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 wrapper flex flex-col w-full relative z-10">

        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer class="bg-onyx-900 text-white pt-20 pb-10 border-t-4 border-brand-500 mt-20 relative overflow-hidden">
        <!-- Subtle background pattern or blur -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">
                <!-- Branding & Philosophy -->
                <div class="col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center shrink-0 group mb-6">
                        <div class="h-10 w-auto group-hover:scale-105 transition-all duration-300">
                            <img src="{{ $siteLogo }}" alt="Bhavani Crafts" class="h-full w-auto object-contain brightness-0 invert opacity-80">
                        </div>
                    </a>
                    <p class="text-xs text-gray-400 font-light leading-relaxed mb-6 max-w-xs">
                        {{ $footerSettings['footer_description'] ?? 'Exquisite handmade pooja articles, brass idols, and corporate gifts, forging heritage and devotion into premium products for modern homes.' }}
                    </p>
                </div>
                
                <!-- Experience Centers (Branches) -->
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 mb-6 underline decoration-brand-500/30 underline-offset-8">Our Branches</h4>
                    <div class="space-y-6">
                        @forelse($footerBranches as $branch)
                            <div>
                                <p class="text-[11px] font-black text-white uppercase tracking-wider">{{ $branch->name }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 leading-relaxed">{{ $branch->address }}</p>
                                @if($branch->phone)
                                    <p class="text-[10px] text-brand-600 font-bold mt-1 tracking-tighter">{{ $branch->phone }}</p>
                                @endif
                                @if($branch->map_link)
                                    <a href="{{ $branch->map_link }}" target="_blank" class="text-[9px] font-black text-gray-500 hover:text-white uppercase tracking-widest mt-2 flex items-center group transition-colors">
                                        Locate on Maps 
                                        <svg class="h-2 w-2 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic">Connect with us online.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Strategic Links -->
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 mb-6 underline decoration-brand-500/30 underline-offset-8">Information</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('pages.sacred-kit') }}" class="text-[11px] font-black text-brand-600 hover:text-white transition-colors uppercase tracking-widest">Sacred Kit Builder</a></li>
                        <li><a href="{{ route('pages.faq') }}" class="text-[11px] text-gray-400 hover:text-white transition-colors font-medium">Sacred FAQ</a></li>
                        <li><a href="{{ route('pages.shipping') }}" class="text-[11px] text-gray-400 hover:text-white transition-colors font-medium">Shipping & Returns</a></li>
                        <li><a href="{{ route('pages.privacy') }}" class="text-[11px] text-gray-400 hover:text-white transition-colors font-medium">Privacy Policy</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="text-[11px] text-gray-400 hover:text-white transition-colors font-medium">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Newsletter & Connectivity -->
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 mb-6 underline decoration-brand-500/30 underline-offset-8">Sacred Digest</h4>
                    <p class="text-[10px] text-gray-400 leading-relaxed mb-4">Join our inner circle for early access to artisan limited releases.</p>
                    <form action="#" class="relative mb-6" data-no-loader>
                        <input type="email" placeholder="Email Address..." class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-4 pr-12 text-[10px] text-white focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all">
                        <button type="submit" class="absolute inset-y-1.5 right-1.5 px-3 bg-brand-500 hover:bg-brand-400 text-white rounded-lg transition-colors flex items-center justify-center">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </form>
                    
                    <div class="flex items-center space-x-6">
                        @if(isset($footerSettings['footer_instagram']))
                            <a href="{{ $footerSettings['footer_instagram'] }}" class="text-gray-500 hover:text-brand-500 transition-all hover:scale-110"><span class="text-[10px] font-black uppercase tracking-widest">IG</span></a>
                        @endif
                        @if(isset($footerSettings['footer_facebook']))
                            <a href="{{ $footerSettings['footer_facebook'] }}" class="text-gray-500 hover:text-brand-500 transition-all hover:scale-110"><span class="text-[10px] font-black uppercase tracking-widest">FB</span></a>
                        @endif
                        @if(isset($footerSettings['footer_pinterest']))
                            <a href="{{ $footerSettings['footer_pinterest'] }}" class="text-gray-500 hover:text-brand-500 transition-all hover:scale-110"><span class="text-[10px] font-black uppercase tracking-widest">PN</span></a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between">
                <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-4 md:mb-0">
                    &copy; {{ date('Y') }} {{ $footerSettings['footer_copyright'] ?? 'Bhavani Crafts. All Rights Reserved.' }}
                </p>
                <div class="flex items-center space-x-4 opacity-40">
                    <div class="h-4 w-auto grayscale"><img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Stripe_Logo%2C_revised_2016.svg" class="h-full"></div>
                    <div class="h-4 w-auto grayscale"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-full"></div>
                    <div class="h-3 w-auto grayscale"><img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-full"></div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Page-specific scripts pushed from child views --}}
    @stack('scripts')

    {{-- Global helper functions used by product_card partial --}}
    <script type="text/javascript">
        window.googleTranslateInitCalled = false;
        function googleTranslateElementInit() {
            if (window.googleTranslateInitCalled) return;
            window.googleTranslateInitCalled = true;
            
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,te,hi,ta,kn,ml',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }

        // Handle Turbo navigation - ensure widget persists or re-attaches safely
        document.addEventListener('turbo:load', function() {
            // If the element exists but widget isn't there, we might need a push
            if (window.google && google.translate && google.translate.TranslateElement) {
                // If the element was removed/re-added by Turbo, we check if we need to re-init
                const el = document.getElementById('google_translate_element');
                if (el && !el.innerHTML.trim()) {
                    window.googleTranslateInitCalled = false;
                    googleTranslateElementInit();
                }
            }
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <style>
        /* Hide Google Translate top banner */
        .goog-te-banner-frame.skiptranslate { display: none !important; }
        body { top: 0px !important; }
        
        /* Style the select element to match brand */
        .goog-te-gadget-simple {
            background-color: transparent !important;
            border: none !important;
            padding: 4px 8px !important;
            border-radius: 8px !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
        }
        .goog-te-gadget-simple span {
            color: #d97706 !important; /* text-brand-600 */
            font-size: 10px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
        }
        .goog-te-menu-value img { display: none !important; }
        .goog-te-menu-value span:nth-child(3) { display: none !important; }
        .goog-te-gadget img { display: none !important; }
        .goog-tooltip { display: none !important; }
        .goog-tooltip:hover { display: none !important; }
        .goog-text-highlight { background-color: transparent !important; box-shadow: none !important; }
    </style>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('liveSearch', () => ({
            query: '',
            results: [],
            loading: false,
            selectedCategory: '',
            abortController: null,
            
            async performSearch() {
                const q = this.query.trim();
                if (q.length < 2) {
                    this.results = [];
                    this.loading = false;
                    return;
                }
                
                // Cancel previous request if any
                if (this.abortController) {
                    this.abortController.abort();
                }
                this.abortController = new AbortController();

                this.loading = true;
                try {
                    // Correct endpoint to match SearchController@index with AJAX support
                    const url = `/${window.AppLocale}/search?q=${encodeURIComponent(q)}&category=${this.selectedCategory}&format=json`;
                    const response = await fetch(url, {
                        headers: { 
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        signal: this.abortController.signal
                    });
                    const data = await response.json();
                    
                    // The SearchController returns a direct array of products
                    this.results = Array.isArray(data) ? data : (data.products || []);
                } catch (e) {
                    if (e.name !== 'AbortError') {
                        console.error('Search failed', e);
                    }
                } finally {
                    // Only stop loading if we're not waiting for another request
                    if (!this.abortController || this.abortController.signal.aborted) {
                        // ignore
                    } else {
                        this.loading = false;
                    }
                }
            }
        }));
    });

    /**
     * addToCart — global function called by product_card partial's onclick.
     * Dispatches a cart-add action and updates the nav cart counter.
     */
    async function addToCart(productId) {
        try {
            if (window.BcLoader) BcLoader.show('Adding to cart...');
            const res = await fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            });
            const data = await res.json();
            if (window.BcLoader) BcLoader.hide();
            if (data.error) {
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.error, type: 'error' } }));
            } else {
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: data.cart_count ?? data.count } }));
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Added to cart ✓', type: 'success' } }));
            }
        } catch(e) {
            if (window.BcLoader) BcLoader.hide();
            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Something went wrong', type: 'error' } }));
        }
    }

    /**
     * toggleWishlist — global function called by product_card partial's onclick.
     * Toggles a product in the wishlist and updates the nav wishlist counter.
     */
    async function toggleWishlist(productId, btn) {
        try {
            const res = await fetch('{{ route("wishlist.toggle") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            });
            const data = await res.json();
            if (data.error) {
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.error, type: 'error' } }));
            } else {
                window.dispatchEvent(new CustomEvent('wishlist-updated', { detail: { count: data.wishlist_count ?? data.count } }));
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.status, type: 'success' } }));
            }
        } catch(e) {
            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Something went wrong', type: 'error' } }));
        }
    }
    </script>
    
    @livewireScripts
    
    <!-- Premium Cookie Consent Banner -->
    <div x-data="{ 
            visible: false,
            accept() {
                localStorage.setItem('bc_cookie_consent', 'accepted');
                this.visible = false;
            },
            init() {
                if (!localStorage.getItem('bc_cookie_consent')) {
                    setTimeout(() => this.visible = true, 2000);
                }
            }
         }" 
         x-show="visible"
         x-transition:enter="transition ease-out duration-700 transform"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-500 transform"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed bottom-0 left-0 right-0 z-[110] bg-white border-t border-brand-100 shadow-[0_-20px_40px_-20px_rgba(0,0,0,0.1)]"
         style="display: none;">
        
        <div class="h-1 w-full bg-gradient-to-r from-brand-600 via-brand-500 to-brand-900"></div>

        <div class="container mx-auto px-6 py-6 lg:py-4">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                <div class="flex items-center space-x-6 text-center lg:text-left">
                    <div class="h-10 w-10 bg-brand-50 rounded-xl flex items-center justify-center shrink-0 hidden md:flex">
                        <svg class="h-5 w-5 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" /></svg>
                    </div>
                    <div>
                        <h4 class="text-[10px] font-black text-onyx-950 uppercase tracking-[3px] mb-1">Ritual Personalization</h4>
                        <p class="text-[9px] font-bold text-gray-400 uppercase leading-relaxed tracking-wider max-w-2xl">We use cookies to tailor your experience with our handmade collection, ensuring every interaction honors our heritage.</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-6 shrink-0 w-full lg:w-auto justify-center">
                    <a href="{{ route('pages.cookie') }}" class="text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-brand-600 transition-colors">Preferences</a>
                    <button @click="accept()" class="px-10 py-3 bg-brand-600 text-white text-[9px] font-black uppercase tracking-[3px] rounded-xl hover:bg-brand-500 transition-all shadow-xl shadow-brand-500/20 active:scale-95">Accept & Proceed</button>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Artisan Concierge: The Sacred Specialist AI -->
    <div x-data="{ 
            open: false,
            showGreeting: false,
            loading: false,
            messages: [
                { role: 'ai', text: 'Namaste! I am your Sacred Specialist. How can I help you today?' }
            ],
            userInput: '',
            init() {
                setTimeout(() => { if(!this.open) this.showGreeting = true }, 4000);
            },
            async sendMessage(text = null) {
                const msg = text || this.userInput;
                if (!msg.trim()) return;
                
                this.messages.push({ role: 'user', text: msg });
                const currentHistory = [...this.messages];
                this.userInput = '';
                this.loading = true;
                this.showGreeting = false;

                this.$nextTick(() => {
                    const chat = document.getElementById('ai-chat-body');
                    if(chat) chat.scrollTop = chat.scrollHeight;
                });

                try {
                    const response = await fetch('{{ route('api.chat.ask', ['locale' => 'en-in']) }}', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ 
                            message: msg,
                            history: currentHistory
                        })
                    });
                    const data = await response.json();
                    this.messages.push({ role: 'ai', text: data.response });
                } catch (e) {
                    this.messages.push({ role: 'ai', text: 'I am taking a small rest. Ask me later!' });
                } finally {
                    this.loading = false;
                    this.$nextTick(() => {
                        const chat = document.getElementById('ai-chat-body');
                        if(chat) chat.scrollTop = chat.scrollHeight;
                    });
                }
            }
         }"
         class="fixed bottom-28 right-8 z-[120]">
        
        <!-- Greeting Bubble -->
        <div x-show="showGreeting && !open" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0 translate-x-4 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-300"
             class="absolute right-[calc(100%+1.5rem)] bottom-4 w-48 bg-white border border-brand-100 rounded-2xl py-3 px-5 shadow-2xl shadow-brand-500/20 pointer-events-none after:content-[''] after:absolute after:top-1/2 after:-right-2 after:-translate-y-1/2 after:border-8 after:border-transparent after:border-l-white" 
             style="display: none;">
            <p class="text-[9px] font-black text-onyx-900 uppercase tracking-widest leading-relaxed">Namaste! Need help with your Sacred space? 🙏</p>
        </div>
        
        <!-- Toggle Bubble -->
        <button @click="open = !open; showGreeting = false" 
                class="h-16 w-16 bg-[#e67e22] text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all border-2 border-white/20 relative group">
            <svg x-show="!open" class="h-8 w-8 text-[#f1c40f]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012-2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <svg x-show="open" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Chat Window -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="opacity-0 translate-y-12 scale-90"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             class="absolute bottom-20 right-0 w-[350px] bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 flex flex-col overflow-hidden"
             style="height: 500px; max-height: 80vh; display: none;">
            
            <div class="px-8 py-6 bg-gradient-to-br from-[#1e293b] to-[#0f172a] text-white flex flex-col items-center">
                <h3 class="text-[10px] font-black uppercase tracking-[4px]">Sacred Specialist AI</h3>
                <div class="h-1 w-12 bg-brand-500 mt-2 rounded-full"></div>
            </div>

            <div id="ai-chat-body" class="flex-1 overflow-y-auto px-6 py-6 space-y-4 bg-gray-50/30">
                <template x-for="msg in messages">
                    <div :class="msg.role === 'ai' ? 'flex justify-start' : 'flex justify-end'">
                        <div :class="msg.role === 'ai' ? 'bg-white text-onyx-900 border border-gray-100 shadow-sm' : 'bg-[#e67e22] text-white'" 
                             class="max-w-[85%] p-4 rounded-2xl text-[10px] font-bold">
                            <span x-text="msg.text"></span>
                        </div>
                    </div>
                </template>
                <div x-show="loading" class="flex justify-start">
                    <div class="bg-white p-3 rounded-2xl border border-gray-100">
                        <div class="flex space-x-1">
                            <div class="h-1.5 w-1.5 bg-gray-300 rounded-full animate-bounce"></div>
                            <div class="h-1.5 w-1.5 bg-gray-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6 bg-white border-t border-gray-100">
                <div class="relative">
                    <input type="text" x-model="userInput" @keydown.enter="sendMessage()" placeholder="Ask in simple English..." class="w-full bg-gray-50 border border-gray-100 rounded-xl py-3 pl-4 pr-12 text-[10px] font-black uppercase tracking-widest focus:ring-1 focus:ring-brand-500 focus:outline-none">
                    <button @click="sendMessage()" class="absolute right-2 top-2 h-8 w-8 bg-[#e67e22] text-white rounded-lg flex items-center justify-center">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Cart / Wishlist Slide-Over -->
    <div x-show="cartOpen" 
         class="fixed inset-0 z-[200] flex overflow-hidden bg-onyx-900/60 backdrop-blur-sm"
         style="display: none;">
         
        <!-- Backdrop -->
        <div x-show="cartOpen" 
             x-transition:enter="ease-in-out duration-500" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in-out duration-500" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0"
             @click="cartOpen = false"
             class="absolute inset-0 transition-opacity"></div>

        <!-- Sidebar -->
        <div class="fixed inset-y-0 right-0 flex max-w-full pl-10"
             x-show="cartOpen"
             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
             x-transition:enter-start="translate-x-full" 
             x-transition:enter-end="translate-x-0" 
             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
             x-transition:leave-start="translate-x-0" 
             x-transition:leave-end="translate-x-full">
             
            <div class="w-screen max-w-md bg-white shadow-[0_0_40px_rgba(0,0,0,0.1)] flex flex-col h-full pointer-events-auto border-l border-gray-100 relative">
                
                <!-- Close Button -->
                <button @click="cartOpen = false" class="absolute top-4 right-4 h-10 w-10 bg-gray-50 flex items-center justify-center rounded-full text-gray-400 hover:text-brand-500 transition-colors z-10">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <!-- Header Tabs -->
                <div class="pt-8 px-6 pb-4 border-b border-gray-100 flex items-center space-x-8">
                    <button @click="activeTab = 'cart'; fetchCart()" 
                            class="text-sm font-black uppercase tracking-widest transition-colors relative pb-4"
                            :class="activeTab === 'cart' ? 'text-onyx-900 border-b-2 border-brand-500' : 'text-gray-400 hover:text-onyx-900'">
                        Cart (<span x-text="cartCount"></span>)
                    </button>
                    <button @click="activeTab = 'wishlist'; fetchWishlist()" 
                            class="text-sm font-black uppercase tracking-widest transition-colors relative pb-4"
                            :class="activeTab === 'wishlist' ? 'text-onyx-900 border-b-2 border-brand-500' : 'text-gray-400 hover:text-onyx-900'">
                        Wishlist (<span x-text="wishlistCount"></span>)
                    </button>
                </div>

                <!-- Tab: Cart -->
                <div x-show="activeTab === 'cart'" class="flex-1 overflow-y-auto flex flex-col pt-4">
                    <template x-if="loading">
                        <div class="flex-1 flex flex-col items-center justify-center text-brand-500 p-8 space-y-4">
                            <svg class="animate-spin h-8 w-8" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span class="text-[10px] font-black uppercase tracking-[2px]">Synchronizing...</span>
                        </div>
                    </template>
                    <template x-if="!loading && (cartItems || []).length === 0">
                        <div class="flex-1 flex flex-col items-center justify-center p-8 text-center space-y-6">
                            <div class="h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center">
                                <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-wider text-onyx-900 mb-2">Your Cart is Empty</h3>
                                <p class="text-[10px] text-gray-500 font-medium">Discover our authentic artifacts.</p>
                            </div>
                            <button @click="cartOpen = false" class="px-8 py-3 bg-brand-50 text-brand-600 rounded-xl text-[9px] font-black uppercase tracking-widest hover:bg-brand-500 hover:text-white transition-colors">Start Shopping</button>
                        </div>
                    </template>
                    <template x-if="!loading && (cartItems || []).length > 0">
                        <div class="flex-1 pb-24">
                            <ul class="divide-y divide-gray-100">
                                <template x-for="item in cartItems" :key="item.id">
                                    <li class="p-6 flex space-x-4 bg-white hover:bg-gray-50 transition-colors group">
                                        <div class="h-20 w-20 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                                            <template x-if="item.image">
                                                <img :src="item.image" :alt="item.name" class="h-full w-full object-cover">
                                            </template>
                                            <template x-if="!item.image">
                                                <div class="h-full w-full flex items-center justify-center text-gray-300">
                                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                </div>
                                            </template>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-center">
                                            <a :href="'/en/artifact/' + item.slug" class="text-xs font-bold text-onyx-900 hover:text-brand-500 transition-colors block mb-1 truncate" x-text="item.name"></a>
                                            <span class="text-[11px] font-black text-brand-600 tracking-wider mb-2" x-text="'₹' + Number(item.price).toLocaleString()"></span>
                                            
                                            <!-- Quantity & Remove -->
                                            <div class="flex items-center space-x-4 mt-auto">
                                                <div class="flex items-center space-x-2">
                                                    <button @click="if(item.quantity > 1) updateCart(item.id, item.quantity - 1)" class="h-6 w-6 bg-gray-100 text-gray-600 rounded flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors">-</button>
                                                    <span class="text-xs font-bold w-6 text-center" x-text="item.quantity"></span>
                                                    <button @click="updateCart(item.id, item.quantity + 1)" class="h-6 w-6 bg-gray-100 text-gray-600 rounded flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors">+</button>
                                                </div>
                                                <button @click="removeFromCart(item.id)" class="text-[9px] font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors flex items-center space-x-1">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    <span>Remove</span>
                                                </button>
                                            </div>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </template>

                    <!-- Cart Footer Checkout Box -->
                    <template x-if="!loading && (cartItems || []).length > 0">
                        <div class="p-6 bg-gray-50 border-t border-gray-100 sticky bottom-0 z-20 shadow-[0_-10px_20px_rgba(0,0,0,0.02)]">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Total Selection</span>
                                <span class="text-xl font-black text-onyx-900" x-text="'₹' + (cartItems || []).reduce((a, b) => a + (b.price * b.quantity), 0).toLocaleString()"></span>
                            </div>
                            <p class="text-[9px] text-gray-400 font-medium mb-6">Shipping and taxes calculated at checkout. Coupons can be applied at checkout.</p>
                            <a href="{{ route('checkout') }}" class="w-full h-14 flex items-center justify-center bg-brand-600 hover:bg-brand-500 text-white transition-colors rounded-[1rem] text-xs font-black uppercase tracking-[2px] shadow-lg shadow-brand-500/20">
                                Verify & Checkout
                            </a>
                        </div>
                    </template>
                </div>

                <!-- Tab: Wishlist -->
                <div x-show="activeTab === 'wishlist'" style="display: none;" class="flex-1 overflow-y-auto flex flex-col pt-4">
                    <template x-if="loading">
                        <div class="flex-1 flex flex-col items-center justify-center text-brand-500 p-8 space-y-4">
                            <svg class="animate-spin h-8 w-8" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                        </div>
                    </template>
                    <template x-if="!loading && (wishlistItems || []).length === 0">
                        <div class="flex-1 flex flex-col items-center justify-center p-8 text-center space-y-6">
                            <div class="h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center">
                                <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xs font-black uppercase tracking-wider text-onyx-900 mb-2">No Saved Items</h3>
                                <p class="text-[10px] text-gray-500 font-medium">Keep track of items you love by adding them to your wishlist.</p>
                            </div>
                        </div>
                    </template>
                    <template x-if="!loading && (wishlistItems || []).length > 0">
                        <ul class="divide-y divide-gray-100">
                            <template x-for="item in wishlistItems" :key="item.id">
                                <li class="p-6 flex space-x-4 bg-white hover:bg-gray-50 transition-colors">
                                    <div class="h-20 w-20 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                                        <template x-if="item.image">
                                            <img :src="item.image" :alt="item.name" class="h-full w-full object-cover">
                                        </template>
                                    </div>
                                    <div class="flex-1 flex flex-col justify-center">
                                        <a :href="'/en/artifact/' + item.slug" class="text-xs font-bold text-onyx-900 hover:text-brand-500 transition-colors block mb-1 truncate" x-text="item.name"></a>
                                        <span class="text-[11px] font-black text-brand-600 tracking-wider mb-2" x-text="'₹' + Number(item.price).toLocaleString()"></span>
                                        <div class="flex items-center space-x-4 mt-auto">
                                            <button @click="addToCart(item.product_id); toggleWishlist(item.product_id)" class="text-[10px] font-bold text-brand-500 hover:text-brand-600 uppercase tracking-widest transition-colors">Move to Cart</button>
                                            <button @click="toggleWishlist(item.product_id)" class="text-[10px] font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors flex items-center space-x-1">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                <span>Remove</span>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Corporate Catalog Request Modal -->
    <div x-show="corporateModalOpen" 
         class="fixed inset-0 z-[200] flex items-center justify-center px-4" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="fixed inset-0 bg-onyx-950/90 backdrop-blur-md" @click="corporateModalOpen = false"></div>
        
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] overflow-hidden shadow-2xl relative z-10 transform transition-all"
             x-show="corporateModalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95">
            
            <div class="flex h-full flex-col md:flex-row">
                <!-- Left: Visual/Info -->
                <div class="w-full md:w-5/12 bg-onyx-950 p-10 text-white relative overflow-hidden flex flex-col justify-center">
                    <img src="/storage/corporate_gifting_showcase_1774718273819.png" class="absolute inset-0 w-full h-full object-cover opacity-20 pointer-events-none">
                    <div class="relative z-10">
                        <span class="text-[9px] font-black uppercase tracking-[4px] text-amber-500 block mb-4">Institutional Supply</span>
                        <h3 class="text-3xl font-serif font-bold italic mb-6 leading-tight">Elevate Your Corporate Identity.</h3>
                        <p class="text-xs text-gray-400 leading-relaxed mb-8">Join 500+ global partners who trust Bhavani Crafts for premium brass artifacts and institutional supplies.</p>
                        
                        <ul class="space-y-4">
                            <li class="flex items-center space-x-3 text-[9px] font-bold uppercase tracking-widest text-white/70">
                                <svg class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                <span>Custom Logo Branding</span>
                            </li>
                            <li class="flex items-center space-x-3 text-[9px] font-bold uppercase tracking-widest text-white/70">
                                <svg class="h-4 w-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                <span>Bulk Institutional Pricing</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="w-full md:w-7/12 p-8 md:p-12 bg-white relative">
                    <button @click="corporateModalOpen = false" class="absolute top-6 right-6 p-2 text-gray-400 hover:text-brand-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>

                    <h4 class="text-xl font-serif font-bold text-onyx-900 mb-2">Request Catalog</h4>
                    <p class="text-[10px] text-gray-500 font-medium mb-8">Submit your details and our relationship manager will reach out within 24 hours.</p>

                    <form action="{{ route('corporate.catalog.request', ['locale' => app()->getLocale()]) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Company Name</label>
                                <input type="text" name="company_name" required placeholder="Organization Ltd." class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none">
                            </div>
                            <div>
                                <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Contact Person</label>
                                <input type="text" name="contact_person" required placeholder="Full Name" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Official Email</label>
                            <input type="email" name="email" required placeholder="corporate@company.com" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none">
                        </div>

                        <div>
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Phone Number</label>
                            <input type="tel" name="phone" required placeholder="+91 XXXX XXXX XX" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none">
                        </div>

                        <div>
                            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 block mb-1.5 ml-1">Requirement (Optional)</label>
                            <textarea name="message" rows="2" placeholder="Tell us more about your event/needs..." class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-medium focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 transition-all outline-none resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 bg-brand-600 hover:bg-brand-500 text-white text-[10px] font-black uppercase tracking-[3px] rounded-xl transition-all shadow-lg shadow-brand-500/20">
                            Send Request
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Concierge (WhatsApp) -->
    <a href="https://wa.me/{{ $footerSettings['contact_whatsapp_number'] ?? '919676832291' }}" target="_blank"
       class="fixed bottom-8 right-8 z-[100] group flex items-center">
       <div class="h-16 w-16 bg-[#25d366] text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all border-2 border-white/20">
           <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
               <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.446 L.657 24l4.552-1.194a11.832 11.832 0 005.833 1.547h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
       </div>
    </a>

</body>
</html>
