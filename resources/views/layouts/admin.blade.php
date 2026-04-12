<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhavani Admin | Store Admin</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- UI Core & Enterprise Theme Logic -->
    <script>
        tailwind = {
            theme: {
                extend: {
                    colors: {
                        'brand-primary': '#ff9933',
                        'brand-dark': '#0f172a'
                    }
                }
            }
        }
    </script>
    <!-- Core Frameworks -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Speed Boosters -->
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
    <script src="//instant.page/5.2.0" type="module" integrity="sha384-jnZyxPjiipYXnSU0ygqeac2q7CVYMbh84q0uHVRRxEtvFPiQYbXWUorga2aqZJ0z"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>

    <!-- Tailwind Config Customizations & Enterprise Design System -->
    <style>
        :root {
            --brand-primary: #ff9933;
            --brand-dark: #0f172a;
            --surface-50: #f8fafc;
            --surface-100: #f1f5f9;
            --surface-200: #e2e8f0;
            --border-subtle: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body { 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
            background: var(--surface-50); 
            color: var(--text-main); 
            font-size: 13px; 
            -webkit-font-smoothing: antialiased; 
        }

        /* Enterprise Sidebar - Structural & Lean */
        .sidebar-silk {
            background: #ffffff;
            border-right: 1px solid var(--border-subtle);
            transition: width 0.2s ease;
        }

        .nav-item {
            color: var(--text-muted);
            border-left: 3px solid transparent;
            transition: all 0.1s ease;
            font-weight: 500;
        }

        .nav-item:hover {
            color: var(--brand-primary);
            background: #fff9f5;
        }

        .nav-item-active {
            color: var(--brand-primary);
            background: #fff9f5;
            border-left: 3px solid var(--brand-primary);
            font-weight: 700;
        }

        /* Precision Cards - Flatter, Border-focused */
        .card-premium {
            background: white;
            border: 1px solid var(--border-subtle);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: border-color 0.2s ease;
        }

        .card-premium:hover {
            border-color: var(--brand-primary);
        }

        /* Modern Typography Hierarchies */
        .heading-silk { font-weight: 700; letter-spacing: -0.025em; color: var(--brand-dark); }
        .label-muted { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); }

        /* Enterprise Buttons */
        .btn-luxury-saffron {
            background: var(--brand-dark);
            color: white;
            border-radius: 6px;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .btn-luxury-saffron:hover {
            background: #000;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .glass-silk {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border-subtle);
        }

        /* Data Grids */
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { background: var(--surface-100); padding: 12px 16px; text-align: left; font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; border-bottom: 1px solid var(--border-subtle); }
        td { padding: 14px 16px; border-bottom: 1px solid var(--surface-100); vertical-align: middle; }
        
        /* ── Global Loading Overlay ───────────────────────── */
        #bc-loading-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        #bc-loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
        .bc-spinner {
            width: 32px;
            height: 32px;
            border: 2px solid rgba(255, 153, 51, 0.1);
            border-top-color: #ff9933;
            border-radius: 50%;
            animation: bc-spin 0.6s linear infinite;
        }
        @keyframes bc-spin { to { transform: rotate(360deg); } }

        /* Disable interactions on page during loading */
        body.bc-busy { cursor: wait !important; overflow: hidden !important; }
        body.bc-busy * { pointer-events: none !important; }
        body.bc-busy #bc-loading-overlay, body.bc-busy #bc-loading-overlay * { pointer-events: all !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        
        /* Smooth Page Transitions */
        @keyframes fadeInContent {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .page-transition {
            animation: fadeInContent 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body x-data="{ sidebarOpen: true }" class="h-screen flex bg-gray-50 overflow-hidden">

    {{-- ── Global Loading Overlay ───────────────────────────────────────── --}}
    <div id="bc-loading-overlay">
        <div class="text-center">
            <div class="bc-spinner mx-auto mb-5"></div>
            <p id="bc-loading-msg" class="text-white text-[10px] font-black uppercase tracking-[3px] opacity-80">Saving...</p>
        </div>
    </div>

    <script>
        window.BcLoader = {
            overlay: null, msg: null,
            init() { 
                this.overlay = document.getElementById('bc-loading-overlay'); 
                this.msg = document.getElementById('bc-loading-msg'); 
            },
            show(message = 'Processing...') {
                if (!this.overlay) this.init();
                this.msg.textContent = message;
                this.overlay.classList.add('active');
                document.body.classList.add('bc-busy');
                NProgress.start();
            },
            hide() {
                if (!this.overlay) return;
                this.overlay.classList.remove('active');
                document.body.classList.remove('bc-busy');
                NProgress.done();
            }
        };

        // NProgress Configuration
        NProgress.configure({ showSpinner: false, trickleSpeed: 200 });

        // Global Navigation Lifecycle (Turbo-Aware)
        document.addEventListener('turbo:visit', () => BcLoader.show('Loading...'));
        document.addEventListener('turbo:load', () => BcLoader.hide());
        document.addEventListener('turbo:submit-start', () => BcLoader.show('Processing...'));
        document.addEventListener('turbo:before-cache', () => BcLoader.hide());
        
        // Fallback for non-turbo Loads
        window.addEventListener('load', () => BcLoader.hide());
    </script>


    <!-- SIDEBAR: Elite Core -->
    <aside 
        :class="sidebarOpen ? 'w-64' : 'w-20'" 
        class='sidebar-silk h-screen flex flex-col z-50 sticky top-0 flex-shrink-0 transition-all duration-300'>
        
        <!-- Logo Section -->
        <div class='px-6 py-8 flex items-center mb-4 overflow-hidden min-h-[80px]'>
            <a href='{{ route('home') }}' class='flex items-center'>
                <div class='h-10 w-auto'>
                    <img src='{{ $siteLogo }}' alt='Bhavani Crafts' class='h-full w-auto object-contain'>
                </div>
                <div x-show='sidebarOpen' class='ml-3'>
                    <span class='text-[10px] font-black uppercase tracking-[2px] text-brand-dark block'>Bhavani</span>
                    <span class='text-[8px] font-bold uppercase tracking-[3px] text-muted block -mt-1'>Admin OS</span>
                </div>
            </a>
        </div>

        <!-- Navigation -->
        <nav class='flex-grow space-y-1 px-2 overflow-y-auto'>
            <p x-show='sidebarOpen' class='label-muted px-4 mb-2 mt-4'>Platform Control</p>
            
            @php
                $dashboardRoute = Auth::user()->hasRole('super_admin') ? 'superadmin.dashboard' : 'admin.dashboard';
            @endphp
            <a href='{{ route($dashboardRoute) }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('*.dashboard') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Dashboard</span>
            </a>

            <a href='{{ route('admin.billing.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.billing.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Billing</span>
            </a>

            <a href='{{ route('admin.orders.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.orders.*') && !request()->routeIs('admin.orders.kanban') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6h16M4 10h16M4 14h16M4 18h16' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Orders</span>
            </a>

            <a href='{{ route('admin.orders.kanban') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.orders.kanban') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Workflow</span>
            </a>

            <a href='{{ route('admin.payment.verify.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.payment.verify.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Verify Payments</span>
            </a>

            <a href='{{ route('admin.franchises') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.franchises') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Franchise Management</span>
            </a>

            <a href='{{ route('admin.franchise-applications.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.franchise-applications.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Partner Applications</span>
            </a>

            <a href='{{ route('admin.corporate-requests.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.corporate-requests.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 13.255A2.358 2.358 0 0119.354 15.4L12 18.2l-7.354-2.8a2.358 2.358 0 01-1.646-2.145V6.2a2.358 2.358 0 011.646-2.145L12 1.255l7.354 2.8A2.358 2.358 0 0121 6.2v7.055z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 22.745V18.2M7 8h10M7 12h10' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Corporate Requests</span>
            </a>

            <a href='{{ route('admin.users') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.users') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4.354l.586.586H19v10.354L12.586 16H4V4.94L11.414 4H12zM12 11h.01' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 11a1 1 0 100-2 1 1 0 000 2z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>User Management</span>
            </a>

            <a href='{{ route('admin.branches.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.branches.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z' /><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M15 11a3 3 0 11-6 0 3 3 0 016 0z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Branches</span>
            </a>

            @if(Auth::user()->hasRole('super_admin'))
                <a href='{{ route('admin.audit.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.audit.index') ? 'nav-item-active' : '' }}">
                    <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4' /></svg>
                    <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Activity Logs</span>
                </a>
                <a href='{{ route('superadmin.employees.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('superadmin.employees.*') ? 'nav-item-active' : '' }}">
                    <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2' /></svg>
                    <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Staff Access</span>
                </a>
            @endif

            <a href='{{ route('shared.logistics.personnel.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('shared.logistics.personnel.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Delivery Agents</span>
            </a>

            <a href='{{ route('admin.broadcasts.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.broadcasts.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Announcements</span>
            </a>

            <a href='{{ route('admin.page-content.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.page-content.index') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Design Pages</span>
            </a>

            <a href='{{ route('admin.coupons.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.coupons.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Coupons</span>
            </a>

            <p x-show='sidebarOpen' class='label-muted px-4 mb-2 mt-8'>Inventory Engine</p>

            <a href='{{ route('admin.categories.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.categories.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m12 4a2 2 0 100-4m0 4a2 2 0 110-4m-6 0a2 2 0 100 4m0-4a2 2 0 110 4m-6 0v-2m8 4v-2a2 2 0 110 4m-6 0v2m8 4v-2' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Categories</span>
            </a>

            <a href='{{ route('admin.products.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.products.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Products</span>
            </a>
            
            <a href='{{ route('admin.ritual-kits.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.ritual-kits.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 011-1h1a2 2 0 100-4H7a1 1 0 01-1-1V7a1 1 0 011-1h3a1 1 0 001-1V4z' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Ritual Kits</span>
            </a>

            <a href='{{ route('admin.restocks.index') }}' class="nav-item flex items-center space-x-3 px-4 py-3 rounded-md transition-all {{ request()->routeIs('admin.restocks.*') ? 'nav-item-active' : '' }}">
                <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10' /></svg>
                <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Restock Management</span>
            </a>
        </nav>

        <!-- Logout / Footer -->
        <div class='p-4 border-t border-slate-50 mt-auto'>
            <form action='{{ route('logout') }}' method='POST'>
                @csrf
                <button class='nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-md transition-all text-rose-500 hover:bg-rose-50 hover:text-rose-600'>
                    <svg class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1' /></svg>
                    <span x-show='sidebarOpen' class='text-[11px] uppercase tracking-wider'>Log out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN: Dynamic Core -->
    <main class='flex-1 overflow-x-hidden overflow-y-auto bg-[#f8fafc] custom-scrollbar'>
        <!-- TOP NAV: Glass UI -->
        <header class='glass-silk sticky top-0 z-40 px-8 py-4 flex items-center justify-between'>
            <div class='flex items-center space-x-6'>
                <button @click='sidebarOpen = !sidebarOpen' class='p-2 hover:bg-gray-100 rounded-lg text-gray-400 transition-all'>
                    <svg class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 6h16M4 12h16M4 18h16' /></svg>
                </button>
                <div class='h-6 w-[1.5px] bg-gray-100'></div>

                <!-- UNIVERSAL SEARCH ENGINE (GraphQL Powered) -->
                <div x-data="{ 
                    search: '', 
                    results: [], 
                    loading: false,
                    async performSearch() {
                        if (this.search.length < 2) { this.results = []; return; }
                        this.loading = true;
                        try {
                            const resp = await fetch('/graphql', {
                                method: 'POST',
                                headers: { 
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content
                                },
                                body: JSON.stringify({ 
                                    query: 'query UniversalSearch($q: String!) { universalSearch(q: $q) { title subtitle type url image } }',
                                    variables: { q: this.search } 
                                })
                            });
                            const body = await resp.json();
                            this.results = body.data?.universalSearch || [];
                        } catch (e) { 
                            this.results = [];
                        } finally { 
                            this.loading = false; 
                        }
                    }
                }"
                @keydown.escape="search = ''; results = []"
                @click.away="results = []"
                class="relative flex-grow max-w-md ml-4">
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 transition-colors" :class="loading ? 'animate-spin text-brand-primary' : 'text-gray-400 group-focus-within:text-brand-primary'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!loading" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                <path x-show="loading" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            x-model="search" 
                            @input.debounce.300ms="performSearch()"
                            placeholder="Universal Search..." 
                            class="w-full bg-gray-50 border-none pl-12 pr-10 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest focus:ring-2 focus:ring-brand-primary/20 transition-all placeholder:text-gray-300">
                        
                        <button x-show="search.length > 0" @click="search = ''; results = []" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-300 hover:text-rose-500 transition-colors">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
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
                                <div class='ml-4 flex-grow'>
                                    <p class='text-[10px] font-black text-gray-900 uppercase tracking-widest leading-none' x-text='result.title'></p>
                                    <p class='text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-1' x-text='result.subtitle || result.type'></p>
                                </div>
                                <svg class='h-4 w-4 text-gray-300 group-hover:text-[#1e40af] transition-colors' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5l7 7-7 7' /></svg>
                            </a>
                        </template>
                    </div>
                </div>

                @yield('header_extra')
            </div>

            <div class='flex items-center space-x-6'>
                <div class='text-right hidden sm:block'>
                    <p class='text-[11px] font-black text-gray-900 uppercase tracking-[2px]'>{{ Auth::user()->name }}</p>
                    <p class='text-[10px] font-bold text-[#1e40af] uppercase tracking-[3px]'>Administrator</p>
                </div>
                <div class='h-10 w-10 rounded-full border-2 border-[#1e40af] p-0.5 shadow-xl'>
                    <div class='h-full w-full rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500 uppercase'>{{ substr(Auth::user()->name, 0, 1) }}</div>
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="p-4 md:p-6 lg:p-10 page-transition w-full max-w-[100%]">

            @if(session('success'))
                <div x-data='{ show: true }' x-show='show' x-transition.duration.500ms class='mb-8 p-6 bg-green-50 border-l-4 border-green-500 rounded-2xl flex items-center justify-between'>
                    <div class='flex items-center space-x-4'>
                        <div class='h-10 w-10 bg-green-500/20 text-green-600 rounded-full flex items-center justify-center'>
                            <svg class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7' /></svg>
                        </div>
                        <p class='text-green-700 text-sm font-bold uppercase tracking-widest'>{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class='mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-2xl'>
                    <div class='flex items-center space-x-4 mb-4'>
                        <div class='h-10 w-10 bg-red-500/20 text-red-600 rounded-full flex items-center justify-center'>
                            <svg class='h-6 w-6' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' /></svg>
                        </div>
                        <p class='text-red-700 text-sm font-black uppercase tracking-widest'>Error Processing Request</p>
                    </div>
                    <ul class='list-disc list-inside text-red-600 text-[11px] font-bold uppercase tracking-wider space-y-1'>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class='mt-auto p-12 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between'>
            <p class='text-[10px] text-gray-400 font-bold uppercase tracking-[6px]'>© 2026 Bhavani Crafts</p>
            <div class='flex space-x-8 mt-4 md:mt-0 opacity-30 grayscale contrast-125'>
                 <div class='h-4 w-4 bg-gray-400 rotate-45'></div>
                 <div class='h-4 w-4 bg-gray-400 rotate-45'></div>
                 <div class='h-4 w-4 bg-gray-400 rotate-45'></div>
            </div>
        </footer>
    </main>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js');
            });
        }
    </script>
</body>
</html>
