<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Success - {{ $order->order_id_string }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.3); }
        .success-bg { background: radial-gradient(circle at top right, #10b981 0%, transparent 70%); }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center p-6 success-bg">

    <div class="max-w-md w-full">
        <!-- LOGO / HEADER -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-black uppercase tracking-tighter text-gray-900">Delivery Registry</h1>
            <p class="text-[10px] font-black text-emerald-500 uppercase tracking-[4px] mt-2">Logistics Success Event</p>
        </div>

        <div class="glass rounded-[2.5rem] p-10 shadow-2xl shadow-emerald-500/10 text-center border-l-8 border-emerald-500">
            <div class="h-20 w-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl shadow-emerald-500/20">
                <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
            </div>

            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight mb-2 italic">Delivery Successful</h2>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[4px] mb-8">Registry ID #{{ $order->order_id_string }}</p>

            <div class="space-y-4 text-left">
                <div class="bg-white/50 p-6 rounded-2xl border border-white/30">
                    <p class="text-[9px] font-black text-gray-300 uppercase tracking-widest mb-4">Event Registry Details</p>
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-[10px] font-black uppercase text-gray-400">Recipient Registry</span>
                        <span class="text-[11px] font-black uppercase text-gray-900 italic">{{ $order->user->name ?? 'Sacred Guest' }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-[10px] font-black uppercase text-gray-400">Logistics ID</span>
                        <span class="text-[11px] font-black uppercase text-emerald-600 italic tracking-widest">{{ $order->dispatch_id }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black uppercase text-gray-400">Authenticated At</span>
                        <span class="text-[11px] font-black uppercase text-gray-900 italic tracking-tighter">{{ $order->delivered_at ? $order->delivered_at->format('d M, Y H:i') : '--' }}</span>
                    </div>
                </div>
                
                <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100/50">
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest text-center italic">The registry has been cryptographically finalized. The verification QR code is now invalid.</p>
                </div>
            </div>

            <a href="{{ route('home') }}" class="mt-8 inline-block px-10 py-4 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-[3px] shadow-lg hover:shadow-xl transition-all">Back to Command Center</a>
        </div>

        <p class="text-center text-[8px] font-black text-gray-400 uppercase tracking-[4px] mt-10 italic">Bhavani Crafts Logistics Integrity Protocol</p>
    </div>

</body>
</html>
