@extends('layouts.admin')

@section('header_extra')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <div class="flex items-center space-x-4">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900 tracking-tight">Billing Dashboard</h2>
            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-widest">Create Bills & Quotations</p>
        </div>
    </div>
@endsection

@section('content')

<div x-cloak x-data="{
    items: [{ name: '', telugu_name: '', amount: '', quantity: 1, searchResults: [] }],
    customer_name: '',
    customer_phone: '',
    customer_results: [],
    discount_amount: 0,
    is_quotation: false,
    gst_percent: 18,
    payment_method: 'online',
    processing: false,
    quickSearch: '',
    
    addItem() { 
        this.items.push({ name: '', telugu_name: '', amount: '', quantity: 1, searchResults: [] });
        this.$nextTick(() => {
            const inputs = document.querySelectorAll('.product-name-input');
            inputs[inputs.length - 1].focus();
        });
    },

    removeItem(index) { 
        this.items.splice(index, 1);
        // Safety Net: If list becomes empty, add a fresh row
        if (this.items.length === 0) {
            this.items.push({ name: '', telugu_name: '', amount: '', quantity: 1, searchResults: [] });
        }
    },

    async searchForCustomer() {
        if (this.customer_phone.length < 3) { this.customer_results = []; return; }
        const response = await fetch(`{{ route('admin.billing.search-customers') }}?q=${this.customer_phone}`);
        this.customer_results = await response.json();
    },

    selectCustomer(c) {
        this.customer_name = c.name;
        this.customer_phone = c.phone;
        this.customer_results = [];
    },

    quickGuest() {
        this.customer_name = 'Walk-in Customer';
        this.customer_phone = '9999999999';
        this.customer_results = [];
    },

    async searchProduct(index) {
        let q = this.items[index].name;
        if (q.length < 2) { this.items[index].searchResults = []; return; }
        const response = await fetch(`{{ route('admin.billing.search-products') }}?q=${q}`);
        this.items[index].searchResults = await response.json();
    },

    selectProduct(index, p) {
        // High-Efficiency Merging: Check if item already exists
        let existingIndex = this.items.findIndex(item => item.name === p.product_name);
        
        if (existingIndex !== -1 && existingIndex !== index) {
            this.items[existingIndex].quantity++;
            // If we are selecting in a placeholder row that isn't the existing one, remove it
            if (this.items[index] && !this.items[index].name) { 
                this.removeItem(index); 
            }
            return;
        }

        if (this.items[index]) {
            this.items[index].name = p.product_name;
            this.items[index].telugu_name = p.telugu_name || '';
            this.items[index].amount = p.price;
            this.items[index].searchResults = [];
        }
    },

    reduceProduct(name) {
        let existingIndex = this.items.findIndex(item => item.name === name);
        if (existingIndex !== -1) {
            if (this.items[existingIndex].quantity > 1) {
                this.items[existingIndex].quantity--;
            } else {
                this.removeItem(existingIndex);
            }
        }
    },
    
    async translateName(index) {
        let name = this.items[index].name;
        if (!name || name.length < 3 || this.items[index].telugu_name) return;
        try {
            const response = await fetch(`https://api.mymemory.translated.net/get?q=${encodeURIComponent(name)}&langpair=en|te`);
            const data = await response.json();
            if (data.responseData.translatedText) {
                this.items[index].telugu_name = data.responseData.translatedText;
            }
        } catch (e) { console.error('Translation error:', e); }
    },

    get subtotal() {
        return this.items.reduce((sum, item) => sum + ((parseFloat(item.amount) || 0) * (parseInt(item.quantity) || 1)), 0);
    },
    get taxable() {
        return this.subtotal - (parseFloat(this.discount_amount) || 0);
    },
    get gstAmount() {
        return (this.taxable * this.gst_percent) / 100;
    },
    get total() {
        return this.taxable + this.gstAmount;
    },
    
    async generateBill() {
        if (this.items.some(i => !i.name || !i.amount)) {
            alert('Please fill all item details');
            return;
        }
        this.processing = true;
        try {
            const response = await fetch('{{ route('admin.billing.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    customer_name: this.customer_name,
                    customer_phone: this.customer_phone,
                    is_quotation: this.is_quotation,
                    discount_amount: this.discount_amount,
                    payment_method: this.payment_method,
                    items: this.items
                })
            });
            const result = await response.json();
            if (result.success) {
                if (result.payment_method === 'cash') {
                    window.location.href = `{{ url('/') }}/{{ app()->getLocale() }}/admin/billing/print/${result.bill_id}`;
                    return;
                }
                this.initiateRazorpay(result);
            }
        } catch (e) { console.error(e); }
        finally { this.processing = false; }
    },

    initiateRazorpay(data) {
        let options = {
            'key': '{{ config('services.razorpay.key') }}',
            'amount': data.total * 100,
            'currency': 'INR',
            'name': 'Bhavani Crafts',
            'description': 'Payment for Bill #' + data.bill_id,
            'order_id': data.razorpay_order_id, 
            'handler': async (response) => {
                window.location.href = `{{ url('/') }}/{{ app()->getLocale() }}/admin/billing/verify/${data.bill_id}?payment_id=` + response.razorpay_payment_id;
            },
            'prefill': {
                'name': this.customer_name,
                'contact': this.customer_phone
            },
            'theme': { 'color': '#1e40af' }
        };
        let rzp = new Razorpay(options);
        rzp.open();
    },

    // Global Scanner Logic
    handleScanner(code) {
        if (!code) return;
        fetch(`{{ route('admin.billing.search-products') }}?q=${code}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    const product = data[0];
                    let lastIdx = this.items.length - 1;
                    if (lastIdx >= 0 && !this.items[lastIdx].name && !this.items[lastIdx].amount) {
                         this.selectProduct(lastIdx, product);
                    } else {
                         this.addItem();
                         this.$nextTick(() => this.selectProduct(this.items.length - 1, product));
                    }
                }
            }).catch(e => console.error('Scanner error:', e));
    },

    quickAdd(p) {
        // Anti-Spam throttling
        if (this.processing) return;
        
        let existingIndex = this.items.findIndex(item => item.name === p.product_name);
        if (existingIndex !== -1) {
            this.items[existingIndex].quantity++;
        } else {
            // Find first empty row OR add new
            let emptyIdx = this.items.findIndex(item => !item.name);
            if (emptyIdx !== -1) {
                this.items[emptyIdx].name = p.product_name;
                this.items[emptyIdx].amount = p.price;
                this.items[emptyIdx].telugu_name = p.telugu_name || '';
            } else {
                this.items.push({ name: p.product_name, telugu_name: p.telugu_name || '', amount: p.price, quantity: 1, searchResults: [] });
            }
        }
    },

    getItemCount(name) {
        if (!name) return 0;
        return this.items.reduce((total, item) => {
            return (item.name === name) ? total + (parseInt(item.quantity) || 0) : total;
        }, 0);
    }
}" 
x-init="
    let scanBuffer = '';
    window.addEventListener('keydown', (e) => {
        // Keyboard Shortcuts
        if (e.key === 'F2') { e.preventDefault(); addItem(); }
        if (e.key === 'Insert') { e.preventDefault(); quickGuest(); }
        
        // Industrial Scanner Support (HID Mode)
        if (e.key === 'Enter') {
            if (scanBuffer.length >= 3) {
                handleScanner(scanBuffer);
                scanBuffer = '';
            }
        } else if (e.key.length === 1) {
            scanBuffer += e.key;
            // Clear buffer if no key for 100ms
            setTimeout(() => { scanBuffer = ''; }, 200);
        }
    });
"
class="space-y-6 max-w-[1600px] mx-auto">


    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-8 space-y-8">
            
            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                    <div class="flex items-center space-x-4">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest">Billing Terminal</h3>
                        <div class="flex items-center space-x-2">
                            <span class="flex h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Scanner Ready</span>
                        </div>
                    </div>
                    <button @click="addItem()" class="inline-flex items-center text-blue-600 hover:text-blue-700 text-xs font-bold uppercase tracking-wider transition-colors">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add [F2]
                    </button>
                </div>

                <!-- Scrollable Item Frame -->
                <div class="max-h-[500px] overflow-y-auto px-8 py-6 space-y-4 custom-scrollbar">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-end space-x-4 animate-in fade-in slide-in-from-bottom-2 duration-200">
                            <div class="flex-grow relative">
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Product Name (Search)</label>
                                <input type="text" x-model="item.name" @input.debounce.300ms="searchProduct(index)" @blur="setTimeout(() => translateName(index), 200)" placeholder="Start typing name or code..." 
                                    class="product-name-input w-full bg-white border border-gray-200 px-5 py-3 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-600/10 focus:border-blue-600 transition-all placeholder:text-gray-300">
                                
                                <!-- Product Search Results -->
                                <div x-show="item.searchResults && item.searchResults.length > 0" class="absolute z-20 left-0 right-0 mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden py-1">
                                    <template x-for="p in item.searchResults" :key="p.product_code">
                                        <button @click="selectProduct(index, p)" class="w-full text-left px-5 py-3 hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-0">
                                            <div class="flex justify-between items-center">
                                                <div class="flex flex-col">
                                                    <span class="text-xs font-bold text-gray-900" x-text="p.product_name"></span>
                                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider" x-text="p.product_code"></span>
                                                </div>
                                                <span class="text-xs font-black text-blue-600" x-text="'₹' + p.price"></span>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div class="flex-grow">
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Telugu Name (Auto)</label>
                                <input type="text" x-model="item.telugu_name" placeholder="తెలుగు పేరు..." 
                                    class="w-full bg-gray-50/50 border border-gray-100 px-5 py-3 rounded-xl text-sm font-medium text-blue-800">
                            </div>
                            <div class="w-32">
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Price (₹)</label>
                                <input type="number" x-model="item.amount" placeholder="0" 
                                    class="w-full bg-white border border-gray-200 px-5 py-3 rounded-xl text-sm font-bold focus:ring-1 focus:ring-blue-600/20 focus:border-blue-600 transition-all text-right">
                            </div>
                            <div class="w-24">
                                <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Qty</label>
                                <input type="number" x-model="item.quantity" min="1" placeholder="1" 
                                    class="w-full bg-white border border-gray-200 px-5 py-3 rounded-xl text-sm font-bold focus:ring-1 focus:ring-blue-600/20 focus:border-blue-600 transition-all text-center">
                            </div>

                            <button x-show="items.length > 1" @click="removeItem(index)" class="mb-3 p-2 text-gray-400 hover:text-red-500 transition-colors">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </template>

                    <div class="mt-8 pt-8 border-t border-gray-50 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="relative">
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-[10px] font-bold text-gray-400 uppercase block">Phone Number (Lookup)</label>
                                <button @click="quickGuest()" class="text-[8px] font-black text-blue-600 uppercase tracking-widest hover:underline decoration-2 underline-offset-4">Quick Guest [Ins]</button>
                            </div>
                            <input type="text" x-model="customer_phone" @input.debounce.300ms="searchForCustomer()" placeholder="99xx..." class="w-full border border-gray-200 px-5 py-3 rounded-xl text-sm font-bold">
                            
                            <!-- Customer Search Results -->
                            <div x-show="customer_results.length > 0" class="absolute z-20 left-0 right-0 mt-2 bg-white border border-gray-100 rounded-2xl shadow-2xl overflow-hidden py-1">
                                <template x-for="c in customer_results" :key="c.phone">
                                    <button @click="selectCustomer(c)" class="w-full text-left px-5 py-4 hover:bg-blue-50 transition-colors border-b border-gray-50 last:border-0">
                                        <div class="flex items-center space-x-3">
                                            <div class="h-8 w-8 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 font-black text-[10px]" x-text="c.name.substring(0,1)"></div>
                                            <div>
                                                <p class="text-xs font-bold text-gray-900" x-text="c.name"></p>
                                                <p class="text-[10px] text-gray-400 font-bold" x-text="c.phone"></p>
                                            </div>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Customer Name</label>
                            <input type="text" x-model="customer_name" class="w-full border border-gray-200 px-5 py-3 rounded-xl text-sm font-medium">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase mb-1.5 block">Direct Discount (₹)</label>
                            <input type="number" x-model="discount_amount" class="w-full border border-blue-100 bg-blue-50/30 px-5 py-3 rounded-xl text-sm font-bold text-blue-600">
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 mt-6">
                        <input type="checkbox" id="is_quotation" x-model="is_quotation" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <label for="is_quotation" class="text-xs font-bold text-gray-600 uppercase tracking-wider cursor-pointer">Make this a Quotation</label>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest">Recent Activity</h3>
                    <div class="flex items-center space-x-6">
                        <div class="flex items-center space-x-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                            <span class="text-[9px] font-bold uppercase text-gray-500 tracking-wider">Bills</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            <span class="text-[9px] font-bold uppercase text-gray-500 tracking-wider">Quotations</span>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Date / ID</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Customer Detail</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Amount</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-center">Type</th>
                                <th class="px-8 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($bills as $bill)
                                <tr x-data="{ 
                                    deleted: false, 
                                    loading: false,
                                    async archiveBill() {
                                        if (this.loading) return;
                                        if (!confirm('Archive this bill record permanently?')) return;
                                        
                                        this.loading = true;
                                        try {
                                            const resp = await fetch('{{ route('admin.billing.destroy', $bill->id) }}', {
                                                method: 'DELETE',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                    'Accept': 'application/json'
                                                }
                                            });
                                            if (resp.ok) this.deleted = true;
                                        } catch (e) { console.error(e); } finally { this.loading = false; }
                                    }
                                }" 
                                x-show="!deleted" 
                                x-transition.duration.500ms
                                class="hover:bg-slate-50 transition-all border-b border-slate-100 last:border-0 group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center space-x-4">
                                            <div class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-400 group-hover:bg-[#ff9933]/10 group-hover:text-[#ff9933] transition-all overflow-hidden border border-slate-100 uppercase">
                                                {{ substr($bill->customer_name ?? 'G', 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-[11px] font-black text-slate-900 uppercase tracking-widest leading-none mb-1">{{ $bill->customer_name ?? 'Guest Member' }}</p>
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-[9px] text-slate-400 font-bold tracking-tighter">#{{ $bill->bill_number }}</span>
                                                    <span class="text-[8px] text-slate-300 font-bold uppercase tracking-wider">• {{ $bill->created_at->format('d M, H:i') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-[11px] font-bold text-slate-900 leading-none mb-1">₹{{ number_format($bill->total_amount, 2) }}</p>
                                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">{{ $bill->payment_method }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @if($bill->type == 'quotation')
                                            <span class="inline-block px-3 py-1 bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-amber-100">Quote</span>
                                        @else
                                            <span class="inline-block px-3 py-1 bg-blue-50 text-blue-600 text-[9px] font-black uppercase tracking-widest rounded-full border border-blue-100">Bill</span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end space-x-4">
                                            @if($bill->payment_status == 'paid')
                                                <a href="{{ route('admin.billing.print', $bill->id) }}" class="text-[10px] font-black uppercase text-blue-600 hover:text-blue-800 transition-colors">Print</a>
                                            @else
                                                <a href="{{ route('admin.billing.verify', $bill->id) }}" class="text-[10px] font-black uppercase text-amber-600 hover:text-amber-800 transition-colors">Verify</a>
                                            @endif
                                            
                                            <!-- Silent Archive Utility -->
                                            <button @click="archiveBill()" class="relative p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors flex items-center justify-center" title="Archive Record">
                                                <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/80 rounded-lg">
                                                    <svg class="animate-spin h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                                </div>
                                                <svg x-show="!loading" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($bills->hasPages())
                    <div class="p-6 border-t border-gray-50 bg-gray-50/20">
                        {{ $bills->links() }}
                    </div>
                @endif
            </div>

        </div>

        <div class="lg:col-span-4 space-y-8">
            <!-- Quick-Pick Grid (Pinned Sidebar for Zero-Scroll) -->
            @php
                $topProducts = \App\Models\Product::orderBy('stock', 'desc')->take(12)->get();
            @endphp
            <div class="card-premium p-6">
                <div class="mb-5">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-[3px]">Quick-Pick Pad</h3>
                        <span class="text-[8px] font-black text-emerald-500 uppercase tracking-widest animate-pulse">Live Grid</span>
                    </div>
                    <div class="relative">
                        <input type="text" x-model="quickSearch" placeholder="Search Pad..." 
                           class="w-full bg-slate-50 border-none px-4 py-2 rounded-lg text-[10px] font-bold uppercase tracking-wider focus:ring-1 focus:ring-brand-primary/20 transition-all placeholder:text-slate-300">
                    </div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($topProducts as $p)
                        <div x-show="'{{ strtolower($p->product_name) }}'.includes(quickSearch.toLowerCase()) || '{{ $p->product_code }}'.toLowerCase().includes(quickSearch.toLowerCase())"
                             class="group relative flex flex-col items-center p-3 border border-slate-100 rounded-xl hover:border-brand-primary/30 hover:bg-slate-50 transition-all cursor-pointer">
                            <!-- Live Count Badge -->
                            <div x-show="getItemCount('{{ $p->product_name }}') > 0" 
                                 x-text="getItemCount('{{ $p->product_name }}')"
                                 class="absolute -top-1.5 -right-1.5 bg-brand-primary text-slate-900 text-[9px] font-black w-5 h-5 flex items-center justify-center rounded-full shadow-lg z-10 animate-in zoom-in">
                            </div>

                            <!-- Reduce Button -->
                            <button x-show="getItemCount('{{ $p->product_name }}') > 0" 
                                    @click.stop="reduceProduct('{{ $p->product_name }}')"
                                    class="absolute -top-1.5 -left-1.5 bg-slate-800 text-white w-5 h-5 flex items-center justify-center rounded-full shadow-lg z-10 hover:bg-rose-600 transition-colors">
                                <svg class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M20 12H4" /></svg>
                            </button>
                            
                            <button @click="quickAdd({ 
                                    product_name: '{{ $p->product_name }}', 
                                    price: {{ $p->price }}, 
                                    telugu_name: '{{ $p->telugu_name }}' 
                                })" class="flex flex-col items-center w-full">
                                <div class="h-10 w-10 bg-slate-50 rounded-lg mb-2 flex items-center justify-center overflow-hidden border border-slate-100 group-hover:scale-110 transition-transform">
                                    <img src="{{ $p->display_image }}" 
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($p->product_name) }}&background=ff9933&color=fff&bold=true&font-size=0.5'"
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="text-[9px] font-bold text-slate-900 text-center line-clamp-1 truncate w-full">{{ $p->product_name }}</span>
                                <span class="text-[8px] font-black text-brand-primary mt-1">₹{{ number_format($p->price, 0) }}</span>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="sticky top-24 space-y-8">
                
                <div class="bg-white border border-gray-200 rounded-3xl shadow-xl p-8 overflow-hidden relative group">
                    <div class="absolute inset-0 bg-blue-600 h-2 top-0"></div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-[4px] mb-8 mt-2">Final Summary</h3>
                    
                    <div class="space-y-5">
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="text-[10px] font-bold uppercase tracking-wider">Subtotal</span>
                            <span class="text-sm font-bold" x-text="'₹ ' + subtotal.toLocaleString('en-IN')"></span>
                        </div>
                        <div x-show="discount_amount > 0" class="flex justify-between items-center text-blue-600">
                            <span class="text-[10px] font-bold uppercase tracking-wider">Discount (-)</span>
                            <span class="text-sm font-bold" x-text="'₹ ' + (parseFloat(discount_amount) || 0).toLocaleString('en-IN')"></span>
                        </div>
                        <div x-show="discount_amount > 0" class="flex justify-between items-center text-gray-500">
                            <span class="text-[10px] font-bold uppercase tracking-wider">Taxable</span>
                            <span class="text-sm font-bold" x-text="'₹ ' + taxable.toLocaleString('en-IN')"></span>
                        </div>
                        <div class="flex justify-between items-center text-gray-500">
                            <span class="text-[10px] font-bold uppercase tracking-wider">GST (18%)</span>
                            <span class="text-sm font-bold" x-text="'₹ ' + gstAmount.toLocaleString('en-IN')"></span>
                        </div>
                        <div class="pt-6 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs font-bold uppercase tracking-widest text-[#1e40af]" x-text="is_quotation ? 'Quoted Total' : 'Net Amount'"></span>
                            <span class="text-3xl font-black text-gray-900 tracking-tighter" x-text="'₹ ' + total.toLocaleString('en-IN')"></span>
                        </div>
                    </div>

                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[4px] mb-4">Select Payment Method</p>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="payment_method = 'online'" 
                                :class="payment_method === 'online' ? 'border-blue-600 bg-blue-50/50 text-blue-700' : 'border-gray-100 text-gray-500'"
                                class="flex flex-col items-center justify-center p-4 border-2 rounded-2xl transition-all">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12H4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest">Scan & Pay</span>
                            </button>
                            <button @click="payment_method = 'cash'" 
                                :class="payment_method === 'cash' ? 'border-emerald-600 bg-emerald-50/50 text-emerald-700' : 'border-gray-100 text-gray-500'"
                                class="flex flex-col items-center justify-center p-4 border-2 rounded-2xl transition-all">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span class="text-[10px] font-black uppercase tracking-widest">Hand Cash</span>
                            </button>
                        </div>
                    </div>

                    <button @click="generateBill()" :disabled="processing"
                        class="w-full mt-8 bg-blue-600 text-white py-4 rounded-2xl font-bold uppercase tracking-[2px] text-xs hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/20 disabled:opacity-50">
                        <span x-show="!processing" x-text="payment_method === 'cash' ? 'Complete & Print' : (is_quotation ? 'Create Quotation' : 'Generate Bill & Pay')"></span>
                        <span x-show="processing" class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Creating...
                        </span>
                    </button>
                </div>

               
                <div x-show="processing && payment_method === 'online'" class="bg-white border border-gray-200 rounded-3xl p-8 animate-in zoom-in duration-300">
                    <div class="text-center">
                        <div class="flex justify-center mb-6">
                            <svg class="animate-spin h-12 w-12 text-blue-600" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-[4px]">Initiating Razorpay...</h4>
                        <p class="text-xs text-gray-500 mt-2 font-medium">Please complete the payment in the popup.</p>
                    </div>
                </div>

              
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
                    <div class="bg-white border border-emerald-100 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4">
                            <div class="h-8 w-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1 leading-none">Manual Hand Cash</p>
                        <h3 class="text-2xl font-bold text-gray-900 leading-tight tracking-tight">₹{{ number_format($cashSales, 0) }}</h3>
                    </div>

                    <div class="bg-white border border-blue-100 rounded-3xl p-6 shadow-sm relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4">
                            <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01" /></svg>
                            </div>
                        </div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1 leading-none">Online Transactions</p>
                        <h3 class="text-2xl font-bold text-gray-900 leading-tight tracking-tight">₹{{ number_format($onlineSales, 0) }}</h3>
                    </div>
                </div>

                <div class="bg-blue-600 rounded-3xl p-8 shadow-xl relative overflow-hidden">
                    <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest mb-1 leading-none">Total Today's Revenue</p>
                    <h3 class="text-3xl font-black text-white leading-tight tracking-tight">₹{{ number_format($todaySales, 0) }}</h3>
                    <div class="mt-4 flex items-center space-x-2">
                        <span class="px-2 py-0.5 bg-white/20 rounded text-[9px] font-bold text-white uppercase tracking-widest">Live Updates</span>
                    </div>
                </div>

                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-[4px] mb-8">7-Day Sales History</h4>
                    <div class="space-y-5">
                        @foreach($dailyStats as $stat)
                            <div class="flex items-center group">
                                <span class="w-12 text-[10px] font-bold text-gray-400 uppercase">{{ Carbon\Carbon::parse($stat->date)->format('d M') }}</span>
                                <div class="flex-grow mx-4 h-1.5 bg-gray-50 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full group-hover:bg-blue-600 transition-colors" style="width: {{ ($stat->total / ($todaySales ?: 1)) * 100 }}%"></div>
                                </div>
                                <span class="w-20 text-xs font-bold text-gray-600 text-right">₹{{ number_format($stat->total, 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
