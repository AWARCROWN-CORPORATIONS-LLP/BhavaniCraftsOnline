<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $bill->is_quotation ? 'Quotation' : 'Tax Invoice' }} - {{ $bill->bill_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-print-color-adjust: exact; margin: 0; padding: 0; }
        @media print {
            @page { size: legal; margin: 10mm; }
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-border { border: 1px solid #e5e7eb !important; box-shadow: none !important; margin: 0 !important; width: 100% !important; border-radius: 0 !important; }
            .invoice-container { min-height: 330mm; display: flex; flex-direction: column; justify-content: space-between; }
        }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-10 flex justify-center">

    <div class="invoice-container w-full max-w-[216mm] bg-white p-8 md:p-10 shadow-xl print-border border border-gray-100 rounded-xl relative overflow-hidden">

        
        <!-- COMPACT HEADER (VERTICAL SPACE OPTIMIZED) -->
        <div class="flex justify-between items-start border-b-2 border-gray-900 pb-4 mb-6">
            <div class="flex items-start space-x-4">
                @if($siteLogo)
                    <img src="{{ Str::startsWith($siteLogo, 'http') ? $siteLogo : asset($siteLogo) }}" alt="Logo" class="h-14 w-auto object-contain">
                @endif
                <div>
                    <h2 class="text-xs font-black text-gray-900 leading-none mb-1">BHAVANI CRAFTS</h2>
                    <div class="text-[9px] font-bold text-gray-500 uppercase tracking-widest leading-relaxed">
                        <p>GSTIN: 37AGUPK4987C1ZS | Reg Date: 30 June 2017</p>
                        <p>Vijayawada, AP | info-services@bhavanicrafts.com</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <h1 class="text-xl font-black text-gray-900 uppercase leading-none mb-1">{{ $bill->is_quotation ? 'Quotation' : 'Tax Invoice' }}</h1>
                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none space-y-0.5">
                    <p>No: <span class="text-gray-900">#{{ $bill->bill_number }}</span></p>
                    <p>Date: <span class="text-gray-900">{{ $bill->created_at->format('d/m/Y') }}</span></p>
                </div>
            </div>
        </div>

        <!-- RECIPIENT COMPACT -->
        <div class="grid grid-cols-2 gap-8 mb-6 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
            <div>
                <h4 class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">{{ $bill->is_quotation ? 'Interested Client:' : 'Billed To:' }}</h4>
                <p class="text-xs font-black text-gray-900 uppercase leading-tight">{{ $bill->customer_name ?? 'Walk-in Customer' }}</p>
                <p class="text-[10px] text-gray-500 font-medium">Cell: {{ $bill->customer_phone ?? 'N/A' }} | Place Supply: 37-AP</p>
            </div>
            <div class="text-right">
                <h4 class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">State</h4>
                <span class="inline-block px-3 py-1 bg-white {{ $bill->is_quotation ? 'text-amber-600 border-amber-100' : 'text-emerald-600 border-emerald-100' }} text-[9px] font-bold uppercase tracking-widest rounded-lg border shadow-sm">
                    {{ $bill->is_quotation ? 'Sent' : 'Verified' }}
                </span>
            </div>
        </div>

        <!-- ITEMS TABLE (HIGH VERTICAL DENSITY) -->
        <div class="flex-grow min-h-[120mm]">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-900 text-white border-b-2 border-gray-900">
                        <th class="px-4 py-3 text-[9px] font-bold uppercase tracking-widest text-left">#</th>
                        <th class="px-4 py-3 text-[9px] font-bold uppercase tracking-widest text-left">Description</th>
                        <th class="px-4 py-3 text-[9px] font-bold uppercase tracking-widest text-right">Amount (₹)</th>
                    </tr>
                </thead>
                <tbody class="border-x border-gray-50 divide-y divide-gray-50">
                    @foreach($bill->items as $index => $item)
                        <tr>
                            <td class="px-4 py-3 text-[10px] text-gray-400 font-bold">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-xs font-bold text-gray-800 uppercase tracking-tight">
                                {{ $item['name'] }}
                                @if(!empty($item['telugu_name']))
                                    <div class="text-[10px] text-gray-400 normal-case font-medium mt-0.5">{{ $item['telugu_name'] }}</div>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-xs font-black text-gray-900 text-right font-mono">₹{{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- TOTALS BLOCK (COMPACTED) -->
        <div class="pt-4 border-t-2 border-dashed border-gray-100 mt-4">
            <div class="flex justify-between items-start">
                <div class="max-w-[50%]">
                    <p class="text-[9px] text-gray-400 italic font-medium leading-relaxed">Goods once sold are not returnable. Computer-generated registry document No: {{ $bill->bill_number }}.</p>
                </div>
                <div class="w-64 space-y-1.5">
                    <div class="flex justify-between items-center text-gray-500 font-medium text-[10px]">
                        <span>Subtotal:</span>
                        <span class="font-bold text-gray-900">₹{{ number_format($bill->subtotal, 2) }}</span>
                    </div>
                    @if($bill->discount_amount > 0)
                        <div class="flex justify-between items-center text-blue-600 font-bold text-[10px]">
                            <span>Discount (-):</span>
                            <span>₹{{ number_format($bill->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    
                    @php
                        $gst = $bill->gst_amount;
                    @endphp
                    <div class="flex justify-between items-center text-gray-500 font-medium text-[10px]">
                        <span>CGST ({{ $bill->gst_percent / 2 }}%):</span>
                        <span class="font-bold text-gray-900">₹{{ number_format($gst/2, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-gray-500 font-medium text-[10px]">
                        <span>SGST ({{ $bill->gst_percent / 2 }}%):</span>
                        <span class="font-bold text-gray-900">₹{{ number_format($gst/2, 2) }}</span>
                    </div>

                    <div class="pt-3 border-t-2 border-gray-900 flex justify-between items-center mt-2">
                        <span class="text-xs font-black text-gray-900 uppercase">{{ $bill->is_quotation ? 'Quotation Total' : 'Net Amount' }}</span>
                        <span class="text-xl font-black text-gray-900 tracking-tighter">₹{{ number_format($bill->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER & SIGNATORY -->
        <div class="mt-12 flex justify-between items-end border-t border-gray-50 pt-6 opacity-80">
            <div>
                <p class="text-[8px] font-bold text-gray-400 italic">No physical signature required (System Generated No 2026)</p>
            </div>
            <div class="text-center w-48 border-t border-gray-400 pt-2">
                <p class="text-[10px] font-black text-gray-900 uppercase">Authorized Signatory</p>
            </div>
        </div>

        <!-- CONTROLS -->
        <div class="mt-8 no-print flex items-center justify-center space-x-6">
            <button onclick="window.print()" class="bg-gray-900 px-8 py-3 rounded-lg text-[10px] text-white font-bold uppercase tracking-widest shadow-lg hover:bg-black transition-all">Print Document</button>
            <a href="{{ route('admin.billing.index') }}" class="text-[10px] font-bold uppercase text-gray-400 hover:text-black tracking-[4px]">Close Terminal</a>
        </div>

    </div>

</body>
</html>
