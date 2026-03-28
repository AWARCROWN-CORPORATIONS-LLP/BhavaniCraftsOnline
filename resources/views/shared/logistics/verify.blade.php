@extends('layouts.logistics')

@section('header_extra')
    <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Secure Delivery Auth</h2>
    <p class="text-[10px] items-center font-black text-emerald-500 uppercase tracking-[4px]">Drop-off Verification</p>
@endsection

@section('content')

    <div class="max-w-lg mx-auto mt-12 space-y-8">
        <!-- Order Identity -->
        <div class="bg-white p-8 rounded-3xl shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] border border-gray-100 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-8 pb-8 border-b border-gray-100">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-[4px] leading-none mb-2">Transit Registry ID</p>
                    <p class="text-2xl font-bold font-mono tracking-tighter text-gray-900">#{{ $order->order_id_string }}</p>
                </div>
                <div class="bg-amber-500/10 border border-amber-500/20 px-6 py-3 rounded-2xl flex flex-col items-center">
                    <span class="text-[9px] font-black text-amber-600 uppercase tracking-[3px] leading-none mb-1">State</span>
                    <span class="text-xs font-bold text-amber-500 tracking-widest">{{ $order->delivery_status ?? 'Pending' }}</span>
                </div>
            </div>

            <!-- Recipient Detail -->
            <div class="space-y-6 mb-8">
                <div class="flex items-start space-x-6">
                    <div class="h-12 w-12 bg-sky-50 flex items-center justify-center rounded-2xl border border-sky-100 shrink-0">
                        <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[4px] leading-none mb-2">Recipient Seeker</p>
                        <p class="text-sm font-bold text-gray-900 leading-none">{{ $order->address->full_name ?? $order->user->name ?? 'Guest User' }}</p>
                        <p class="text-[11px] font-bold text-gray-500 mt-1 tracking-widest">{{ $order->address->phone_number ?? $order->user->phone ?? 'Contact Unavailable' }}</p>
                    </div>
                </div>

                <div class="flex items-start space-x-6">
                    <div class="h-12 w-12 bg-sky-50 flex items-center justify-center rounded-2xl border border-sky-100 shrink-0">
                        <svg class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="flex-grow">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[4px] leading-none mb-2">Drop-off Point</p>
                        @if($order->address)
                            <p class="text-xs font-bold text-gray-600 leading-relaxed">{{ $order->address->address_line_1 }}<br>{{ $order->address->city }}, {{ $order->address->state }} {{ $order->address->postal_code }}</p>
                        @else
                            <p class="text-xs font-bold text-gray-400 italic">No physical coordinates stored in registry.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Authentic Form -->
            <form action="{{ route('logistics.verify.process', $order->encryptedId()) }}" method="POST" class="border-t border-gray-100 pt-8 mt-4">
                @csrf
                <div class="mb-6 space-y-2 text-center">
                    <div class="mx-auto h-20 w-20 bg-gray-50 flex items-center justify-center rounded-full border border-gray-100 mb-4 shadow-inner">
                        <svg class="h-8 w-8 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <label class="text-[11px] font-black text-gray-800 tracking-[3px] uppercase block leading-none">Enter Recipient's Verification Code</label>
                    <p class="text-[9px] font-medium text-gray-400 leading-tight">Ask the customer to provide the 6-digit delivery security PIN from their control panel.</p>
                </div>

                <div class="mb-8">
                    <input type="text" name="delivery_pin" placeholder="000000" maxlength="6" inputmode="numeric" required class="w-full text-center tracking-[1rem] text-4xl font-mono py-6 border-b-2 border-gray-200 outline-none focus:border-sky-500 bg-transparent transition-all placeholder-gray-200 text-gray-900">
                </div>

                <button type="submit" class="w-full py-5 bg-logistics-blue hover:bg-sky-600 text-white font-black text-[12px] uppercase tracking-[4px] rounded-2xl shadow-xl shadow-sky-500/20 transition-transform hover:scale-[1.02]">
                    Confirm Delivery Validation
                </button>
            </form>
        </div>
    </div>

@endsection
