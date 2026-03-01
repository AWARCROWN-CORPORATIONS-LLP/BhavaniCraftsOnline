<div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 cursor-grab hover:shadow-md transition-shadow group relative" data-id="{{ $order->encryptedId() }}">
    <div class="flex justify-between items-start mb-3">
        <div>
            <span class="text-[10px] font-black uppercase text-gray-400 tracking-[2px]">#{{ $order->order_id_string }}</span>
            <h4 class="text-sm font-bold text-gray-900 leading-tight mt-1 truncate max-w-[180px]">{{ $order->user->name ?? 'Guest' }}</h4>
        </div>
        <a href="{{ route('admin.orders.show', $order->encryptedId()) }}" class="h-6 w-6 rounded border border-gray-100 flex items-center justify-center text-gray-400 hover:text-[#ff9933] hover:border-[#ff9933]/50 transition-colors" title="View Order Manifest">
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
        </a>
    </div>
    
    <div class="flex items-center space-x-2 mb-3">
        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-[2px] bg-gray-50 text-gray-500 border border-gray-100">
            {{ $order->items->sum('quantity') }} Line Items
        </span>
        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-[2px] 
            {{ $order->payment_status == 'Paid' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
            {{ $order->payment_status }}
        </span>
    </div>

    <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
        <span class="text-[13px] font-black text-gray-900 tracking-tight">₹{{ number_format($order->total_amount, 2) }}</span>
        <span class="text-[9px] font-bold text-gray-400 tracking-[1px]">{{ $order->ordered_date->diffForHumans() }}</span>
    </div>
</div>
