@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-16 pb-32">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">

        <!-- Header -->
        <div class="mb-12">
            <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">Sacred Ceremony</span>
            <h1 class="text-4xl font-serif font-bold text-onyx-900 italic">Secure <span class="text-brand-500">Checkout</span></h1>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div x-data="checkoutApp()" class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Left: Address + Payment -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Step 1: Delivery Address -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <span class="h-8 w-8 bg-brand-500 text-white text-xs font-black rounded-full flex items-center justify-center">1</span>
                        <h2 class="text-sm font-black uppercase tracking-widest text-onyx-900">Delivery Sanctuary</h2>
                    </div>

                    @if($addresses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            @foreach($addresses as $address)
                                <label class="cursor-pointer">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" 
                                           x-model="selectedAddressId"
                                           class="sr-only peer"
                                           {{ $address->is_default ? 'checked' : '' }}>
                                    <div class="relative p-5 rounded-2xl border-2 transition-all duration-300 
                                                peer-checked:border-brand-500 peer-checked:bg-brand-50/30 
                                                border-gray-100 hover:border-gray-200">
                                        @if($address->is_default)
                                            <span class="absolute top-3 right-3 text-[8px] font-black uppercase tracking-widest bg-brand-500 text-white px-2 py-0.5 rounded-full">Default</span>
                                        @endif
                                        <p class="text-sm font-black text-onyx-900 mb-1">{{ $address->full_name }}</p>
                                        <p class="text-[11px] text-gray-500">{{ $address->phone_number }}</p>
                                        <p class="text-[11px] text-gray-500 mt-2 leading-relaxed">
                                            {{ $address->address_line1 }},
                                            @if($address->address_line2) {{ $address->address_line2 }}, @endif
                                            {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}
                                        </p>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endif

                    <a href="{{ route('customer.addresses') }}" 
                       class="inline-flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-brand-500 hover:text-onyx-900 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Add New Address</span>
                    </a>
                </div>

                <!-- Step 2: Order Review -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <span class="h-8 w-8 bg-brand-500 text-white text-xs font-black rounded-full flex items-center justify-center">2</span>
                        <h2 class="text-sm font-black uppercase tracking-widest text-onyx-900">Order Manifest</h2>
                    </div>

                    <div class="space-y-6">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-5">
                            <div class="h-20 w-20 rounded-2xl overflow-hidden bg-gray-50 shrink-0 border border-gray-100">
                                @php $img = $item->product->images->where('is_main', true)->first() ?? $item->product->images->first(); @endphp
                                @if($img)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($img->image_url) }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-onyx-900 truncate">{{ $item->product->product_name }}</p>
                                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-widest">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-black text-onyx-900 shrink-0">₹{{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Step 3: Payment Method -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <span class="h-8 w-8 bg-brand-500 text-white text-xs font-black rounded-full flex items-center justify-center">3</span>
                        <h2 class="text-sm font-black uppercase tracking-widest text-onyx-900">Payment Method</h2>
                    </div>

                    <!-- Method Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <!-- Razorpay -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="razorpay" x-model="paymentMethod" class="sr-only peer">
                            <div class="p-5 rounded-2xl border-2 transition-all duration-300
                                        peer-checked:border-brand-500 peer-checked:bg-brand-50/30
                                        border-gray-100 hover:border-gray-200">
                                <div class="flex items-center space-x-3 mb-2">
                                    <img src="https://razorpay.com/favicon.png" class="h-7 w-7 rounded-lg" alt="Razorpay">
                                    <p class="text-sm font-black text-onyx-900">Online Payment</p>
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium">UPI · Cards · Net Banking · Wallets · EMI</p>
                            </div>
                        </label>

                        <!-- COD -->
                        <label class="cursor-pointer">
                            <input type="radio" name="payment_method" value="cod" x-model="paymentMethod" class="sr-only peer">
                            <div class="p-5 rounded-2xl border-2 transition-all duration-300
                                        peer-checked:border-brand-500 peer-checked:bg-brand-50/30
                                        border-gray-100 hover:border-gray-200">
                                <div class="flex items-center space-x-3 mb-2">
                                    <div class="h-7 w-7 rounded-lg bg-green-100 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    </div>
                                    <p class="text-sm font-black text-onyx-900">Cash on Delivery</p>
                                </div>
                                <p class="text-[10px] text-gray-400 font-medium">Pay when your artifacts arrive</p>
                            </div>
                        </label>
                    </div>

                    <!-- COD notice -->
                    <div x-show="paymentMethod === 'cod'" x-cloak
                         class="p-4 bg-yellow-50 border border-yellow-100 rounded-2xl text-yellow-700 text-[11px] font-medium mb-6">
                        💰 Cash on Delivery is available. Please keep the exact amount ready at the time of delivery. COD charges ₹30 may apply.
                    </div>

                    <!-- Error message -->
                    <div x-show="errorMessage" x-cloak class="p-4 bg-red-50 border border-red-100 rounded-2xl text-red-600 text-sm font-medium mb-6" x-text="errorMessage"></div>

                    <!-- Place order button -->
                    <button @click="initiatePayment()"
                            :disabled="processing || !selectedAddressId"
                            class="w-full h-16 bg-brand-500 text-white text-[11px] font-black uppercase tracking-[3px] rounded-2xl shadow-xl shadow-brand-500/20 hover:bg-brand-600 transition-all flex items-center justify-center space-x-3 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!processing">
                            <span x-text="paymentMethod === 'cod' ? 'Confirm COD Order — ₹{{ number_format($total, 2) }}' : 'Pay Online — ₹{{ number_format($total, 2) }}'"></span>
                        </template>
                        <template x-if="processing">
                            <span class="flex items-center space-x-3">
                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Processing...</span>
                            </span>
                        </template>
                    </button>

                    <p class="text-center text-[10px] text-gray-400 font-medium mt-4">
                        By placing this order, you agree to our <span class="text-brand-500">Terms of Sanctuary</span>
                    </p>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 sticky top-28">
                    <h3 class="text-sm font-black uppercase tracking-widest text-onyx-900 mb-8">Sacred Summary</h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between">
                            <span class="text-[11px] font-medium text-gray-400">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span class="text-sm font-bold text-onyx-900">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[11px] font-medium text-gray-400">GST (18%)</span>
                            <span class="text-sm font-bold text-onyx-900">₹{{ number_format($gst, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[11px] font-medium text-gray-400">Sacred Shipping</span>
                            @if($shipping == 0)
                                <span class="text-sm font-bold text-green-500">FREE</span>
                            @else
                                <span class="text-sm font-bold text-onyx-900">₹{{ number_format($shipping, 2) }}</span>
                            @endif
                        </div>
                        @if($shipping > 0)
                            <div class="text-[10px] font-medium text-brand-500 bg-brand-50 px-3 py-2 rounded-xl">
                                Add ₹{{ number_format(999 - $subtotal, 2) }} more for Free Shipping!
                            </div>
                        @endif
                    </div>

                    <div class="border-t border-gray-100 pt-6 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-black uppercase tracking-widest text-onyx-900">Total</span>
                            <span class="text-2xl font-black text-onyx-900">₹{{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <!-- Trust badges -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 text-[10px] font-medium text-gray-400">
                            <svg class="h-4 w-4 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            <span>100% Authentic Artifacts</span>
                        </div>
                        <div class="flex items-center space-x-3 text-[10px] font-medium text-gray-400">
                            <svg class="h-4 w-4 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            <span>Secure Payment via Razorpay</span>
                        </div>
                        <div class="flex items-center space-x-3 text-[10px] font-medium text-gray-400">
                            <svg class="h-4 w-4 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            <span>Sacred Packaging & Delivery</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Razorpay SDK --}}
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
function checkoutApp() {
    return {
        selectedAddressId: '{{ $addresses->where("is_default", true)->first()?->id ?? $addresses->first()?->id }}',
        paymentMethod: 'razorpay',
        processing: false,
        errorMessage: '',

        initiatePayment() {
            if (!this.selectedAddressId) {
                this.errorMessage = 'Please select a delivery address before proceeding.';
                return;
            }
            this.processing = true;
            this.errorMessage = '';

            if (this.paymentMethod === 'cod') {
                BcLoader.show('Placing your order...');
                this.placeCodOrder();
            } else {
                BcLoader.show('Connecting to payment gateway...');
                this.initiateRazorpay();
            }
        },

        placeCodOrder() {
            fetch('{{ route("checkout.create_order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    address_id: this.selectedAddressId,
                    payment_method: 'cod',
                })
            })
            .then(r => r.json())
            .then(data => {
                this.processing = false;
                BcLoader.hide();
                if (data.error) {
                    this.errorMessage = data.error;
                    return;
                }
                if (data.success) {
                    document.body.innerHTML = `
                        <div class="min-h-screen bg-white flex items-center justify-center flex-col text-center p-8">
                            <div class="h-24 w-24 bg-green-50 rounded-full flex items-center justify-center mb-8 mx-auto">
                                <svg class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">Order Confirmed! 🙏</h2>
                            <p class="text-gray-500 mb-2">Order ID: <strong>${data.order_id}</strong></p>
                            <p class="text-gray-400 text-sm mb-2">Payment: <strong>Cash on Delivery</strong></p>
                            <p class="text-gray-400 text-sm mb-8">Your sacred artifacts will arrive shortly. Jai Bhavani!</p>
                            <a href="${data.redirect}" class="px-8 py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition">View My Orders</a>
                        </div>`;
                }
            })
            .catch(() => {
                BcLoader.hide();
                this.processing = false;
                this.errorMessage = 'Something went wrong. Please try again.';
            });
        },

        initiateRazorpay() {
            fetch('{{ route("checkout.create_order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    address_id: this.selectedAddressId,
                    payment_method: 'razorpay',
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    this.errorMessage = data.error;
                    this.processing = false;
                    BcLoader.hide();
                    return;
                }

                const options = {
                    key: data.key,
                    amount: data.amount,
                    currency: data.currency,
                    order_id: data.razorpay_order_id,
                    name: 'Bhavani Crafts',
                    description: 'Sacred Artifacts Collection',
                    image: '/favicon.ico',
                    prefill: {
                        name:    data.user_name,
                        email:   data.user_email,
                        contact: data.user_phone,
                    },
                    theme: { color: '#f5821c' },
                    handler: (response) => {
                        BcLoader.show('Verifying payment...');
                        this.verifyPayment(response);
                    },
                    modal: {
                        ondismiss: () => {
                            BcLoader.hide();
                            this.processing = false;
                            this.errorMessage = 'Payment was cancelled. Please try again.';
                        }
                    }
                };

                BcLoader.hide(); // Hide before Razorpay modal opens
                const rzp = new Razorpay(options);
                rzp.on('payment.failed', (response) => {
                    BcLoader.hide();
                    this.processing = false;
                    this.errorMessage = 'Payment failed: ' + response.error.description;
                });
                rzp.open();
            })
            .catch(() => {
                BcLoader.hide();
                this.processing = false;
                this.errorMessage = 'Something went wrong. Please try again.';
            });
        },

        verifyPayment(response) {
            fetch('{{ route("checkout.verify_payment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    razorpay_payment_id: response.razorpay_payment_id,
                    razorpay_order_id:   response.razorpay_order_id,
                    razorpay_signature:  response.razorpay_signature,
                })
            })
            .then(r => r.json())
            .then(data => {
                this.processing = false;
                BcLoader.hide();
                if (data.success) {
                    document.body.innerHTML = `
                        <div class="min-h-screen bg-white flex items-center justify-center flex-col text-center p-8">
                            <div class="h-24 w-24 bg-green-50 rounded-full flex items-center justify-center mb-8 mx-auto">
                                <svg class="h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">Order Confirmed! 🙏</h2>
                            <p class="text-gray-500 mb-2">Order ID: <strong>${data.order_id}</strong></p>
                            <p class="text-gray-400 text-sm mb-8">Your sacred artifacts will arrive shortly. Jai Bhavani!</p>
                            <a href="${data.redirect}" class="px-8 py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition">View My Orders</a>
                        </div>`;
                } else {
                    this.errorMessage = data.message || 'Payment verification failed. Please contact support.';
                }
            })
            .catch(() => {
                BcLoader.hide();
                this.processing = false;
                this.errorMessage = 'Verification error. Please contact support with your payment ID.';
            });
        }
    };
}
</script>
@endsection
