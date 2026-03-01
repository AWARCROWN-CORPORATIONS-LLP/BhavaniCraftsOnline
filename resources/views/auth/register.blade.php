<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join the Tradition | Bhavani Crafts</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #ffffff; }
        h1, h2 { font-family: 'Playfair Display', serif; }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('/assets/images/auth/hero_hd.png');
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

        .peer:checked + .tab-luxury {
            color: white;
            background: #ff9933;
            box-shadow: 0 4px 12px rgba(255, 153, 51, 0.2);
            border-color: #ff9933;
        }

        @media (min-width: 1024px) {
            .no-scroll-desktop { height: 100vh; overflow: hidden; }
        }
    </style>
</head>
<body class="no-scroll-desktop flex flex-col lg:flex-row min-h-screen" x-data="{ userType: 'individual', passwordType: 'password' }">

    <!-- Normal Circular Loader -->
    <div id="masterLoader" class="loader-overlay">
        <span class="spinner"></span>
        <p id="loaderText" class="text-[#ff9933] mt-4 text-[10px] font-black tracking-[4px] uppercase animate-pulse">Syncing</p>
    </div>

    <!-- Left: Artistic Section -->
    <div class="w-full lg:w-5/12 hero-section relative p-8 sm:p-12 lg:p-16 flex flex-col justify-between min-h-[40vh] lg:min-h-0">
        <div class="z-10 animate-in fade-in slide-in-from-left duration-1000">
            <div class="flex items-center space-x-6 mb-8 lg:mb-12">
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-[#ff9933] flex items-center justify-center rounded-full shadow-2xl">
                    <span class="text-white text-xl lg:text-2xl font-black italic">B</span>
                </div>
                <div class="h-6 w-[1.5px] bg-white/30"></div>
                <h1 class="text-xl lg:text-3xl text-white tracking-[6px] font-bold uppercase">Bhavani Crafts</h1>
            </div>
            <div class="space-y-4 lg:space-y-6">
                <h2 class="text-3xl sm:text-5xl lg:text-6xl text-white font-light leading-tight tracking-tight">Join the <br><span class="text-[#ff9933] italic">Tradition.</span></h2>
                <div class="w-16 lg:w-24 h-1 bg-[#ff9933]"></div>
                <p class="text-white/70 text-sm sm:text-lg max-w-sm font-light leading-relaxed hidden sm:block">Become a part of India's most exclusive sacred artifacts community.</p>
            </div>
        </div>
        
        <div class="z-10 opacity-60">
            <div class="text-white text-[9px] tracking-[6px] uppercase font-black">2026 Limited Edition</div>
        </div>

        <div class="absolute inset-0 bg-black/40 lg:bg-transparent lg:bg-gradient-to-r lg:from-black/80 lg:via-transparent lg:to-transparent"></div>
    </div>

    <!-- Right: High-End Form Area -->
    <div class="w-full lg:w-7/12 bg-white flex flex-col justify-center px-6 sm:px-16 lg:px-24 py-12 lg:py-16 relative overflow-y-auto">
        <div class="max-w-2xl mx-auto w-full">
            <div class="mb-10 text-center lg:text-left">
                <h2 class="text-4xl lg:text-5xl font-black text-gray-900 mb-2">Registration</h2>
                <p class="text-gray-400 font-bold uppercase tracking-[4px] text-[10px]">Create your sacred portal</p>
            </div>

            <form id="regForm" action="{{ route('register') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="flex p-1.5 bg-gray-100 rounded-2xl mb-8 max-w-md mx-auto lg:mx-0 shadow-inner">
                    <label class="w-1/2 cursor-pointer">
                        <input type="radio" name="user_type" value="individual" class="hidden peer" checked x-on:change="userType = 'individual'">
                        <div class="tab-luxury py-3 text-center rounded-xl text-[10px] font-black tracking-widest text-gray-400 uppercase transition-all">Individual</div>
                    </label>
                    <label class="w-1/2 cursor-pointer">
                        <input type="radio" name="user_type" value="business" class="hidden peer" x-on:change="userType = 'business'">
                        <div class="tab-luxury py-3 text-center rounded-xl text-[10px] font-black tracking-widest text-gray-400 uppercase transition-all">Franchise</div>
                    </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 tracking-[3px] uppercase block ml-1">Full Name</label>
                        <input type="text" name="name" required class="input-luxury w-full py-4 px-6 rounded-2xl text-gray-800 font-medium" placeholder="Ex: Aditya Manikanta">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 tracking-[3px] uppercase block ml-1">Email Address</label>
                        <input type="email" name="email" required class="input-luxury w-full py-4 px-6 rounded-2xl text-gray-800 font-medium" placeholder="name@domain.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 tracking-[3px] uppercase block ml-1">Unique Username</label>
                        <input type="text" name="username" required class="input-luxury w-full py-4 px-6 rounded-2xl text-gray-800 font-medium" placeholder="@aditya">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 tracking-[3px] uppercase block ml-1">WhatsApp Mobile</label>
                        <input type="text" name="phone" required class="input-luxury w-full py-4 px-6 rounded-2xl text-gray-800 font-medium" placeholder="+91 9676...">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 tracking-[3px] uppercase block ml-1">Create Password</label>
                        <div class="relative">
                            <input :type="passwordType" name="password" required class="input-luxury w-full py-4 px-6 rounded-2xl text-gray-800 font-medium" placeholder="••••••••">
                            <button type="button" @click="passwordType = (passwordType === 'password' ? 'text' : 'password')" class="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-500 tracking-[3px] uppercase block ml-1">Verify Password</label>
                        <input :type="passwordType" name="password_confirmation" required class="input-luxury w-full py-4 px-6 rounded-2xl text-gray-800 font-medium" placeholder="••••••••">
                    </div>
                </div>

                <div x-show="userType === 'business'" x-transition class="bg-gray-50 p-4 rounded-2xl border-l-4 border-[#ff9933]">
                    <p class="text-gray-900 text-[10px] font-black tracking-widest uppercase">Franchise Policy</p>
                    <p class="text-gray-500 text-[11px] leading-relaxed mt-1">Audit verification required for all business/franchise memberships.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between gap-6 pt-6">
                    <button type="submit" class="btn-luxury w-full sm:w-auto px-16 py-5 rounded-2xl font-black text-xs uppercase tracking-[4px]">Sign Up</button>
                    <a href="{{ route('google.login') }}" class="w-full sm:w-auto flex items-center justify-center space-x-3 px-10 py-5 border border-gray-100 rounded-2xl hover:border-orange-100 transition-all font-bold text-xs text-gray-500 uppercase tracking-widest">
                        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="w-5 h-5 grayscale opacity-70" alt="Google">
                        <span>Google Signup</span>
                    </a>
                </div>
            </form>
        </div>

        <p class="mt-12 lg:absolute lg:bottom-10 lg:right-10 text-[9px] text-gray-300 font-black tracking-[8px] uppercase opacity-30 text-center lg:text-right">© 2026 BHAVANI CRAFTS</p>
    </div>

    <script>
        document.getElementById('regForm').onsubmit = async function(e) {
            e.preventDefault();
            const loader = document.getElementById('masterLoader');
            const loaderText = document.getElementById('loaderText');
            loader.style.display = 'flex';

            const messages = [
                'Establishing Safe Connection...',
                'Verifying Credentials...',
                'Routing Application...',
                'Checking Encryption Keys...',
                'Optimizing Profile...'
            ];

            let msgIndex = 0;
            const messageInterval = setInterval(() => {
                msgIndex = (msgIndex + 1) % messages.length;
                loaderText.textContent = messages[msgIndex];
            }, 800);

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/api/auth/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                clearInterval(messageInterval);

                if (result.success) {
                    loaderText.textContent = 'Account Verified. Redirecting...';
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 500);
                } else {
                    loader.style.display = 'none';
                    if(result.errors) {
                        const errorMsg = Object.values(result.errors).flat().join('\n');
                        alert('Registration error:\n' + errorMsg);
                    } else {
                        alert(result.message || 'Registration failed. Please check your data.');
                    }
                }
            } catch (error) {
                clearInterval(messageInterval);
                loader.style.display = 'none';
                console.error('API Error:', error);
                alert('A technical connection error occurred. Our team has been notified.');
            }
        };
    </script>

</body>
</html>
