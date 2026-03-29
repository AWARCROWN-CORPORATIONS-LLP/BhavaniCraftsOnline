<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP | Bhavani Crafts</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Public Sans', sans-serif; height: 100vh; overflow: hidden; background: #f9f9f9; }
        .hero-bg { background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.1)), url('/assets/images/auth/hero_hd.png'); background-size: cover; background-position: center; }
        .btn-premium { background: #f97316; transition: all 0.2s ease; }
        .btn-premium:hover { background: #ea580c; transform: translateY(-1px); }
        .otp-input { 
            width: 50px; height: 60px; text-align: center; font-size: 24px; font-weight: 800; 
            border: 2px solid #e5e7eb; border-radius: 16px; background: #ffffff; 
            transition: all 0.2s; 
        }
        .otp-input:focus { border-color: #f97316; outline: none; box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.1); }
        @keyframes fadeUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-fade-up { animation: fadeUp 0.6s ease-out forwards; }
        
        /* Global Loading */
        #otp-loader {
            position: fixed; inset: 0; z-index: 9999; background: rgba(255,255,255,0.8);
            backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center;
        }
        body.bc-busy { cursor: wait !important; overflow: hidden !important; }
        body.bc-busy * { pointer-events: none !important; }
        body.bc-busy #otp-loader, body.bc-busy #otp-loader * { pointer-events: all !important; }
    </style>
</head>
<body class="flex items-center justify-center p-6 hero-bg">
    <div id="otp-loader">
        <div class="flex flex-col items-center">
            <div class="w-12 h-12 border-4 border-orange-200 border-t-orange-600 rounded-full animate-spin mb-4"></div>
            <p class="text-[10px] font-black uppercase tracking-[3px] text-orange-600">Verifying...</p>
        </div>
    </div>
    
    <script>
        document.addEventListener('submit', () => {
             document.getElementById('otp-loader').style.display = 'flex';
             document.body.classList.add('bc-busy');
        });
    </script>


    <div class="max-w-md w-full bg-white rounded-[40px] p-10 shadow-2xl animate-fade-up relative overflow-hidden">
        
        <!-- Decorative Saffron Circle -->
        <div class="absolute -top-12 -right-12 w-32 h-32 bg-orange-100 rounded-full opacity-50"></div>
        
        <div class="text-center mb-8 relative z-10">
            <div class="w-16 h-16 bg-orange-600 rounded-2xl flex items-center justify-center shadow-xl mx-auto mb-6">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <h2 class="text-3xl font-black text-gray-900 mb-2">Verify OTP</h2>
            <p class="text-gray-500 text-sm font-medium">We've sent a 6-digit code to your mobile</p>
            <p class="text-orange-600 font-bold text-xs mt-1 tracking-widest">+91 ******2291</p>
        </div>

        <form x-data="{ 
                otp: ['', '', '', '', '', ''],
                handleInput(e, index) {
                    if (e.target.value.length > 1) e.target.value = e.target.value.slice(-1);
                    this.otp[index] = e.target.value;
                    if (e.target.value && index < 5) this.$refs['input' + (index + 1)].focus();
                },
                handleBackspace(e, index) {
                    if (!e.target.value && index > 0) this.$refs['input' + (index - 1)].focus();
                }
            }" 
            action="#" method="POST" class="space-y-8 relative z-10">
            
            <div class="flex justify-between gap-2">
                <template x-for="(i, index) in 6" :key="index">
                    <input type="number" maxlength="1" 
                           class="otp-input" 
                           :x-ref="'input' + index"
                           @input="handleInput($event, index)"
                           @keydown.backspace="handleBackspace($event, index)">
                </template>
            </div>

            <button type="submit" class="w-full py-5 btn-premium text-white rounded-2xl font-black text-sm shadow-xl uppercase tracking-[4px]">
                Verify & Continue
            </button>

            <div class="text-center">
                <p class="text-xs text-gray-400 font-bold">
                    Didn't receive code? <a href="#" class="text-orange-600 hover:underline uppercase ml-2 tracking-widest">Resend Code</a>
                </p>
            </div>
        </form>

        <!-- Trust Footer -->
        <div class="mt-10 pt-6 border-t border-gray-50 flex items-center justify-center space-x-4 opacity-40 grayscale">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L3 7v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-9-5z"/></svg>
            <span class="text-[9px] font-black uppercase tracking-widest">Secure Connection</span>
        </div>
    </div>

</body>
</html>
