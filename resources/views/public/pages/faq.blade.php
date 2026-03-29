@extends('public.pages.layout')
@section('page_title', 'Sacred FAQ Assistant')
@section('page_content')
    <div x-data="{ active: null }" class="space-y-4">
        
        <!-- Accordion Item 1 -->
        <div class="border border-gray-100 rounded-3xl overflow-hidden bg-white shadow-sm transition-all duration-300" :class="active === 1 ? 'ring-2 ring-brand-500/20 border-brand-200' : ''">
            <button @click="active = active === 1 ? null : 1" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                <span class="text-xs font-black uppercase tracking-[2px] text-onyx-950 group-hover:text-brand-600 transition-colors">How should I maintain my brass idols?</span>
                <svg class="h-5 w-5 text-gray-400 transition-transform duration-500" :class="active === 1 ? 'rotate-180 text-brand-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="active === 1" x-collapse x-cloak class="px-8 pb-8">
                <p class="text-[11px] font-medium leading-relaxed uppercase tracking-wider text-gray-500">Brass artifacts develop a natural patina over time. To maintain their sacred shine, we recommend using a mixture of lemon juice and baking soda or a specialized brass polish. Gently buff with a soft ritual cloth provided in our premium packaging.</p>
            </div>
        </div>

        <!-- Accordion Item 2 -->
        <div class="border border-gray-100 rounded-3xl overflow-hidden bg-white shadow-sm transition-all duration-300" :class="active === 2 ? 'ring-2 ring-brand-500/20 border-brand-200' : ''">
            <button @click="active = active === 2 ? null : 2" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                <span class="text-xs font-black uppercase tracking-[2px] text-onyx-950 group-hover:text-brand-600 transition-colors">Is shipping safe for fragile items?</span>
                <svg class="h-5 w-5 text-gray-400 transition-transform duration-500" :class="active === 2 ? 'rotate-180 text-brand-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="active === 2" x-collapse x-cloak class="px-8 pb-8">
                <p class="text-[11px] font-medium leading-relaxed uppercase tracking-wider text-gray-500">Absolutely. We use a 5-layer fortification process including custom-fit high-density foam and eco-friendly shock absorbents. Every artifact is insured individually to ensure your devotion reaches you in perfect condition.</p>
            </div>
        </div>

        <!-- Accordion Item 3 -->
        <div class="border border-gray-100 rounded-3xl overflow-hidden bg-white shadow-sm transition-all duration-300" :class="active === 3 ? 'ring-2 ring-brand-500/20 border-brand-200' : ''">
            <button @click="active = active === 3 ? null : 3" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                <span class="text-xs font-black uppercase tracking-[2px] text-onyx-950 group-hover:text-brand-600 transition-colors">Do you offer custom ritual kits?</span>
                <svg class="h-5 w-5 text-gray-400 transition-transform duration-500" :class="active === 3 ? 'rotate-180 text-brand-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="active === 3" x-collapse x-cloak class="px-8 pb-8">
                <p class="text-[11px] font-medium leading-relaxed uppercase tracking-wider text-gray-500">Yes! You can consult with our sacred specialists via WhatsApp to curate a kit specific to your family traditions or office requirements. Custom kits typically take 3-5 days to assemble and verify.</p>
            </div>
        </div>

        <!-- Accordion Item 4 -->
        <div class="border border-gray-100 rounded-3xl overflow-hidden bg-white shadow-sm transition-all duration-300" :class="active === 4 ? 'ring-2 ring-brand-500/20 border-brand-200' : ''">
            <button @click="active = active === 4 ? null : 4" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                <span class="text-xs font-black uppercase tracking-[2px] text-onyx-950 group-hover:text-brand-600 transition-colors">What happens if my artifact arrives damaged?</span>
                <svg class="h-5 w-5 text-gray-400 transition-transform duration-500" :class="active === 4 ? 'rotate-180 text-brand-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="active === 4" x-collapse x-cloak class="px-8 pb-8">
                <p class="text-[11px] font-medium leading-relaxed uppercase tracking-wider text-gray-500">While rare, if an artifact is disrupted during transit, we offer a dedicated "Damage Registry." Simply upload photos within 48 hours of delivery, and we will dispatch a replacement artifact immediately at no extra cost.</p>
            </div>
        </div>

        <!-- Accordion Item 5 -->
        <div class="border border-gray-100 rounded-3xl overflow-hidden bg-white shadow-sm transition-all duration-300" :class="active === 5 ? 'ring-2 ring-brand-500/20 border-brand-200' : ''">
            <button @click="active = active === 5 ? null : 5" class="w-full px-8 py-6 flex items-center justify-between text-left group">
                <span class="text-xs font-black uppercase tracking-[2px] text-onyx-950 group-hover:text-brand-600 transition-colors">How do I track my order?</span>
                <svg class="h-5 w-5 text-gray-400 transition-transform duration-500" :class="active === 5 ? 'rotate-180 text-brand-500' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
            <div x-show="active === 5" x-collapse x-cloak class="px-8 pb-8">
                <p class="text-[11px] font-medium leading-relaxed uppercase tracking-wider text-gray-500">You can trace your order's journey through our live portal using your Order ID and Registered Email. Additionally, you will receive automated WhatsApp and Email updates at every fulfillment milestone.</p>
            </div>
        </div>

    </div>
@endsection
