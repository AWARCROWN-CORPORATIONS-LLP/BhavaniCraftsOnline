<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Partner Core | Bhavani Crafts</title>

    <!-- UI Core -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind Config Customizations -->
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; font-size: 14px; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, .heading-silk { font-family: 'Inter', sans-serif; font-weight: 800; }
        p, span, td, th, li, label, input, select, textarea { color: inherit; }
        td { color: #1e293b; }
        th { color: #475569; font-weight: 700; }
        
        .sidebar-silk {
            background: #111111; /* Matte Elite Black */
            border-right: 1px solid rgba(255,255,255,0.05);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item-active {
            background: rgba(30, 64, 175, 0.1);
            color: #1e40af;
            border-right: 3px solid #1e40af;
        }

        .card-premium {
            background: white;
            border: 1px solid #f1f1f1;
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
            border-radius: 20px;
            transition: transform 0.3s;
        }

        .card-premium:hover { transform: translateY(-5px); }

        .glass-silk {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid #f1f1f1;
        }

        .btn-luxury-saffron {
            background: #1e40af;
            color: white;
            border-radius: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s;
        }

        .btn-luxury-saffron:hover {
            background: #1e3a8a;
            box-shadow: 0 10px 20px rgba(30, 64, 175, 0.2);
            transform: scale(1.02);
        }

        /* Beautiful Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }
    </style>
</head>
<body x-data="{ sidebarOpen: true }" class="min-h-screen flex bg-gray-50 overflow-hidden">

    <!-- SIDEBAR: Partner Hub -->
    <aside 
        :class="sidebarOpen ? 'w-64' : 'w-20'" 
        class="sidebar-silk h-screen flex flex-col z-50 sticky top-0 flex-shrink-0 transition-all duration-300">
        
        <!-- Logo Section -->
        <div class="p-6 flex items-center mb-10 overflow-hidden min-h-[100px]">
            <a href="{{ route('home') }}" class="flex items-center group">
                <div class="h-10 w-auto group-hover:scale-105 transition-all duration-300">
                    <img src="{{ $siteLogo }}" alt="Bhavani Crafts" class="h-full w-auto object-contain brightness-0 invert shadow-2xl">
                </div>
                <div x-show="sidebarOpen" class="ml-4 flex flex-col items-start whitespace-nowrap">
                    <h2 class="text-white text-xs font-black uppercase tracking-[4px] leading-none">Bhavani</h2>
                    <span class="text-[#1e40af] text-[10px] uppercase tracking-[3px] font-bold mt-1">Partner Hub</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-grow space-y-2 px-3 overflow-y-auto">
            <p x-show="sidebarOpen" class="text-white/40 text-[10px] font-black uppercase tracking-[4px] px-6 mb-4">Business Portal</p>
            
            <a href="{{ route('franchise.dashboard') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('franchise.dashboard') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Overview</span>
            </a>

            <a href="{{ route('franchise.catalog') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('franchise.catalog') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Wholesale Shop</span>
            </a>

            <a href="{{ route('franchise.inventory') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('franchise.inventory*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">My Inventory</span>
            </a>

            <a href="{{ route('franchise.restock.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('franchise.restock.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">HQ Restock</span>
            </a>

            <a href="{{ route('franchise.payment.verify.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('franchise.payment.verify.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Verify Payments</span>
            </a>

            <p x-show="sidebarOpen" class="text-white/40 text-[10px] font-black uppercase tracking-[4px] px-6 mt-10 mb-4">Orders &amp; Supply</p>

            <a href="#" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 text-white/50 hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Supply History</span>
            </a>
            
            <a href="#" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 text-white/50 hover:text-white">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">My Invoices</span>
            </a>
        </nav>

        <!-- Logout / Footer -->
        <div class="p-6 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center space-x-3 text-red-400 font-bold uppercase tracking-widest text-[10px] hover:text-red-300 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span x-show="sidebarOpen">Secure Exit</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN: Dynamic Core -->
    <main class="flex-grow flex flex-col h-screen overflow-y-auto">
        <!-- TOP NAV: Glass UI -->
        <header class="glass-silk sticky top-0 z-40 px-8 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-gray-100 rounded-lg text-gray-400 transition-all">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="h-6 w-[1.5px] bg-gray-100"></div>

                <!-- UNIVERSAL SEARCH ENGINE (GraphQL Powered) -->
                <div x-data="{ 
                    search: '', 
                    results: [], 
                    loading: false,
                    async performSearch() {
                        if (this.search.length < 2) { this.results = []; return; }
                        this.loading = true;
                        const query = `query UniversalSearch($q: String!) {
                            universalSearch(q: $q) {
                                title subtitle type url image
                            }
                        }`;
                        try {
                            const response = await fetch('/graphql', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                body: JSON.stringify({ query, variables: { q: this.search } })
                            });
                            const data = await response.json();
                            this.results = data.data.universalSearch;
                        } catch (e) { console.error(e); }
                        finally { this.loading = false; }
                    }
                }" class="relative flex-grow max-w-md ml-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg :class="loading ? 'animate-spin text-[#1e40af]' : 'text-gray-400 group-focus-within:text-[#1e40af]'" class="h-4 w-4 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path v-if="!loading" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            x-model="search" 
                            @input.debounce.300ms="performSearch()"
                            @click.away="results = []"
                            placeholder="Universal Registry Search..." 
                            class="w-full bg-gray-50 border-none pl-12 pr-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-[#1e40af]/20 transition-all placeholder:text-gray-300">
                    </div>

                    <!-- Search Results Dropdown -->
                    <div x-show="results.length > 0" x-transition class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 py-2">
                        <template x-for="result in results" :key="result.url">
                            <a :href="result.url" class="flex items-center px-6 py-4 hover:bg-gray-50 transition-colors group">
                                <div class="h-8 w-8 bg-gray-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-gray-100">
                                    <template x-if="result.image">
                                        <img :src="result.image" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!result.image">
                                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-tighter" x-text="result.type.substring(0,1)"></span>
                                    </template>
                                </div>
                                <div class="ml-4 flex-grow">
                                    <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest leading-none" x-text="result.title"></p>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1" x-text="result.subtitle || result.type"></p>
                                </div>
                                <svg class="h-4 w-4 text-gray-300 group-hover:text-[#1e40af] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </template>
                    </div>
                </div>

                @yield('header_extra')
            </div>

            <div class="flex items-center space-x-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-gray-900 uppercase tracking-[2px]">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-[#1e40af] uppercase tracking-[3px]">Partner Member</p>
                </div>
                <div class="h-10 w-10 rounded-xl border-2 border-[#1e40af] p-0.5 shadow-xl">
                    <div class="h-full w-full rounded-lg bg-gray-100 flex items-center justify-center font-bold text-gray-500 uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="p-8 lg:p-12 animate-in fade-in duration-700">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.duration.500ms class="mb-8 p-6 bg-green-50 border-l-4 border-green-500 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="h-10 w-10 bg-green-500/20 text-green-600 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <p class="text-green-700 text-sm font-bold uppercase tracking-widest">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-2xl">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="h-10 w-10 bg-red-500/20 text-red-600 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <p class="text-red-700 text-sm font-black uppercase tracking-widest">Inventory Conflict Detected</p>
                    </div>
                    <ul class="list-disc list-inside text-red-600 text-[11px] font-bold uppercase tracking-wider space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="mt-auto p-12 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between">
            <p class="text-[10px] text-gray-300 font-bold uppercase tracking-[8px]">© 2026 Bhavani Partner Registry</p>
            <div class="flex space-x-8 mt-4 md:mt-0 opacity-30 grayscale contrast-125">
                 <div class="h-4 w-4 bg-gray-400 rotate-45"></div>
                 <div class="h-4 w-4 bg-gray-400 rotate-45"></div>
                 <div class="h-4 w-4 bg-gray-400 rotate-45"></div>
            </div>
        </footer>
    </main>

    @stack('scripts')
</body>
</html>
