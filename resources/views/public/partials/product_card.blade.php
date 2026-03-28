@php 
    // Normalize $product into an object $p
    if (is_array($product)) {
        // Recursively convert array to object
        $p = json_decode(json_encode($product));
    } else {
        $p = $product;
    }

    // Extract main image
    $img = null;
    $pImages = data_get($p, 'images', []);
    if (count($pImages) > 0) {
        foreach($pImages as $item) {
            if (data_get($item, 'is_main')) {
                $img = $item;
                break;
            }
        }
        if (!$img) $img = $pImages[0];
    }
@endphp
<a wire:navigate href="{{ route('artifact.show', $p->slug) }}"
   class="stagger-item group bg-white rounded-[1.5rem] border border-gray-100 hover:border-brand-500/30 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-brand-500/10 transition-all duration-500 flex flex-col">

    {{-- Image --}}
    <div class="relative aspect-square overflow-hidden bg-gray-50">
        @if($img)
            <img src="{{ \Illuminate\Support\Facades\Storage::url($img->image_url) }}"
                 alt="{{ $p->product_name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="h-16 w-16 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-3 left-3 flex flex-col gap-1">
            @if(isset($p->discount_percent) && $p->discount_percent > 0)
                <span class="bg-red-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">{{ $p->discount_percent }}% OFF</span>
            @endif
            @if(isset($p->stock) && $p->stock == 0)
                <span class="bg-gray-800/80 text-white text-[9px] font-black px-2 py-0.5 rounded-full">Out of Stock</span>
            @elseif(isset($p->stock) && $p->stock <= 5)
                <span class="bg-orange-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full animate-pulse">Only {{ $p->stock }} left</span>
            @endif
        </div>

        {{-- Wishlist quick --}}
        <button onclick="event.preventDefault(); toggleWishlist({{ $p->id }}, this)"
                class="absolute top-3 right-3 h-8 w-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-brand-500 hover:bg-white transition-all shadow-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
        </button>
    </div>

    {{-- Product Info --}}
    <div class="p-5 flex flex-col flex-1">
        @if(isset($p->category))
            <span class="text-[9px] font-black uppercase tracking-[2px] text-brand-500 mb-1">{{ is_object($p->category) ? $p->category->name : (isset($p->category->name) ? $p->category->name : '') }}</span>
        @endif
        <h3 class="text-sm font-bold text-onyx-900 leading-snug mb-1 group-hover:text-brand-500 transition-colors line-clamp-2">
            {{ $p->product_name }}
        </h3>
        @if(isset($p->material_type))
        <p class="text-[10px] text-gray-400 font-medium capitalize mb-3">{{ $p->material_type }}</p>
        @endif

        <div class="mt-auto flex items-center justify-between">
            <div>
                <p class="text-lg font-black text-onyx-900">{{ \App\Helpers\PriceHelper::format($p->price) }}</p>
                @if(isset($p->mrp) && $p->mrp > $p->price)
                    <p class="text-[10px] text-gray-400 line-through font-medium">{{ \App\Helpers\PriceHelper::format($p->mrp) }}</p>
                @endif
            </div>
            <button onclick="event.preventDefault(); addToCart({{ $p->id }})"
                    class="h-9 w-9 bg-brand-500 text-white rounded-xl flex items-center justify-center hover:bg-brand-600 transition-all shadow-md shadow-brand-500/20 hover:scale-110">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            </button>
        </div>
    </div>
</a>
