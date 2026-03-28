<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics Operative Terminal | Bhavani Crafts</title>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- TailwindCSS & Plugins -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,container-queries"></script>
    
    <!-- Theme Utilities & Tokens Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'Menlo', 'monospace'],
                    },
                    colors: {
                        'logistics-blue': '#0ea5e9',
                    }
                }
            }
        }
    </script>

    <!-- Global CSS -->
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>

<body class="bg-[#f8f9fa] text-gray-900 font-sans antialiased text-rendering-optimizeLegibility selection:bg-logistics-blue selection:text-white">

    <div class="min-h-screen flex flex-col">
        <!-- HEADER -->
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 p-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="h-10 w-10 flex-shrink-0 bg-logistics-blue flex items-center justify-center rounded-xl shadow-lg">
                    <span class="text-white text-xl font-black italic">B</span>
                </div>
                <div class="flex flex-col items-start leading-none">
                    <h2 class="text-gray-900 text-xs font-black uppercase tracking-[4px]">Field Agent</h2>
                    <span class="text-logistics-blue text-[9px] uppercase tracking-[3px] font-bold mt-1">Terminal</span>
                </div>
            </div>

            <div class="flex items-center space-x-6">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[9px] font-black text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors flex items-center space-x-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span class="hidden md:inline">Sign Out</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- MAIN TERMINAL CONTENT -->
        <main class="flex-grow p-4 md:p-10 mb-20 bg-gray-50/30">
            <div class="max-w-5xl mx-auto space-y-12">
                @if(session('success'))
                    <div class="bg-green-500/10 border border-green-500/20 text-green-700 p-6 rounded-2xl flex items-start space-x-4">
                        <svg class="h-6 w-6 text-green-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[4px] leading-none mb-2">Systems Nominal</p>
                            <p class="text-xs font-bold">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="bg-red-500/10 border border-red-500/20 text-red-700 p-6 rounded-2xl flex items-start space-x-4">
                        <svg class="h-6 w-6 text-red-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[4px] leading-none mb-2">Security Exception</p>
                            <p class="text-xs font-bold">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>
