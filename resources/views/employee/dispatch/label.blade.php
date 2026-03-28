<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics Label - {{ $order->dispatch_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .no-print { display: none; }
            .label-card { border: 2px solid black; }
        }
        .label-card {
            width: 400px;
            margin: 20px auto;
            border: 1px solid #e5e7eb;
            background: white;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 p-10 flex flex-col items-center">

    <div class="no-print mb-8 space-x-4">
        <button onclick="window.print()" class="px-8 py-3 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-emerald-600/20 hover:bg-emerald-700 transition-all flex items-center space-x-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            <span>Execute Print</span>
        </button>
        <button onclick="window.close()" class="px-8 py-3 bg-white text-gray-400 border border-gray-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-all">Close Terminal</button>
    </div>

    <div class="label-card relative overflow-hidden">
        <!-- HEADER -->
        <div class="flex items-center justify-between border-b-2 border-black pb-4 mb-6">
            <div class="flex flex-col items-start">
                <h1 class="text-xs font-black uppercase tracking-[4px] leading-tight text-gray-900">Bhavani Crafts</h1>
                <p class="text-[8px] font-bold text-gray-300 uppercase tracking-widest mt-1">Sacred Logistics Hub</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black uppercase text-gray-900">{{ $order->dispatch_id }}</p>
                <p class="text-[7px] font-bold text-gray-400 mt-0.5">ORD: #{{ $order->order_id_string }}</p>
            </div>
        </div>

        <!-- RECIPIENT -->
        <div class="mb-8">
            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-3">Ship To / Recipient</p>
            <h2 class="text-lg font-black text-gray-900 uppercase tracking-tight mb-2">{{ $order->user->name ?? 'Sacred Guest' }}</h2>
            <div class="space-y-1">
                @if($order->address)
                    <p class="text-[12px] font-bold text-gray-600 uppercase leading-relaxed">{{ $order->address->address_line_1 }}</p>
                    <p class="text-[12px] font-bold text-gray-600 uppercase leading-relaxed">{{ $order->address->city }}, {{ $order->address->state }}</p>
                    <p class="text-[14px] font-black text-gray-900 mt-4 tracking-[4px]">{{ $order->address->postal_code }}</p>
                    <p class="text-[11px] font-bold text-gray-400 mt-2 uppercase">MOB: {{ $order->address->phone }}</p>
                @else
                    <p class="text-red-500 font-black uppercase text-xs">Registry Error: Address Not Found</p>
                @endif
            </div>
        </div>

        <!-- LOGISTICS QR & FOOTER -->
        <div class="flex items-end justify-between pt-6 border-t border-gray-100">
            <div class="flex flex-col items-start space-y-4">
                <div class="p-2 border border-black rounded-lg bg-white shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('logistics.verify.show', $order->encryptedId())) }}" 
                         alt="QR Code" class="h-24 w-24">
                </div>
                <p class="text-[7px] font-bold text-gray-400 uppercase tracking-widest max-w-[150px]">Scan for Logistics Routing & Registry Identification</p>
            </div>
            <div class="text-right flex flex-col items-end space-y-2">
                <div class="px-3 py-1 bg-black text-white text-[9px] font-black uppercase tracking-[3px] rounded-md">PREPAID</div>
                <p class="text-[7px] font-bold text-gray-300 uppercase tracking-tighter">Verified Audit: {{ $order->label_printed_at ? $order->label_printed_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <!-- SIDE BARCODE (Decorative/Logistics flavor) -->
        <div class="absolute top-0 right-0 h-full w-2 bg-black/5 flex flex-col justify-center space-y-1">
            @for($i=0; $i<20; $i++)
                <div class="w-full bg-black/20" style="height: {{ rand(1, 4) }}px"></div>
            @endfor
        </div>
    </div>

</body>
</html>
