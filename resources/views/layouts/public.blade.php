<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bhavani Crafts | Premium Artifacts') }}</title>

    <!-- Google Fonts: Modern & Premium -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (via CDN for immediate dev flexibility, ideally compiled in production) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['"Playfair Display"', 'serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fff9f2',
                            100: '#fff1e0',
                            200: '#ffdfb3',
                            300: '#ffc67a',
                            400: '#ff9933', 
                            500: '#f5821c',
                            600: '#db6411',
                            700: '#b64a10',
                            800: '#913b14',
                            900: '#753213',
                            950: '#3f1606',
                        },
                        onyx: {
                            900: '#111111', 
                            800: '#1a1a1a', 
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .premium-shadow {
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
        }

        /* ── Global Loading Overlay ───────────────────────── */
        #bc-loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(17, 17, 17, 0.65);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }
        #bc-loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        .bc-spinner {
            width: 52px;
            height: 52px;
            border: 4px solid rgba(245, 130, 28, 0.2);
            border-top-color: #f5821c;
            border-radius: 50%;
            animation: bc-spin 0.7s linear infinite;
        }
        @keyframes bc-spin { to { transform: rotate(360deg); } }

        /* ── Page Progress Bar ─────────────────────────────── */
        #bc-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: linear-gradient(90deg, #f5821c, #ffc67a, #f5821c);
            background-size: 200% 100%;
            z-index: 10000;
            transition: width 0.3s ease, opacity 0.4s ease;
            animation: bc-progress-shimmer 1.2s linear infinite;
        }
        @keyframes bc-progress-shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* ── Button Loading State ──────────────────────────── */
        .btn-loading {
            position: relative;
            pointer-events: none !important;
            opacity: 0.8 !important;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: bc-spin 0.6s linear infinite;
        }
        .btn-loading-dark::after {
            border: 2px solid rgba(0,0,0,0.15);
            border-top-color: #111;
        }

        /* Disable interactions on page during loading */
        body.bc-busy { cursor: wait !important; }
        /* Block plain <a> links without onclick (navigations handled by progress bar) */
        body.bc-busy a:not([onclick]):not([data-bc-allow]) { pointer-events: none !important; cursor: wait !important; }
        /* Block submit buttons inside forms, but NOT action buttons with onclick */
        body.bc-busy button[type="submit"]:not([data-bc-allow]) { pointer-events: none !important; cursor: wait !important; }
        /* Allow the overlay itself always */
        body.bc-busy #bc-loading-overlay { pointer-events: all !important; cursor: default !important; }

        /* ── Global Text Legibility ─────────────────────────── */
        * { text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased; }
        /* Ensure body copy never goes lighter than a readable slate */
        p, li, td, th { color: inherit; }
        /* Raise minimum size for tiny tracking labels so they remain legible */
        .text-\[9px\]  { font-size: 10px !important; }
        .text-\[10px\] { font-size: 11px !important; }
        .text-\[11px\] { font-size: 12px !important; }
        /* Slightly heavier base weight for body copy */
        body { font-weight: 450; }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50/30 selection:bg-brand-400 selection:text-white flex flex-col min-h-screen">

    {{-- ── Global Loading Overlay ───────────────────────────────────────── --}}
    <div id="bc-loading-overlay">
        <div class="text-center">
            <div class="bc-spinner mx-auto mb-5"></div>
            <p id="bc-loading-msg" class="text-white text-[11px] font-black uppercase tracking-[3px] opacity-80">Processing...</p>
        </div>
    </div>

    {{-- ── Page Progress Bar ────────────────────────────────────────────── --}}
    <div id="bc-progress-bar"></div>

    {{-- ── Global Loading JS (must be early) ───────────────────────────── --}}
    <script>
        // Public API: window.BcLoader
        window.BcLoader = {
            overlay: null,
            bar: null,
            msg: null,
            _timer: null,

            init() {
                this.overlay = document.getElementById('bc-loading-overlay');
                this.bar     = document.getElementById('bc-progress-bar');
                this.msg     = document.getElementById('bc-loading-msg');
            },

            show(message = 'Processing...') {
                if (!this.overlay) this.init();
                this.msg.textContent = message;
                this.overlay.classList.add('active');
                document.body.classList.add('bc-busy');
                // Progress bar animation
                this.bar.style.opacity = '1';
                this.bar.style.width = '15%';
                clearTimeout(this._timer);
                this._timer = setTimeout(() => { this.bar.style.width = '70%'; }, 200);
            },

            hide() {
                if (!this.overlay) return;
                // Complete the bar
                this.bar.style.width = '100%';
                setTimeout(() => {
                    this.bar.style.opacity = '0';
                    setTimeout(() => { this.bar.style.width = '0%'; }, 400);
                }, 300);
                this.overlay.classList.remove('active');
                document.body.classList.remove('bc-busy');
            },

            // Add loading state to a button
            btnStart(btn, text = null) {
                if (!btn) return;
                btn._originalHTML = btn.innerHTML;
                btn._originalDisabled = btn.disabled;
                if (text) btn.innerHTML = `<span class="opacity-0 sr-only">${btn.textContent}</span>` + text;
                btn.classList.add('btn-loading');
                btn.disabled = true;
            },

            btnStop(btn) {
                if (!btn || !btn._originalHTML) return;
                btn.innerHTML = btn._originalHTML;
                btn.classList.remove('btn-loading');
                btn.disabled = btn._originalDisabled || false;
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            BcLoader.init();

            // ── Auto-intercept all standard form submissions ──────────────
            document.addEventListener('submit', (e) => {
                const form = e.target;
                // Skip forms with data-no-loader or AJAX-only forms
                if (form.dataset.noLoader !== undefined) return;
                // Skip filter forms (they are fast GET requests — use progress bar only)
                const isFilterForm = form.id === 'filter-form' || form.dataset.filterForm !== undefined;
                if (isFilterForm) {
                    BcLoader.bar.style.opacity = '1';
                    BcLoader.bar.style.width = '50%';
                    return;
                }
                // Identify submit button
                const submitBtn = form.querySelector('[type="submit"]');
                BcLoader.show('Please wait...');
                if (submitBtn) BcLoader.btnStart(submitBtn);
            });

            // ── Auto-intercept page navigation links ─────────────────────
            document.addEventListener('click', (e) => {
                const link = e.target.closest('a[href]');
                if (!link) return;
                const href = link.getAttribute('href');
                // Skip anchors, external links, javascript:, # links
                if (!href || href.startsWith('#') || href.startsWith('javascript') || href.startsWith('mailto') || link.target === '_blank') return;
                // Skip links with data-no-loader
                if (link.dataset.noLoader !== undefined) return;
                // Only internal links
                try {
                    const url = new URL(href, window.location.origin);
                    if (url.origin !== window.location.origin) return;
                } catch { return; }
                BcLoader.bar.style.opacity = '1';
                BcLoader.bar.style.width = '30%';
                setTimeout(() => { BcLoader.bar.style.width = '70%'; }, 300);
            });

            // ── Hide loader when page is fully loaded ─────────────────────
            window.addEventListener('load', () => BcLoader.hide());
        });
    </script>


    <!-- Promotional Top Bar -->
    <div class="bg-onyx-900 text-brand-100 text-[11px] font-bold uppercase tracking-[2px] py-2.5 text-center relative z-50 overflow-hidden">
        <div class="container mx-auto px-4 flex justify-between items-center relative z-10">
            <span class="hidden md:inline-block">Exclusive: B2B Wholesale Franchise Opportunities Open</span>
            <span>Worldwide Shipping | 100% Secure Checkout</span>
            <span class="hidden md:inline-block">Support: +91 1800 123 456</span>
        </div>
        <!-- subtle light bleed effect -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-full bg-brand-500/10 blur-xl"></div>
    </div>

    <!-- Main Navigation Bar -->
    <header class="sticky top-0 z-40 w-full glass-nav transition-all duration-300" 
            x-data="globalState"
            @cart-updated.window="cartCount = $event.detail.count; fetchCart(); cartOpen = true"
            @wishlist-updated.window="wishlistCount = $event.detail.count"
            @notify.window="addNotification($event.detail.message, $event.detail.type || 'success')">
        
        <!-- Side Cart Drawer -->
        <div x-cloak x-show="cartOpen" class="fixed inset-0 z-[100] overflow-hidden">
            <!-- Overlay -->
            <div x-show="cartOpen" 
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-onyx-900/60 backdrop-blur-sm" 
                 @click="cartOpen = false"></div>
            
            <div class="fixed inset-y-0 right-0 max-w-full flex">
                <div x-show="cartOpen"
                     x-transition:enter="transform transition ease-in-out duration-500"
                     x-transition:enter-start="translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transform transition ease-in-out duration-500"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="translate-x-full"
                     class="w-screen max-w-md h-screen pointer-events-auto">
                    <div class="h-full flex flex-col bg-white shadow-2xl relative">
                        <!-- Drawer Header -->
                        <div class="px-6 pt-8 pb-4 bg-onyx-900 text-white">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-3">
                                    <span class="h-10 w-10 bg-brand-500 text-white rounded-xl flex items-center justify-center">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path x-show="activeTab === 'cart'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            <path x-show="activeTab === 'wishlist'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </span>
                                    <div>
                                        <h2 class="text-lg font-black uppercase tracking-widest" x-text="activeTab === 'cart' ? 'Ritual Cart' : 'Sacred Collection'"></h2>
                                        <p class="text-[10px] text-brand-400 font-bold uppercase tracking-widest" x-text="activeTab === 'cart' ? `${cartCount} items selected` : `${wishlistCount} artifacts saved`"></p>
                                    </div>
                                </div>
                                <button @click="cartOpen = false" class="p-2 hover:bg-white/10 rounded-full transition-colors">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>

                            <!-- Tabs -->
                            <div class="flex p-1 bg-white/10 rounded-2xl w-full mt-4">
                                <button @click="activeTab = 'cart'; fetchCart()" 
                                        :class="activeTab === 'cart' ? 'bg-white text-onyx-900 shadow-xl' : 'text-white/60 hover:text-white'"
                                        class="flex-1 py-3 text-[10px] font-black uppercase tracking-[2px] rounded-xl transition-all duration-300">
                                    Cart
                                </button>
                                <button @click="activeTab = 'wishlist'; fetchWishlist()" 
                                        :class="activeTab === 'wishlist' ? 'bg-white text-onyx-900 shadow-xl' : 'text-white/60 hover:text-white'"
                                        class="flex-1 py-3 text-[10px] font-black uppercase tracking-[2px] rounded-xl transition-all duration-300">
                                    Wishlist
                                </button>
                            </div>
                        </div>

                        <!-- Drawer Content -->
                        <div class="flex-1 overflow-y-auto py-6 px-6">
                            <!-- Cart Tab -->
                            <div x-show="activeTab === 'cart'" x-transition>
                                <div class="space-y-8" x-show="cartItems.length > 0">
                                    <template x-for="item in cartItems" :key="item.id">
                                        <div class="flex items-center space-x-4 group animate-fadeIn">
                                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl border border-gray-100 bg-gray-50 group-hover:border-brand-200 transition-colors relative">
                                                <img :src="item.image || '/assets/placeholder.png'" :alt="item.name" class="h-full w-full object-cover">
                                                <template x-if="item.discount_percent > 0">
                                                    <div class="absolute top-1 left-1 bg-red-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded shadow-sm">
                                                        <span x-text="item.discount_percent + '% OFF'"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex-1 flex flex-col">
                                                <div class="flex justify-between items-start">
                                                    <h3 class="text-sm font-bold text-onyx-900 group-hover:text-brand-500 transition-colors line-clamp-2" x-text="item.name"></h3>
                                                    <button @click="removeFromCart(item.id)" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>
                                                <div class="flex items-center justify-between mt-4">
                                                    <!-- Quantity Controls -->
                                                    <div class="flex items-center bg-gray-100 rounded-lg px-2 py-1">
                                                        <button @click="updateQuantity(item.id, item.quantity - 1)" class="h-6 w-6 flex items-center justify-center text-gray-500 hover:text-brand-500 transition-colors">-</button>
                                                        <span class="mx-3 text-xs font-black text-onyx-900 w-4 text-center" x-text="item.quantity"></span>
                                                        <button @click="updateQuantity(item.id, item.quantity + 1)" class="h-6 w-6 flex items-center justify-center text-gray-500 hover:text-brand-500 transition-colors">+</button>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span class="text-[10px] text-gray-400 line-through font-bold" x-show="item.mrp > item.price" x-text="'₹' + item.mrp.toLocaleString()"></span>
                                                        <span class="text-sm font-black text-onyx-900" x-text="'₹' + item.total.toLocaleString()"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Empty State -->
                                <div x-show="cartItems.length === 0" class="h-full flex flex-col items-center justify-center text-center px-4 py-12">
                                    <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-onyx-900 mb-2 uppercase tracking-wide">Your Cart is Empty</h3>
                                    <p class="text-sm text-gray-400 font-medium max-w-[200px]">Begin your ritual by selecting exquisite artifacts from our catalog.</p>
                                    <button @click="cartOpen = false" class="mt-8 px-8 py-3 bg-brand-500 text-white text-[11px] font-black uppercase tracking-[3px] rounded-xl hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20">
                                        Browse Artifacts
                                    </button>
                                </div>
                            </div>

                            <!-- Wishlist Tab -->
                            <div x-show="activeTab === 'wishlist'" x-transition>
                                <div class="space-y-8" x-show="wishlistItems.length > 0">
                                    <template x-for="item in wishlistItems" :key="item.id">
                                        <div class="flex items-center space-x-4 group animate-fadeIn">
                                            <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-2xl border border-gray-100 bg-gray-50 group-hover:border-brand-200 transition-colors relative">
                                                <img :src="item.image || '/assets/placeholder.png'" :alt="item.name" class="h-full w-full object-cover">
                                                <template x-if="item.discount_percent > 0">
                                                    <div class="absolute top-1 left-1 bg-red-500 text-white text-[8px] font-black px-1.5 py-0.5 rounded shadow-sm">
                                                        <span x-text="item.discount_percent + '% OFF'"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex-1 flex flex-col">
                                                <div class="flex justify-between items-start">
                                                    <h3 class="text-sm font-bold text-onyx-900 group-hover:text-brand-500 transition-colors line-clamp-2" x-text="item.name"></h3>
                                                    <button @click="removeFromWishlist(item.product_id)" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </button>
                                                </div>
                                                <div class="flex items-center justify-between mt-4">
                                                <div class="flex flex-col">
                                                    <span class="text-[10px] text-gray-400 line-through font-bold" x-show="item.mrp > item.price" x-text="'₹' + item.mrp.toLocaleString()"></span>
                                                    <span class="text-sm font-black text-onyx-900" x-text="'₹' + item.price.toLocaleString()"></span>
                                                </div>
                                                    <button @click="moveToCart(item.product_id)" class="px-4 py-2 bg-brand-600 text-white text-[9px] font-black uppercase tracking-widest rounded-lg hover:bg-brand-500 transition-all shadow-md">
                                                        Add to Cart
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <!-- Empty State -->
                                <div x-show="wishlistItems.length === 0" class="h-full flex flex-col items-center justify-center text-center px-4 py-12">
                                    <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-onyx-900 mb-2 uppercase tracking-wide">Collection is Empty</h3>
                                    <p class="text-sm text-gray-400 font-medium max-w-[200px]">Save artifacts you love for future rituals.</p>
                                    <button @click="cartOpen = false" class="mt-8 px-8 py-3 bg-brand-500 text-white text-[11px] font-black uppercase tracking-[3px] rounded-xl hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20">
                                        Explore Sacred Art
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Drawer Footer (Only for Cart) -->
                        <div x-show="activeTab === 'cart' && cartItems.length > 0" class="border-t border-gray-100 px-6 py-6 space-y-4 bg-gray-50/50">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-bold text-gray-500 uppercase tracking-widest">Ritual Total</span>
                                <span class="text-xl font-black text-onyx-900" x-text="'₹' + cartSubtotal.toLocaleString()"></span>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center">Shipping & sacred taxes calculated at checkout</p>
                            <a href="{{ route('checkout') }}" class="block w-full py-4 bg-brand-600 text-white text-center text-xs font-black uppercase tracking-[4px] rounded-2xl hover:bg-brand-500 transition-all duration-500 shadow-xl shadow-brand-900/10 hover:shadow-brand-500/30">
                                Secure Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="fixed top-24 left-1/2 -translate-x-1/2 z-[60] w-full max-w-sm px-4 flex flex-col space-y-2">
            <template x-for="note in notifications" :key="note.id">
                <div x-transition.opacity 
                     class="bg-brand-600 border-l-4 text-white p-4 rounded-xl shadow-2xl flex items-center justify-between pointer-events-auto"
                     :class="note.type === 'error' ? 'border-red-500' : 'border-brand-500'">
                    <div class="flex items-center space-x-3">
                        <template x-if="note.type !== 'error'">
                            <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </template>
                        <template x-if="note.type === 'error'">
                            <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </template>
                        <p class="text-[10px] font-bold uppercase tracking-widest" x-text="note.message"></p>
                    </div>
                    <button @click="removeNotification(note.id)" class="text-white/50 hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </template>
        </div>

        <!-- Static PHP Session Notification (Initial Page Load) -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition.opacity class="fixed top-24 left-1/2 -translate-x-1/2 z-[60] w-full max-w-sm px-4">
                <div class="bg-brand-600 border-l-4 border-brand-500 text-white p-4 rounded-xl shadow-2xl flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p class="text-[10px] font-bold uppercase tracking-widest">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-white/50 hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        @endif

        <nav class="container mx-auto px-4 lg:px-8 py-4 lg:py-5 flex items-center justify-between">
            
            <!-- Left: Mobile Menu Toggle -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 -ml-2 text-gray-800 hover:text-brand-500 transition-colors focus:outline-none">
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="mobileMenuOpen" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2 shrink-0 group">
                <div class="h-10 w-10 bg-brand-600 text-white rounded-xl flex items-center justify-center font-serif text-2xl font-bold shadow-lg shadow-onyx-900/20 group-hover:bg-brand-500 group-hover:shadow-brand-500/30 transition-all duration-300">
                    B
                </div>
                <div class="hidden sm:block">
                    <h1 class="font-serif text-xl font-bold leading-none tracking-tight text-onyx-900">Bhavani</h1>
                    <span class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 leading-none block">Crafts</span>
                </div>
            </a>

            <!-- Central Search Bar (Desktop) -->
            <div class="hidden lg:flex flex-1 max-w-2xl mx-12 h-12 relative group rounded-full overflow-hidden border border-gray-200 bg-gray-50/50 hover:bg-white hover:border-brand-200 transition-all duration-300 focus-within:ring-2 focus-within:ring-brand-500/20 focus-within:bg-white focus-within:border-brand-400">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-brand-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                
                <form action="{{ route('search') }}" method="GET" class="w-full h-full flex items-center xl:pl-44">
                    <!-- Category dropdown inside the form -->
                    <select name="category" class="absolute inset-y-0 left-12 w-32 bg-transparent text-xs font-semibold text-gray-500 border-none outline-none focus:ring-0 cursor-pointer hidden xl:flex items-center">
                        <option value="">All Categories</option>
                        @foreach(\App\Models\Category::whereHas('products', fn($q) => $q->where('listed_status',1))->get() as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-2 left-44 w-px bg-gray-200 hidden xl:block"></div>
                    
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Discover brass idols, corporate gifts, divine artifacts..." class="w-full h-full pl-12 xl:pl-4 pr-16 bg-transparent text-sm font-medium text-gray-900 border-none outline-none focus:ring-0 placeholder-gray-400">
                    <button type="submit" class="absolute inset-y-1.5 right-1.5 px-4 bg-brand-600 hover:bg-brand-500 text-white text-[11px] font-bold uppercase tracking-wider rounded-full transition-all duration-300 shadow-sm">
                        Search
                    </button>
                </form>
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
                    @php
                        $wishCount = Auth::check() ? \App\Models\Wishlist::where('user_id', Auth::id())->count() : 0;
                    @endphp
                    <span x-show="wishlistCount > 0" 
                          class="absolute top-0 right-0 h-4 w-4 bg-brand-500 text-white flex items-center justify-center text-[9px] font-bold rounded-full transform translate-x-1/2 -translate-y-1/4 border-2 border-white"
                          x-text="wishlistCount"
                          x-init="wishlistCount = {{ $wishCount }}">
                        {{ $wishCount }}
                    </span>
                </button>

                <!-- Cart Icon -->
                <button @click="cartOpen = true; activeTab = 'cart'; fetchCart()" class="relative p-2 text-gray-600 hover:text-brand-500 transition-colors rounded-full hover:bg-gray-50 flex items-center space-x-2 group">
                    <div class="relative">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        @php
                            $cartCount = 0;
                            if(Auth::check()) {
                                $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
                            } else {
                                $cartCount = \App\Models\CartItem::where('session_id', Session::getId())->sum('quantity');
                            }
                        @endphp
                        <span class="absolute top-0 right-0 h-4 w-4 bg-brand-600 group-hover:bg-brand-500 text-white flex items-center justify-center text-[9px] font-bold rounded-full transform translate-x-1/3 -translate-y-1/3 border-2 border-white transition-colors"
                              x-text="cartCount"
                              x-init="cartCount = {{ $cartCount }}">
                            {{ $cartCount }}
                        </span>
                    </div>
                </button>

                <!-- Divider -->
                <div class="w-px h-6 bg-gray-200 mx-2 hidden lg:block"></div>

                <!-- Auth Buttons -->
                <div class="hidden lg:flex items-center space-x-3">
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
                <form action="{{ route('search') }}" method="GET" class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search artifacts..." class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-4 pr-12 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-400">
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
                <a href="#" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Home</a>
                <a href="#" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Categories</a>
                <a href="#" class="text-white text-2xl font-serif hover:text-brand-400 transition-colors">Franchise Program</a>
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
    <main class="flex-1 wrapper flex flex-col w-full">
        @yield('content')
    </main>

    <!-- Public Footer -->
    <footer class="bg-onyx-900 text-white pt-20 pb-10 border-t-4 border-brand-500 mt-20 relative overflow-hidden">
        <!-- Subtle background pattern or blur -->
        <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 30px 30px;"></div>
        
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8 mb-16">
                <!-- Branding -->
                <div class="col-span-1 md:col-span-2 lg:col-span-1">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2 shrink-0 group mb-6">
                        <div class="h-10 w-10 bg-brand-500 text-onyx-900 rounded-xl flex items-center justify-center font-serif text-2xl font-bold shadow-lg shadow-brand-500/20">
                            B
                        </div>
                        <div>
                            <h1 class="font-serif text-xl font-bold leading-none tracking-tight text-white">Bhavani</h1>
                            <span class="text-[10px] font-black uppercase tracking-[3px] text-brand-500 leading-none block">Crafts</span>
                        </div>
                    </a>
                    <p class="text-sm text-gray-400 font-light leading-relaxed mb-6 max-w-sm">Exquisite handcrafted pooja articles, brass idols, and corporate gifts, forging heritage and devotion into premium artifacts for modern sanctuaries.</p>
                </div>
                
                <!-- Links -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[3px] text-brand-500 mb-6">Collections</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Brass Idols</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Pooja Mandirs</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Corporate Gifting</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">New Arrivals</a></li>
                    </ul>
                </div>

                <!-- Legal/Support -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[3px] text-brand-500 mb-6">Support</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Track Order</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Shipping & Returns</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h4 class="text-xs font-black uppercase tracking-[3px] text-brand-500 mb-6">The Sacred Digest</h4>
                    <p class="text-xs text-gray-400 leading-relaxed mb-4">Join our inner circle for early access to artisan limited releases.</p>
                    <form action="#" class="relative" data-no-loader>
                        <input type="email" placeholder="Email Address..." class="w-full bg-white/5 border border-white/10 rounded-xl py-3 pl-4 pr-12 text-sm text-white focus:outline-none focus:ring-2 focus:ring-brand-500/50 focus:border-brand-500 transition-all">
                        <button type="submit" class="absolute inset-y-1.5 right-1.5 px-3 bg-brand-500 hover:bg-brand-400 text-white rounded-lg transition-colors flex items-center justify-center">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row items-center justify-between">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-4 md:mb-0">&copy; {{ date('Y') }} Bhavani Crafts. All Rights Reserved.</p>
                <div class="flex items-center space-x-6">
                    <a href="#" class="text-gray-500 hover:text-brand-500 transition-colors">Instagram</a>
                    <a href="#" class="text-gray-500 hover:text-brand-500 transition-colors">Facebook</a>
                    <a href="#" class="text-gray-500 hover:text-brand-500 transition-colors">Pinterest</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('globalState', () => ({
                cartCount: 0,
                wishlistCount: 0,
                cartOpen: false,
                activeTab: 'cart',
                cartItems: [],
                wishlistItems: [],
                cartSubtotal: 0,
                notifications: [],
                mobileMenuOpen: false,
                searchOpen: false,
                _busy: false,   // per-drawer busy flag (lighter than full overlay)

                async fetchCart() {
                    const response = await fetch('{{ route('cart.index') }}');
                    const data = await response.json();
                    this.cartItems = data.items;
                    this.cartSubtotal = data.subtotal;
                    this.cartCount = data.count;
                },

                async fetchWishlist() {
                    const response = await fetch('{{ route('wishlist.index') }}');
                    const data = await response.json();
                    this.wishlistItems = data.items;
                    this.wishlistCount = data.count;
                },

                async moveToCart(productId) {
                    if (this._busy) return;
                    this._busy = true;
                    BcLoader.show('Moving to cart...');
                    try {
                        await fetch('{{ route('cart.add') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: JSON.stringify({ product_id: productId })
                        });
                        await fetch('{{ route('wishlist.toggle') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify({ product_id: productId })
                        });
                        await this.fetchCart();
                        await this.fetchWishlist();
                        this.activeTab = 'cart';
                        this.addNotification('Artifact moved to ritual cart 🛒');
                    } finally {
                        BcLoader.hide();
                        this._busy = false;
                    }
                },

                async removeFromWishlist(productId) {
                    if (this._busy) return;
                    this._busy = true;
                    BcLoader.show('Removing...');
                    try {
                        await fetch('{{ route('wishlist.toggle') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify({ product_id: productId })
                        });
                        await this.fetchWishlist();
                        this.addNotification('Removed from collection', 'info');
                    } finally {
                        BcLoader.hide();
                        this._busy = false;
                    }
                },

                async updateQuantity(itemId, newQty) {
                    if (this._busy) return;
                    if (newQty < 1) return this.removeFromCart(itemId);
                    this._busy = true;
                    BcLoader.show('Updating cart...');
                    try {
                        const response = await fetch('{{ route('cart.update') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ cart_item_id: itemId, quantity: newQty })
                        });
                        const data = await response.json();
                        this.cartItems = data.items;
                        this.cartSubtotal = data.subtotal;
                        this.cartCount = data.count;
                    } finally {
                        BcLoader.hide();
                        this._busy = false;
                    }
                },

                async removeFromCart(itemId) {
                    if (this._busy) return;
                    this._busy = true;
                    BcLoader.show('Removing item...');
                    try {
                        const response = await fetch('{{ route('cart.remove') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({ cart_item_id: itemId })
                        });
                        const data = await response.json();
                        this.cartItems = data.items;
                        this.cartSubtotal = data.subtotal;
                        this.cartCount = data.count;
                        this.addNotification('Artifact removed from cart', 'info');
                    } finally {
                        BcLoader.hide();
                        this._busy = false;
                    }
                },

                addNotification(message, type = 'success') {
                    const id = Date.now();
                    this.notifications.push({ id, message, type });
                    setTimeout(() => this.removeNotification(id), 5000);
                },

                removeNotification(id) {
                    this.notifications = this.notifications.filter(n => n.id !== id);
                }
            }))
        })
    </script>

    {{-- Page-specific scripts pushed from child views --}}
    @stack('scripts')

</body>
</html>
