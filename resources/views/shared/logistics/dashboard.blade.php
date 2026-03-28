@extends('layouts.logistics')

@section('content')

    <div class="space-y-12">
        <div class="flex items-center space-x-6 pb-6 border-b border-gray-200">
            <h3 class="text-sm font-black text-logistics-blue uppercase tracking-[6px] leading-none">Active Deliveries</h3>
            <div class="px-4 py-2 bg-logistics-blue/10 text-logistics-blue rounded-lg text-[10px] font-black uppercase tracking-widest leading-none">
                {{ $activeDeliveries->count() }} Artifacts assigned
            </div>
        </div>

        @forelse ($activeDeliveries as $order)
            <div class="bg-white p-8 rounded-3xl shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100 flex flex-col justify-between">
                
                <div class="flex items-center justify-between mb-8 pb-8 border-b border-gray-100">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[4px] leading-none mb-2">Transit Registry ID</p>
                        <p class="text-xl font-bold font-mono tracking-tighter text-gray-900">#{{ $order->order_id_string }}</p>
                    </div>
                    @if($order->delivery_status === 'Return Requested')
                        <div class="bg-rose-500/10 border border-rose-500/20 px-6 py-3 rounded-2xl flex flex-col items-center">
                            <span class="text-[9px] font-black text-rose-600 uppercase tracking-[3px] leading-none mb-1">Mission Type</span>
                            <span class="text-xs font-bold text-rose-500 tracking-widest uppercase">Extraction (Return)</span>
                        </div>
                    @else
                        <div class="bg-amber-500/10 border border-amber-500/20 px-6 py-3 rounded-2xl flex flex-col items-center">
                            <span class="text-[9px] font-black text-amber-600 uppercase tracking-[3px] leading-none mb-1">State</span>
                            <span class="text-xs font-bold text-amber-500 tracking-widest">{{ $order->delivery_status ?? 'Pending' }}</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-6 mb-8">
                    <div class="flex items-start space-x-6">
                        <div class="h-12 w-12 bg-gray-50 flex items-center justify-center rounded-2xl border border-gray-100 shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[4px] leading-none mb-2">Recipient Seeker</p>
                            <p class="text-sm font-bold text-gray-900 leading-none">{{ $order->user->name ?? 'Guest User' }}</p>
                            <p class="text-[11px] font-bold text-gray-500 mt-1 tracking-widest">{{ $order->user->phone ?? 'Contact Unavailable' }}</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-6">
                        <div class="h-12 w-12 bg-gray-50 flex items-center justify-center rounded-2xl border border-gray-100 shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <div class="flex-grow">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[4px] leading-none mb-2">Exchange Point / Root Map</p>
                            @if($order->shipping_address)
                                <p class="text-xs font-bold text-gray-600 leading-relaxed">{{ $order->shipping_address->street_address }}<br>{{ $order->shipping_address->city }}, {{ $order->shipping_address->state }} {{ $order->shipping_address->postal_code }}</p>
                            @else
                                <p class="text-xs font-bold text-gray-400 italic">No physical coordinates stored in registry.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-col space-y-4">
                    @if($order->delivery_status === 'Return Requested')
                        <form action="{{ route('logistics.orders.update-status', $order->encryptedId()) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Returned">
                            <button type="submit" class="w-full py-6 bg-rose-600 hover:bg-rose-700 text-white font-black text-[10px] uppercase tracking-[4px] rounded-2xl shadow-xl shadow-rose-500/20 transition-all hover:scale-[1.02] flex items-center justify-center space-x-3">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                <span>Confirm Collection (Item Received)</span>
                            </button>
                        </form>
                    @else
                        <!-- Location Broadcast -->
                        @if($order->delivery_status === 'Out for Delivery' || $order->delivery_status === 'In Transit')
                            <button onclick="broadcastLocation('{{ $order->encryptedId() }}')" class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-[9px] uppercase tracking-[3px] rounded-xl shadow-lg shadow-emerald-500/20 transition-transform hover:scale-[1.02] flex items-center justify-center space-x-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                <span>Broadcast Location Signal</span>
                            </button>
                        @endif

                        <!-- Update status form -->
                        <form action="{{ route('logistics.orders.update-status', $order->encryptedId()) }}" method="POST" class="w-full">
                            @csrf
                            @method('PATCH')
                            <div class="flex items-center space-x-4 w-full">
                                <select name="status" class="w-1/2 bg-gray-50 border border-gray-100 px-6 py-4 rounded-xl text-xs font-bold text-gray-600 tracking-wider hover:bg-white focus:ring-0 focus:border-logistics-blue transition-colors">
                                    <option value="In Transit" @if($order->delivery_status == 'In Transit') selected @endif>Mark In Transit</option>
                                    <option value="Out for Delivery" @if($order->delivery_status == 'Out for Delivery') selected @endif>Mark Out for Delivery</option>
                                </select>
                                <button type="submit" class="w-1/2 py-4 bg-gray-900 hover:bg-black text-white font-black text-[9px] uppercase tracking-[3px] rounded-xl shadow-lg shadow-gray-500/20 transition-transform hover:scale-[1.02]">
                                    Update Link
                                </button>
                            </div>
                        </form>

                        @if($order->delivery_status === 'Out for Delivery')
                            <a href="{{ route('logistics.verify.show', $order->encryptedId()) }}" class="w-full py-4 bg-logistics-blue hover:bg-sky-600 text-white font-black text-[9px] uppercase tracking-[3px] rounded-xl shadow-lg shadow-sky-500/20 transition-transform hover:scale-[1.02] flex items-center justify-center space-x-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M8 12h.01M12 16h.01M16 16h.01M8 16h.01" /></svg>
                                <span>Initiate Deliver (Scan QR)</span>
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-gray-50/50 p-16 rounded-[3rem] border border-gray-100 flex flex-col items-center justify-center text-center">
                <div class="h-24 w-24 bg-white shadow-xl rounded-full flex items-center justify-center mb-8">
                    <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </div>
                <h4 class="text-lg font-black text-gray-900 uppercase tracking-widest mb-2">No Active Logistics Missions</h4>
                <p class="text-[11px] font-bold tracking-widest text-gray-400 max-w-sm uppercase">You have no pending field operations assigned in the registry at this time.</p>
            </div>
        @endforelse


        <div class="flex items-center space-x-6 pb-6 border-b border-gray-200 mt-20">
            <h3 class="text-sm font-black text-gray-400 uppercase tracking-[6px] leading-none">Historical Field Ops</h3>
            <div class="px-4 py-2 bg-gray-200/50 text-gray-500 rounded-lg text-[10px] font-black uppercase tracking-widest leading-none">
                {{ $pastDeliveries->count() }} Encounters Logged
            </div>
        </div>

        <div class="space-y-6">
            @forelse ($pastDeliveries as $order)
                <div class="bg-white p-6 rounded-2xl border border-gray-100 flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-6">
                        <div class="h-12 w-12 rounded-xl {{ $order->delivery_status == 'Delivered' ? 'bg-emerald-50 text-emerald-500 border border-emerald-100' : 'bg-red-50 text-red-500 border border-red-100' }} flex items-center justify-center shrink-0">
                            @if($order->delivery_status == 'Delivered')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-black text-gray-900 uppercase tracking-widest mb-1 leading-none">#{{ $order->order_id_string }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-[2px] leading-none">
                                {{ $order->delivery_status }}
                                @if($order->delivered_at) - {{ $order->delivered_at->format('M d, H:i') }} @endif
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-[11px] font-bold text-gray-400 italic text-center p-8 bg-gray-50/50 rounded-2xl border border-gray-50">Log clean.</p>
            @endforelse
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function broadcastLocation(token) {
        if (!navigator.geolocation) {
            alert('Geospatial access denied by hardware.');
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            fetch(`/logistics/orders/${token}/location`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ latitude: lat, longitude: lng })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Signal Lock: Position broadcasted to HQ.');
                } else {
                    alert('Transmission Failure: ' + data.message);
                }
            })
            .catch(err => {
                alert('Atmospheric Interference: Could not reach HQ servers.');
            });
        }, function(err) {
            alert('Geospatial Lock Failed: Ensure GPS is enabled.');
        });
    }
</script>
@endpush
