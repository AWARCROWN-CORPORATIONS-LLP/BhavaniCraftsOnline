@extends('layouts.public')

@section('content')
<div x-data="checkoutApp()" class="bg-gray-50 min-h-screen py-16 pb-32">
    <div class="container mx-auto px-4 lg:px-8 max-w-7xl">

        <!-- Header -->
        <div class="mb-12">
            <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">Secure Checkout</span>
            <h1 class="text-4xl font-serif font-bold text-onyx-900 italic">Checkout <span class="text-brand-500">Process</span></h1>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- Left: Address + Payment -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Step 1: Delivery Address -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <span class="h-8 w-8 bg-brand-500 text-white text-xs font-black rounded-full flex items-center justify-center">1</span>
                        <h2 class="text-sm font-black uppercase tracking-widest text-onyx-900">Delivery Address</h2>
                    </div>

                        <div x-show="addresses.length === 0" class="p-8 border-2 border-dashed border-gray-100 rounded-[2rem] text-center mb-6">
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">No addresses found</p>
                        </div>
                        <div x-show="addresses.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <template x-for="addr in addresses" :key="addr.id">
                                <label class="cursor-pointer">
                                    <input type="radio" name="address_id" :value="addr.id" 
                                           x-model="selectedAddressId"
                                           class="sr-only peer">
                                    <div class="relative p-5 rounded-2xl border-2 transition-all duration-300 
                                                peer-checked:border-brand-500 peer-checked:bg-brand-50/30 
                                                border-gray-100 hover:border-gray-200">
                                        <template x-if="addr.is_default">
                                            <span class="absolute top-3 right-3 text-[8px] font-black uppercase tracking-widest bg-brand-500 text-white px-2 py-0.5 rounded-full">Default</span>
                                        </template>
                                        <p class="text-sm font-black text-onyx-900 mb-1" x-text="addr.full_name"></p>
                                        <p class="text-[11px] text-gray-500" x-text="addr.phone_number"></p>
                                        <p class="text-[11px] text-gray-500 mt-2 leading-relaxed" x-text="addr.address_line1 + (addr.address_line2 ? ', ' + addr.address_line2 : '') + ', ' + addr.city + ', ' + addr.state + ' - ' + addr.postal_code"></p>
                                    </div>
                                </label>
                            </template>
                        </div>

                    <button @click="showAddressModal = true" 
                       class="inline-flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-brand-500 hover:text-onyx-900 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        <span>Add New Address</span>
                    </button>
                </div>

                <!-- Step 2: Order Review -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                    <div class="flex items-center space-x-3 mb-8">
                        <span class="h-8 w-8 bg-brand-500 text-white text-xs font-black rounded-full flex items-center justify-center">2</span>
                        <h2 class="text-sm font-black uppercase tracking-widest text-onyx-900">Order Items</h2>
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
                            <span x-text="paymentMethod === 'cod' ? 'Confirm COD Order — ₹' + totalAmount.toFixed(2) : 'Pay Online — ₹' + totalAmount.toFixed(2)"></span>
                        </template>
                        <template x-if="processing">
                            <span class="flex items-center space-x-3">
                                <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Processing...</span>
                            </span>
                        </template>
                    </button>

                    <p class="text-center text-[10px] text-gray-400 font-medium mt-4">
                        By placing this order, you agree to our <span class="text-brand-500">Terms and Conditions</span>
                    </p>
                </div>
            </div>

            <!-- Right: Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 sticky top-28">
                    <h3 class="text-sm font-black uppercase tracking-widest text-onyx-900 mb-8">Order Summary</h3>

                    <!-- Coupon Section -->
                    <div class="mb-8 p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <label class="text-[9px] font-black uppercase tracking-[2px] text-gray-400 block mb-3">Coupon Code</label>
                        
                        <div x-show="!appliedCoupon" class="flex space-x-2">
                            <input type="text" x-model="couponCode" placeholder="Enter Code" 
                                   class="flex-1 bg-white border-gray-200 rounded-xl text-xs font-bold px-4 h-11 focus:ring-brand-500 focus:border-brand-500 transition-all uppercase tracking-widest">
                            <button @click="applyCoupon()" :disabled="!couponCode"
                                    class="h-11 px-5 bg-onyx-900 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-500 transition-all disabled:opacity-30">
                                Apply
                            </button>
                        </div>

                        <div x-show="appliedCoupon" x-cloak class="flex items-center justify-between bg-brand-500/10 border border-brand-500/20 p-3 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <span class="h-8 w-8 bg-brand-500 text-white rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                </span>
                                <div>
                                    <p class="text-[10px] font-black text-onyx-900 uppercase tracking-widest" x-text="appliedCoupon ? appliedCoupon.code : ''"></p>
                                    <p class="text-[8px] font-bold text-brand-600 uppercase tracking-widest">Coupon Applied</p>
                                </div>
                            </div>
                            <button @click="removeCoupon()" class="text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between">
                            <span class="text-[11px] font-medium text-gray-400">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                            <span class="text-sm font-bold text-onyx-900">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        <div x-show="discountAmount > 0" x-cloak class="flex justify-between text-green-600">
                            <span class="text-[11px] font-medium">Discount</span>
                            <span class="text-sm font-bold">- ₹<span x-text="discountAmount.toFixed(2)"></span></span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-[11px] font-medium text-gray-400">GST (18%)</span>
                            <span class="text-sm font-bold text-onyx-900">₹<span x-text="gstAmount.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[11px] font-medium text-gray-400">Shipping Charge</span>
                            <template x-if="shippingAmount == 0">
                                <span class="text-sm font-bold text-green-500">FREE</span>
                            </template>
                            <template x-if="shippingAmount > 0">
                                <span class="text-sm font-bold text-onyx-900">₹<span x-text="shippingAmount.toFixed(2)"></span></span>
                            </template>
                        </div>
                        
                        <div x-show="shippingAmount > 0" class="text-[10px] font-medium text-brand-500 bg-brand-50 px-3 py-2 rounded-xl">
                            Add ₹{{ number_format(max(0, 999 - ($subtotal - $discountAmount)), 2) }} more for Free Shipping!
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-black uppercase tracking-widest text-onyx-900">Total</span>
                            <span class="text-2xl font-black text-onyx-900">₹<span x-text="totalAmount.toFixed(2)"></span></span>
                        </div>
                    </div>

                    <!-- Trust badges -->
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3 text-[10px] font-medium text-gray-400">
                            <svg class="h-4 w-4 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            <span>100% Authentic Products</span>
                        </div>
                        <div class="flex items-center space-x-3 text-[10px] font-medium text-gray-400">
                            <svg class="h-4 w-4 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            <span>Secure Payment via Razorpay</span>
                        </div>
                        <div class="flex items-center space-x-3 text-[10px] font-medium text-gray-400">
                            <svg class="h-4 w-4 text-brand-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            <span>Safe Packaging & Delivery</span>
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
        selectedAddressId: @php echo $addresses->where("is_default", true)->first()?->id ?? $addresses->first()?->id ?? 'null'; @endphp,
        paymentMethod: 'razorpay',
        processing: false,
        errorMessage: '',
        singleCartItemId: new URLSearchParams(window.location.search).get('single_cart_item'),
        addresses: @json($addresses),
        showAddressModal: false,
        newAddress: {
            full_name: '',
            phone_number: '',
            address_line1: '',
            address_line2: '',
            city: '',
            state: '',
            postal_code: '',
            address_type: 'home',
            is_default: true
        },
        
        // Coupon Data
        couponCode: '',
        appliedCoupon: @json($appliedCoupon),
        discountAmount: {{ $discountAmount }},
        subtotal: {{ $subtotal }},
        
        // Computed-like getters for totals
        get discountedSubtotal() { return Math.max(0, this.subtotal - this.discountAmount); },
        get gstAmount() { return Math.round(this.discountedSubtotal * 0.18 * 100) / 100; },
        get shippingAmount() { return this.discountedSubtotal >= 999 ? 0 : 80; },
        get totalAmount() { return this.discountedSubtotal + this.gstAmount + this.shippingAmount; },

        applyCoupon() {
            if (!this.couponCode) return;
            BcLoader.show('Validating blessing...');
            
            fetch('{{ route("checkout.coupon.apply") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ coupon_code: this.couponCode })
            })
            .then(r => r.json())
            .then(data => {
                BcLoader.hide();
                if (data.error) {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.error, type: 'error' } }));
                    return;
                }
                this.appliedCoupon = { code: this.couponCode };
                this.discountAmount = parseFloat(data.discount_amount);
                this.couponCode = '';
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.success, type: 'success' } }));
            })
            .catch(() => {
                BcLoader.hide();
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Unexpected error. Try again.', type: 'error' } }));
            });
        },

        removeCoupon() {
            BcLoader.show('Removing coupon...');
            fetch('{{ route("checkout.coupon.remove") }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                BcLoader.hide();
                this.appliedCoupon = null;
                this.discountAmount = 0;
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: data.success } }));
            });
        },

        async saveAddress() {
            // Validate all required fields
            const requiredFields = ['full_name', 'phone_number', 'address_line1', 'city', 'state', 'postal_code'];
            let missing = requiredFields.filter(f => !this.newAddress[f]);
            
            if (missing.length > 0) {
                window.dispatchEvent(new CustomEvent('notify', { 
                    detail: { message: 'Please fill all required ritual fields: ' + missing.join(', ').replace(/_/g, ' '), type: 'error' } 
                }));
                return;
            }

            BcLoader.show('Sanctifying your address...');
            try {
                const res = await fetch('{{ route("customer.addresses.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.newAddress)
                });
                
                const data = await res.json();
                
                if (res.ok && data.address) {
                    this.addresses.push(data.address);
                    this.selectedAddressId = data.address.id;
                    this.showAddressModal = false;
                    // Reset to clean state
                    this.newAddress = { full_name: '', phone_number: '', address_line1: '', address_line2: '', city: '', state: '', postal_code: '', address_type: 'home', is_default: false };
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Divine address saved successfully!' } }));
                } else {
                    // Handle validation errors from backend
                    let msg = data.message || 'Validation error.';
                    if (data.errors) {
                        msg = Object.values(data.errors).flat().join(' ');
                    }
                    window.dispatchEvent(new CustomEvent('notify', { detail: { message: msg, type: 'error' } }));
                }
            } catch (e) {
                console.error(e);
                window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Connection interrupted. Please try again.', type: 'error' } }));
            } finally {
                BcLoader.hide();
            }
        },

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
                    single_cart_item: this.singleCartItemId,
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
                    window.location.href = data.redirect;
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
                    single_cart_item: this.singleCartItemId,
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
                    theme: { color: '#C62828' },
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
                    window.location.href = data.redirect;
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

