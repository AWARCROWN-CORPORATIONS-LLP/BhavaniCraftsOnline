<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email | Bhavani Crafts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Public Sans', sans-serif; height: 100vh; overflow: hidden; background: #f9f9f9; }
        .hero-bg { background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.3)), url('https://images.unsplash.com/photo-1582213796563-3bad15bcdd81?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; }
        .btn-premium { background: #f97316; transition: all 0.2s ease; }
        .btn-premium:hover { background: #ea580c; transform: translateY(-1px); }
        @keyframes fadeUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
    </style>
</head>
<body class="flex items-center justify-center p-6 hero-bg">

    <div class="max-w-md w-full bg-white rounded-[40px] p-10 shadow-2xl animate-fade-up relative overflow-hidden text-center">
        
        <!-- Decorative Saffron Circle -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-orange-100 rounded-full opacity-50"></div>
        
        <div class="mb-8 relative z-10">
            <div class="w-20 h-20 bg-orange-600 rounded-3xl flex items-center justify-center shadow-xl mx-auto mb-8">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-4">Verify Your Email</h2>
            <p class="text-gray-500 text-sm font-medium leading-relaxed">
                Thanks for joining Bhavani Crafts! Before we begin, please verify your email by clicking the link we just sent to you.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 text-xs font-bold uppercase tracking-widest rounded-2xl border border-green-100 italic">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4 relative z-10">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-5 btn-premium text-white rounded-2xl font-black text-sm shadow-xl uppercase tracking-[4px]">
                    Resend Link
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full py-4 text-gray-400 hover:text-gray-600 font-black text-[10px] uppercase tracking-[3px] transition-colors">
                    Logout
                </button>
            </form>
        </div>

        <!-- Trust Footer -->
        <div class="mt-10 pt-6 border-t border-gray-50 flex items-center justify-center space-x-4 opacity-40 grayscale">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/></svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Secure Verification</span>
        </div>
    </div>

</body>
</html>
