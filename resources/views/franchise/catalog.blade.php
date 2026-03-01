@extends('layouts.franchise')

@section('header_extra')
    <h2 class="text-xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter">Wholesale Catalog</h2>
@endsection

@section('content')

    {{-- TOAST NOTIFICATION --}}
    <div id="catalog-toast" class="fixed bottom-8 right-8 z-[999] hidden">
        <div class="flex items-center space-x-3 bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl border border-white/10">
            <div class="h-8 w-8 bg-[#ff9933] rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <p id="catalog-toast-msg" class="text-sm font-bold tracking-wide"></p>
        </div>
    </div>

    <div class="mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <p class="text-[10px] font-black text-[#ff9933] uppercase tracking-[4px] mb-2">Sacred Inventory</p>
            <h3 class="text-2xl font-light text-gray-600">Exclusive Partner Pricing</h3>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="relative">
                <input type="text" id="catalog-search" placeholder="Search Artifacts..." class="bg-white border border-gray-100 rounded-xl py-3 px-12 text-sm focus:border-[#ff9933] focus:ring-0 transition-all w-full md:w-64">
                <svg class="h-4 w-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- PRODUCT GRID -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($products as $product)
            <div class="card-premium group overflow-hidden flex flex-col">
                <div class="aspect-[4/5] bg-gray-50 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-all duration-500"></div>
                    <div class="h-full w-full flex items-center justify-center text-gray-200">
                        @if($product->images->where('is_main', true)->first())
                            <img src="{{ asset('storage/' . $product->images->where('is_main', true)->first()->image_url) }}" class="w-full h-full object-cover">
                        @elseif($product->images->first())
                            <img src="{{ asset('storage/' . $product->images->first()->image_url) }}" class="w-full h-full object-cover">
                        @else
                            <svg class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        @endif
                    </div>
                    <div class="absolute top-4 right-4 z-10">
                        <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest text-gray-900 shadow-sm border border-gray-100">{{ $product->product_code }}</span>
                    </div>

                    @if($product->discount_percent && $product->discount_percent > 0)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-red-500/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-white shadow-lg shadow-red-500/30">{{ $product->discount_percent }}% OFF</span>
                        </div>
                    @endif
                </div>

                <div class="p-6 flex-grow flex flex-col">
                    <p class="text-[9px] font-black text-[#ff9933] uppercase tracking-[3px] mb-1">{{ $product->category->name ?? 'General' }}</p>
                    <h4 class="text-lg font-bold text-gray-900 leading-tight mb-4 group-hover:text-[#ff9933] transition-colors line-clamp-2">
                        {{ $product->product_name }}
                    </h4>
                    
                    <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                        <div>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest leading-none mb-1">Wholesale</p>
                            <p class="text-xl font-black text-gray-900">₹{{ number_format($product->price, 2) }}</p>
                        </div>
                        {{-- + button wired to addToCart() --}}
                        <button
                            class="add-to-cart-btn h-10 w-10 bg-[#ff9933] text-white flex items-center justify-center rounded-xl shadow-lg hover:scale-110 transition-transform focus:outline-none focus:ring-2 focus:ring-[#ff9933] focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            data-product-id="{{ $product->id }}"
                            title="Add {{ $product->product_name }} to cart"
                        >
                            <svg class="h-5 w-5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center card-premium bg-white/50">
                <div class="flex flex-col items-center opacity-20 capitalize">
                    <svg class="h-24 w-24 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    <h5 class="text-2xl font-black tracking-[10px] text-gray-400">Vault Closed</h5>
                    <p class="text-sm font-bold tracking-widest mt-4">No products are currently listed in the wholesale registry.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="mt-16">
        {{ $products->links() }}
    </div>

@endsection

@push('scripts')
<script>
(function () {
    const CSRF  = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const CART_URL = '{{ route("cart.add") }}';

    // ── Toast helper ───────────────────────────────────────────────
    function showToast(msg, isError = false) {
        const toast    = document.getElementById('catalog-toast');
        const msgEl    = document.getElementById('catalog-toast-msg');
        const iconBox  = toast.querySelector('.bg-\\[\\#ff9933\\]');

        msgEl.textContent = msg;
        if (isError) {
            iconBox.classList.replace('bg-[#ff9933]', 'bg-red-500');
            iconBox.innerHTML = `<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>`;
        } else {
            iconBox.classList.replace('bg-red-500', 'bg-[#ff9933]');
            iconBox.innerHTML = `<svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`;
        }

        toast.classList.remove('hidden');
        clearTimeout(toast._hideTimer);
        toast._hideTimer = setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // ── Add-to-cart ────────────────────────────────────────────────
    document.querySelectorAll('.add-to-cart-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const productId = this.getAttribute('data-product-id');
            const self = this;

            self.disabled = true;

            fetch(CART_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 }),
            })
            .then(function (res) {
                if (!res.ok) throw new Error('Server error ' + res.status);
                return res.json();
            })
            .then(function (data) {
                showToast(data.message || 'Added to cart!');
            })
            .catch(function (err) {
                showToast('Could not add to cart. Please try again.', true);
                console.error(err);
            })
            .finally(function () {
                self.disabled = false;
            });
        });
    });

    // ── Live search filter ─────────────────────────────────────────
    const searchInput = document.getElementById('catalog-search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.card-premium').forEach(function (card) {
                const name = card.querySelector('h4');
                if (!name) return;
                card.closest('[class*="col"]') // grid cell wrapper if any
                card.style.display = name.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
})();
</script>
@endpush