<!-- New Address Modal -->
<div x-show="showAddressModal" x-cloak
     class="fixed inset-0 z-[100] overflow-y-auto"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <div class="flex items-center justify-center min-h-screen px-4 py-8 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-onyx-900/60 backdrop-blur-sm" @click="showAddressModal = false"></div>

        <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2.5rem] border border-gray-100">
            <div class="p-8 sm:p-12">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[4px] text-brand-500 block mb-2">New Address</span>
                        <h2 class="text-2xl font-serif font-bold text-onyx-900 italic">Add <span class="text-brand-500">Address</span></h2>
                    </div>
                    <button @click="showAddressModal = false" class="h-12 w-12 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-onyx-900 transition-all">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Full Name</label>
                        <input type="text" x-model="newAddress.full_name" placeholder="Receiver's Name"
                               class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-brand-500 focus:border-brand-500 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Phone Number</label>
                        <input type="text" x-model="newAddress.phone_number" placeholder="Phone Number"
                               class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-brand-500 focus:border-brand-500 transition-all">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Address Line 1</label>
                        <input type="text" x-model="newAddress.address_line1" placeholder="House / Office / Suite"
                               class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-brand-500 focus:border-brand-500 transition-all">
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Landmark (Optional)</label>
                        <input type="text" x-model="newAddress.address_line2" placeholder="Near..."
                               class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-brand-500 focus:border-brand-500 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">City</label>
                        <input type="text" x-model="newAddress.city" placeholder="City"
                               class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-brand-500 focus:border-brand-500 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Postal Code</label>
                        <input type="text" x-model="newAddress.postal_code" placeholder="PIN"
                               class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-brand-500 focus:border-brand-500 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">State</label>
                        <select x-model="newAddress.state" class="w-full h-14 bg-gray-50 border-transparent rounded-2xl px-6 text-sm font-bold text-onyx-900 focus:bg-white focus:ring-brand-500 focus:border-brand-500 transition-all">
                            <option value="">Select State</option>
                            <option value="Karnataka">Karnataka</option>
                            <option value="Maharashtra">Maharashtra</option>
                            <option value="Delhi">Delhi</option>
                            <option value="Tamil Nadu">Tamil Nadu</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Address Type</label>
                        <div class="flex space-x-2">
                             <button type="button" @click="newAddress.address_type = 'home'" 
                                     :class="newAddress.address_type === 'home' ? 'bg-onyx-900 text-white ring-2 ring-brand-500' : 'bg-white text-gray-700 border border-gray-200'"
                                     class="flex-1 h-14 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Home</button>
                             <button type="button" @click="newAddress.address_type = 'office'" 
                                     :class="newAddress.address_type === 'office' ? 'bg-onyx-900 text-white ring-2 ring-brand-500' : 'bg-white text-gray-700 border border-gray-200'"
                                     class="flex-1 h-14 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Office</button>
                             <button type="button" @click="newAddress.address_type = 'other'" 
                                     :class="newAddress.address_type === 'other' ? 'bg-onyx-900 text-white ring-2 ring-brand-500' : 'bg-white text-gray-700 border border-gray-200'"
                                     class="flex-1 h-14 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">Other</button>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex items-center space-x-4">
                    <button type="button" @click="showAddressModal = false"
                            class="flex-1 h-16 bg-gray-100 text-gray-700 text-[11px] font-black uppercase tracking-[3px] rounded-2xl hover:bg-gray-200 hover:text-onyx-900 transition-all border border-gray-200">
                        Cancel
                    </button>
                    <button type="button" @click="saveAddress()"
                            class="flex-1 h-16 bg-brand-500 text-white text-[11px] font-black uppercase tracking-[3px] rounded-2xl shadow-xl shadow-brand-500/20 hover:bg-brand-600 transition-all">
                        Save Address
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
