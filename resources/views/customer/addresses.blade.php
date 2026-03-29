@extends('customer.layout')

@section('customer_content')
<div class="space-y-8 animate-fadeIn" x-data="{ showForm: false }">
    <div class="flex items-center justify-between mb-2">
        <div>
            <h2 class="text-2xl font-black text-onyx-900 uppercase tracking-widest leading-none mb-2">Saved Addresses</h2>
            <p class="text-sm text-gray-400 font-medium">Manage your delivery locations.</p>
        </div>
        <button @click="showForm = !showForm" 
                class="px-6 py-3 bg-brand-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-600 transition-all duration-500 shadow-lg shadow-brand-500/20">
            <span x-text="showForm ? 'Cancel' : 'Add New Address'"></span>
        </button>
    </div>

    <!-- Address Form -->
    <div x-show="showForm" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-white rounded-[2rem] p-10 shadow-xl border border-brand-500/20 mb-12">
        <h3 class="text-sm font-black text-onyx-900 uppercase tracking-widest mb-8 italic">Address Details</h3>
        <form action="{{ route('customer.addresses.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Recipient Name</label>
                    <input type="text" name="full_name" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Phone Number</label>
                    <input type="text" name="phone_number" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Street Address</label>
                    <input type="text" name="address_line1" required placeholder="House No, Floor, Street..." class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="md:col-span-2 space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Landmark (Optional)</label>
                    <input type="text" name="address_line2" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">City / Town</label>
                    <input type="text" name="city" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">State / Province</label>
                    <input type="text" name="state" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Postal Code</label>
                    <input type="text" name="postal_code" required class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-[2px] text-onyx-900 ml-1">Address Type</label>
                    <select name="address_type" class="w-full px-6 py-4 bg-gray-50 border-none rounded-2xl text-sm font-bold text-onyx-900 focus:ring-2 focus:ring-brand-500/20 transition-all appearance-none">
                        <option value="home">Home</option>
                        <option value="office">Office</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center space-x-3 pt-4">
                <input type="checkbox" name="is_default" value="1" id="is_default" class="h-5 w-5 rounded border-gray-300 text-brand-500 focus:ring-brand-500/20">
                <label for="is_default" class="text-xs font-bold text-gray-500 uppercase tracking-widest">Set as default address</label>
            </div>
            <div class="pt-6">
                <button type="submit" class="w-full py-5 bg-brand-600 text-white text-xs font-black uppercase tracking-[4px] rounded-2xl hover:bg-brand-500 transition-all duration-500 shadow-xl shadow-brand-900/10 hover:shadow-brand-500/30">
                    Save Address
                </button>
            </div>
        </form>
    </div>

    <!-- Address Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($addresses as $address)
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border {{ $address->is_default ? 'border-brand-500 shadow-brand-500/5' : 'border-gray-100 hover:border-brand-300' }} transition-all duration-500 relative group overflow-hidden">
            @if($address->is_default)
            <div class="absolute top-0 right-10 bg-brand-500 text-white text-[9px] font-black uppercase tracking-widest px-4 py-1.5 rounded-b-xl shadow-lg">
                Default Address
            </div>
            @endif

            <div class="flex items-center space-x-3 mb-6">
                <div class="h-10 w-10 bg-gray-50 rounded-xl flex items-center justify-center text-brand-500">
                    @if($address->address_type === 'home')
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    @elseif($address->address_type === 'office')
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745V6a2 2 0 012-2h14a2 2 0 012 2v7.255z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8V5m0 3a2 2 0 110 4m0-4a2 2 0 110 4m0 0v3m3 3h3m-3 0h-3m-3 0H6m3 0h3" /></svg>
                    @else
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    @endif
                </div>
                <h4 class="text-sm font-black text-onyx-900 uppercase tracking-widest">{{ $address->full_name }}</h4>
            </div>

            <div class="space-y-1 mb-8">
                <p class="text-sm font-bold text-onyx-900">{{ $address->address_line1 }}</p>
                @if($address->address_line2)<p class="text-sm font-medium text-gray-500">{{ $address->address_line2 }}</p>@endif
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}</p>
                <p class="text-[10px] font-black text-brand-500 uppercase tracking-widest pt-2 italic">Phone: {{ $address->phone_number }}</p>
            </div>

            <div class="flex items-center space-x-4 pt-6 border-t border-gray-100">
                @if(!$address->is_default)
                <form action="{{ route('customer.addresses.default', $address->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-brand-500 hover:text-onyx-900 transition-colors">Set Primary</button>
                </form>
                @endif
                <form action="{{ route('customer.addresses.delete', $address->id) }}" method="POST" onsubmit="return confirm('Delete this address?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition-colors">Delete Address</button>
                </form>
            </div>
        </div>
        @empty
        <div class="md:col-span-2 py-20 text-center flex flex-col items-center justify-center bg-white rounded-[2rem] border-2 border-dashed border-gray-100">
            <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                <svg class="h-10 w-10 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
            </div>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest italic">No addresses saved yet.</p>
            <button @click="showForm = true" class="mt-8 text-[11px] font-black text-brand-500 uppercase tracking-[3px] hover:text-onyx-900 transition-colors">Add Your First Address</button>
        </div>
        @endforelse
    </div>
</div>
@endsection
