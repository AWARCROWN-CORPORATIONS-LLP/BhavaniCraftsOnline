<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhavani Admin | Elite Sacred Commerce</title>

    <!-- UI Core -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,wght@0,400;0,700;1,400;1,700&family=Outfit:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Tailwind Config Customizations -->
    <style>
        body { font-family: 'Outfit', sans-serif; background: #fbfbfc; color: #1e293b; font-size: 14px; -webkit-font-smoothing: antialiased; }
        h1, h2, h3, .heading-silk { font-family: 'Bodoni Moda', serif; }
        p, span, td, th, li, label, input, select, textarea { font-family: 'Outfit', sans-serif; color: inherit; }
        /* Boost base text legibility across all table cells and content areas */
        td { color: #1e293b; }
        th { color: #475569; font-weight: 700; }
        
        .sidebar-silk {
            background: #111111; /* Matte Elite Black */
            border-right: 1px solid rgba(255,255,255,0.05);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item-active {
            background: rgba(255, 153, 51, 0.1);
            color: #ff9933;
            border-right: 3px solid #ff9933;
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
            background: #ff9933;
            color: white;
            border-radius: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s;
        }

        .btn-luxury-saffron:hover {
            background: #fb8c00;
            box-shadow: 0 10px 20px rgba(255, 153, 51, 0.2);
            transform: scale(1.02);
        }

        /* Beautiful Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        .revenue-badge {
            background: linear-gradient(135deg, #ff9933 0%, #ff5e00 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
        }
    </style>
</head>
<body x-data="{ sidebarOpen: true }" class="min-h-screen flex bg-gray-50 overflow-hidden">

    <!-- SIDEBAR: Elite Core -->
    <aside 
        :class="sidebarOpen ? 'w-64' : 'w-20'" 
        class="sidebar-silk h-screen flex flex-col z-50 sticky top-0 flex-shrink-0 transition-all duration-300">
        
        <!-- Logo Section -->
        <div class="p-6 flex items-center mb-10 overflow-hidden min-h-[100px]">
            <div class="h-10 w-10 flex-shrink-0 bg-[#ff9933] flex items-center justify-center rounded-xl shadow-2xl">
                <span class="text-white text-xl font-black italic">B</span>
            </div>
            <div x-show="sidebarOpen" class="ml-4 flex flex-col items-start whitespace-nowrap">
                <h2 class="text-white text-xs font-black uppercase tracking-[4px] leading-none">Bhavani</h2>
                <span class="text-[#ff9933] text-[10px] uppercase tracking-[3px] font-bold mt-1">Admin Silk</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-grow space-y-2 px-3 overflow-y-auto">
            <p x-show="sidebarOpen" class="text-white/40 text-[10px] font-black uppercase tracking-[4px] px-6 mb-4">Core Portal</p>
            
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.dashboard') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Dashboard</span>
            </a>

            <a href="{{ route('admin.franchises') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.franchises') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Franchise Mastery</span>
            </a>

            <a href="{{ route('admin.users') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.users') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11a1 1 0 100-2 1 1 0 000 2z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Seeker Registry</span>
            </a>

            @if(Auth::user()->hasRole('super_admin'))
                <a href="{{ route('superadmin.employees.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('superadmin.employees.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                    <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Employee Access</span>
                </a>
            @endif

            <a href="{{ route('admin.broadcasts.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.broadcasts.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Global Broadcast</span>
            </a>

            <a href="{{ route('admin.page-content.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.page-content.index') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Page Content</span>
            </a>

            <p x-show="sidebarOpen" class="text-white/40 text-[10px] font-black uppercase tracking-[4px] px-6 mt-10 mb-4">Catalog Silk</p>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.categories.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m12 4a2 2 0 100-4m0 4a2 2 0 110-4m-6 0a2 2 0 100 4m0-4a2 2 0 110 4m-6 0v-2m8 4v-2a2 2 0 110 4m-6 0v2m8 4v-2" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Category Registry</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.products.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Products Mastery</span>
            </a>

            <a href="{{ route('admin.orders.kanban') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.orders.kanban') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Kanban Fulfillment</span>
            </a>

            <a href="{{ route('admin.restocks.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.restocks.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">HQ Partner Restocks</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.show') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Order Registry</span>
            </a>

            <a href="{{ route('admin.coupons.index') }}" class="flex items-center space-x-4 p-4 rounded-xl transition-all hover:bg-white/5 {{ request()->routeIs('admin.coupons.*') ? 'nav-item-active' : 'text-white/70 hover:text-white' }}">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                <span x-show="sidebarOpen" class="font-bold text-sm tracking-widest text-[10px] uppercase">Divine Coupons</span>
            </a>
        </nav>

        <!-- Logout / Footer -->
        <div class="p-6 border-t border-white/5">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center space-x-3 text-red-400 font-bold uppercase tracking-widest text-[10px] hover:text-red-300 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span x-show="sidebarOpen">Log out</span>
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
                @yield('header_extra')
            </div>

            <div class="flex items-center space-x-6">
                <div class="text-right hidden sm:block">
                    <p class="text-[11px] font-black text-gray-900 uppercase tracking-[2px]">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-bold text-[#ff9933] uppercase tracking-[3px]">Elite Admin</p>
                </div>
                <div class="h-10 w-10 rounded-full border-2 border-[#ff9933] p-0.5 shadow-xl">
                    <div class="h-full w-full rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 uppercase">{{ substr(Auth::user()->name, 0, 1) }}</div>
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
                        <p class="text-red-700 text-sm font-black uppercase tracking-widest">Registry Synchronization Errors</p>
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
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[6px]">© 2026 Bhavani Crafts Portal</p>
            <div class="flex space-x-8 mt-4 md:mt-0 opacity-30 grayscale contrast-125">
                 <div class="h-4 w-4 bg-gray-400 rotate-45"></div>
                 <div class="h-4 w-4 bg-gray-400 rotate-45"></div>
                 <div class="h-4 w-4 bg-gray-400 rotate-45"></div>
            </div>
        </footer>
    </main>

</body>
</html>
