@extends(request()->routeIs('superadmin.*') ? 'layouts.admin' : (request()->routeIs('franchise.*') ? 'layouts.franchise' : 'layouts.employee'))

@section('header_extra')
    <div class="flex items-center space-x-4">
        <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Verified Audit</h2>
        <span class="text-gray-300">/</span>
        <p class="text-[10px] items-center font-black text-sky-500 uppercase tracking-[4px]">Financial Registry Sync</p>
    </div>
@endsection

@section('content')

    <div class="card-premium p-10 mb-10 border-l-4 border-sky-500">
        @php $pfx = explode('.', request()->route()->getName())[0]; @endphp
        <form action="{{ route($pfx . '.payment.verify.search') }}" method="GET" class="space-y-6">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none mb-4">Payment Query Engine</h3>
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-grow">
                    <input type="text" name="query" value="{{ $q ?? '' }}" required 
                           placeholder="Razorpay ID, Email, Phone, or Order ID..."
                           class="w-full bg-gray-50 border-none px-8 py-5 rounded-2xl text-sm font-medium focus:ring-2 focus:ring-sky-500/20 transition-all">
                </div>
                <button type="submit" class="btn-luxury px-12 py-5 text-[11px] shadow-2xl">Execute Registry Search</button>
            </div>
            <div class="flex items-center space-x-2 pl-2">
                <div class="h-1.5 w-1.5 rounded-full bg-sky-500 animate-pulse"></div>
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest ">Synchronized with Razorpay Live API</p>
            </div>
        </form>
    </div>

    @if(session('error'))
        <div class="mb-8 p-6 bg-red-50 border-l-4 border-red-500 rounded-2xl flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="h-10 w-10 bg-red-500/10 text-red-600 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <p class="text-red-700 text-xs font-black uppercase tracking-widest">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(isset($orders))
        <div class="card-premium overflow-hidden">
            <div class="p-8 border-b border-gray-100 flex items-center justify-between bg-gray-50/30">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-[6px] leading-none">Query Results</h3>
                <span class="text-[9px] font-black uppercase text-gray-300">Searching for: "{{ $q }}"</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left uppercase tracking-widest">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="px-8 py-5 text-[9px] font-black text-gray-400">Order ID</th>
                            <th class="px-8 py-5 text-[9px] font-black text-gray-400">Registry Snapshot</th>
                            <th class="px-8 py-5 text-[9px] font-black text-gray-400">Amount</th>
                            <th class="px-8 py-5 text-[9px] font-black text-gray-400">Status Logic</th>
                            <th class="px-8 py-5 text-[9px] font-black text-gray-400">Action Registry</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @if(count($orders) > 0)
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="text-[11px] font-black text-gray-900">#{{ $order->order_id_string }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 mt-1 italic">{{ $order->created_at->format('d M, Y') }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="text-[11px] font-black text-gray-900">{{ $order->user->name ?? 'Guest User' }}</p>
                                    <p class="text-[9px] font-bold text-sky-500 mt-1 lowercase select-all cursor-pointer hover:underline">{{ $order->razorpay_order_id ?? 'Missing Razorpay Reference' }}</p>
                                </td>
                                <td class="px-8 py-6 text-[11px] font-black text-gray-900">
                                    ₹{{ number_format($order->total_amount, 2) }}
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col space-y-2">
                                        <span class="inline-block px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] w-fit
                                            {{ $order->payment_status == 'Paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            PAY: {{ $order->payment_status }}
                                        </span>
                                        <span class="inline-block px-3 py-1 rounded-full text-[8px] font-black tracking-[2px] w-fit bg-gray-100 text-gray-500">
                                            ORD: {{ $order->status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-6">
                                        <div x-data="{ manualInput: false, rzpOrderId: '{{ $order->razorpay_order_id }}' }">
                                            <form action="{{ route($pfx . '.payment.verify.verify', $order->id) }}" method="POST" class="flex items-center space-x-3">
                                                @csrf
                                                <input x-show="manualInput" type="text" name="manual_razorpay_order_id" x-model="rzpOrderId" 
                                                       placeholder="RZP Order ID..." 
                                                       class="bg-white border border-gray-100 rounded-lg px-3 py-2 text-[9px] font-bold h-10 ring-0 focus:ring-2 focus:ring-sky-500/20 transition-all">
                                                
                                                <button type="submit" class="group flex flex-col items-start">
                                                    <span class="text-[9px] font-black uppercase text-sky-500 group-hover:text-sky-700 transition-colors">Invoke Sync</span>
                                                    <span class="text-[7px] font-black text-gray-300 uppercase tracking-widest mt-0.5">Live Razorpay</span>
                                                </button>

                                                <button type="button" @click="manualInput = !manualInput" class="text-gray-300 hover:text-gray-500 transition-colors">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                        <a href="{{ route($pfx . '.orders.show', $order->encryptedId()) }}" 
                                           class="group flex flex-col items-start border-l border-gray-100 pl-6">
                                            <span class="text-[9px] font-black uppercase text-gray-400 group-hover:text-gray-900 transition-colors">Details</span>
                                            <span class="text-[7px] font-black text-gray-300 uppercase tracking-widest mt-0.5">Audit View</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="p-20 text-center text-gray-300 italic">No matching records found in the registry for "{{ $q }}". Try an partial email or partial Razorpay ID.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if(method_exists($orders, 'links'))
            <div class="px-8 py-6 border-t border-gray-100 bg-gray-50/10">
                {{ $orders->appends(['query' => $q])->links() }}
            </div>
            @endif
        </div>
    @endif

@endsection

@push('scripts')
<style>
    /* Custom pagination styles for elite theme */
    .pagination { @apply flex items-center space-x-2 text-[10px] font-black; }
    .page-item .page-link { @apply px-3 py-1 rounded-lg border border-gray-100 bg-white text-gray-400 hover:bg-sky-50 transition-all; }
    .page-item.active .page-link { @apply bg-sky-500 border-sky-500 text-white shadow-lg shadow-sky-500/20; }
</style>
@endpush
