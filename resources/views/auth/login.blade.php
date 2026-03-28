<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Bhavani Crafts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #ffffff; }
        h1, h2 { font-family: 'Playfair Display', serif; }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.7)), url('/assets/images/auth/hero_hd.png');
            background-size: cover;
            background-position: center;
        }

        /* Minimalist Blur Loader */
        .loader-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.01); /* Completely transparent */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(15px);
            display: none;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 3px solid #ff9933;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .input-luxury {
            border: 1.2px solid #e5e7eb;
            background: #fafafa;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-luxury:focus {
            border-color: #ff9933;
            background: white;
            box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.08);
            outline: none;
        }

        .btn-luxury {
            background: #ff9933;
            color: white;
            border: 1px solid #fb923c;
            letter-spacing: 2px;
            transition: all 0.4s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-luxury:hover {
            background: #fb8c00;
            box-shadow: 0 10px 25px rgba(255, 153, 51, 0.2);
            transform: translateY(-1px);
        }

        @media (min-width: 1024px) {
            .no-scroll-desktop { height: 100vh; overflow: hidden; }
        }
    </style>
</head>
<body class="no-scroll-desktop flex flex-col lg:flex-row min-h-screen">

    <!-- Normal Circular Loader -->
    <div id="masterLoader" class="loader-overlay">
        <span class="spinner"></span>
        <p id="loaderText" class="text-[#ff9933] mt-4 text-[10px] font-black tracking-[4px] uppercase animate-pulse">Please wait...</p>
    </div>

    <!-- Left: Immersive Artwork -->
    <div class="w-full lg:w-7/12 hero-section relative p-8 sm:p-12 lg:p-20 flex flex-col justify-between min-h-[40vh] lg:min-h-0">
        <div class="z-10">
            <div class="flex items-center space-x-6 mb-8 lg:mb-16">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#ff9933] flex items-center justify-center rounded-full shadow-2xl">
                    <span class="text-white text-xl lg:text-2xl font-black italic">B</span>
                </div>
                <div class="h-6 w-[1.5px] bg-white/30"></div>
                <h1 class="text-xl lg:text-2xl text-white tracking-[8px] font-bold uppercase">Bhavani Crafts</h1>
            </div>
            <div class="space-y-4 lg:space-y-8 max-w-2xl">
                <h2 class="text-4xl sm:text-6xl lg:text-7xl text-white font-light leading-tight tracking-tight">Welcome <br><span class="text-[#ff9933] italic">Back.</span></h2>
                <div class="w-16 lg:w-24 h-1 bg-[#ff9933]"></div>
                <p class="text-white/80 text-sm lg:text-lg font-light leading-relaxed max-w-md hidden sm:block">Access India's finest collection of handcrafted artifacts and pooja essentials.</p>
            </div>
        </div>
        
        <div class="z-10 flex gap-10 items-center opacity-60">
            <div class="text-white text-[9px] tracking-[6px] uppercase font-black">2026 Limited Edition</div>
            <div class="text-white text-[9px] tracking-[6px] uppercase font-black">Worldwide Artisans</div>
        </div>

        <div class="absolute inset-0 bg-black/40 lg:bg-transparent lg:bg-gradient-to-r lg:from-black/60 lg:via-transparent lg:to-transparent"></div>
    </div>

    <!-- Right: High-Contrast Login Area -->
    <div class="w-full lg:w-5/12 bg-white flex flex-col justify-center px-8 sm:px-16 lg:px-20 py-12 sm:py-20 relative">
        <div class="max-w-md mx-auto w-full">
            <div class="mb-10 lg:mb-16 text-center lg:text-left">
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 mb-2 leading-none">Sign In</h2>
                <p class="text-gray-400 font-bold uppercase tracking-[4px] text-[10px]">Access your account</p>
            </div>

            <!-- Password Form -->
            <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-6 lg:space-y-8">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase mb-3 block ml-1 leading-none">Email or Username</label>
                    <input type="text" name="email" required 
                           class="input-luxury w-full py-5 px-7 rounded-2xl text-gray-800 font-medium"
                           value="{{ old('email') }}" placeholder="Email or Username">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-3 px-1">
                        <label class="text-[10px] font-black text-gray-600 tracking-[3px] uppercase block leading-none">Password</label>
                    </div>
                    <input type="password" name="password" required 
                           class="input-luxury w-full py-5 px-7 rounded-2xl text-gray-800 font-medium"
                           placeholder="••••••••">
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-luxury w-full py-6 rounded-2xl font-black text-sm uppercase shadow-2xl">Sign In</button>
                </div>
            </form>

            <div class="text-center my-10 relative flex items-center">
                 <div class="flex-grow border-t border-gray-100"></div>
                 <span class="px-6 text-[9px] text-gray-300 font-black tracking-[8px] uppercase">OR</span>
                 <div class="flex-grow border-t border-gray-100"></div>
            </div>

            <a href="{{ route('google.login') }}" class="flex items-center justify-center space-x-3 px-8 py-5 border border-gray-100 rounded-2xl hover:border-orange-100 transition-all font-bold text-[10px] text-gray-500 uppercase tracking-[4px]">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5 grayscale opacity-70" alt="Google">
                <span>Sign-in with Google</span>
            </a>

            <p class="text-center text-sm text-gray-400 font-bold mt-12">
                New user? <a href="{{ route('register') }}" class="text-[#ff9933] hover:underline uppercase ml-2 tracking-tighter">Register here</a>
            </p>
        </div>

        <p class="mt-12 lg:absolute lg:bottom-10 lg:right-10 text-[9px] text-gray-300 font-black tracking-[8px] uppercase opacity-30 text-center lg:text-right">© 2026 BHAVANI CRAFTS</p>
    </div>

    <script>
        const masterLoader = document.getElementById('masterLoader');
        const loaderText = document.getElementById('loaderText');

        // Password Login
        document.getElementById('loginForm').onsubmit = async function(e) {
            e.preventDefault();
            loaderText.textContent = 'Verifying credentials...';
            masterLoader.style.display = 'flex';

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('api/auth/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (result.success) {
                    window.location.href = result.redirect;
                } else {
                    masterLoader.style.display = 'none';
                    alert(result.message || 'Authentication failed.');
                }
            } catch (error) {
                masterLoader.style.display = 'none';
                alert('Connection error.');
            }
        };
    </script>
</body>
</html>
