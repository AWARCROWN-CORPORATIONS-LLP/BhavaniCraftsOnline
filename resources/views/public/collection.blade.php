@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-20 pb-32">
    <div class="container mx-auto px-4 lg:px-8">
        
        <!-- Collection Header -->
        <div class="max-w-4xl mx-auto mb-16 text-center">
            <span class="inline-block py-1.5 px-3 rounded-full bg-brand-500/10 border border-brand-500/20 text-brand-500 text-[10px] font-black uppercase tracking-[4px] mb-6">
                Shared Collection
            </span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-onyx-900 mb-6 italic">
                {{ $user->name }}'s <span class="text-brand-500">Gift List</span>
            </h1>
            <p class="text-gray-500 font-medium max-w-xl mx-auto leading-relaxed">
                A curated selection of items and handcrafted pieces saved for a special celebration.
            </p>
        </div>

        <!-- Products Grid -->
        <div class="max-w-6xl mx-auto">
            @if($items->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 md:gap-12">
                    @foreach($items as $item)
                        @php $product = $item->product; @endphp
                        <div class="group bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl hover:shadow-brand-900/10 transition-all duration-700 transform hover:-translate-y-2">
                            <div class="relative aspect-[4/5] overflow-hidden bg-gray-50">
                                @php 
                                    $mainImage = $product->images->where('is_main', true)->first() ?? $product->images->first();
                                @endphp
                                @if($mainImage)
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($mainImage->image_url) }}" 
                                         alt="{{ $product->product_name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-[1.5s] ease-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                                        <svg class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif

                                <!-- Fractional Gifting Status -->
                                @if($item->is_fully_funded)
                                    <div class="absolute top-6 right-6 py-2 px-4 bg-onyx-900 border border-white/20 backdrop-blur-md rounded-2xl shadow-xl z-10 flex items-center space-x-2">
                                        <svg class="h-3 w-3 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        <span class="text-[10px] font-black uppercase tracking-[2px] text-white">Fully Gifted</span>
                                    </div>
                                    <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px] z-0"></div>
                                @endif
                                
                                <!-- Price Tag -->
                                <div class="absolute top-6 left-6 py-2 px-4 bg-white/90 backdrop-blur-md rounded-2xl shadow-xl z-20">
                                    <span class="text-xs font-black text-onyx-900">₹{{ number_format($product->price, 2) }}</span>
                                </div>
                            </div>

                            <div class="p-8 relative z-20">
                                <div class="flex items-center space-x-2 text-[9px] font-black uppercase tracking-[3px] text-brand-500 mb-3">
                                    <span>{{ $product->category->category_name ?? 'Collection' }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-onyx-900 leading-tight mb-4 line-clamp-2">
                                    {{ $product->product_name }}
                                </h3>
                                
                                @if(!$item->is_fully_funded)
                                    <!-- Gifting Progress -->
                                    <div class="mb-5">
                                        <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">
                                            <span>Funded: ₹{{ number_format($item->total_contributed, 0) }}</span>
                                            <span>Goal: ₹{{ number_format($product->price, 0) }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 rounded-full h-2">
                                            <div class="bg-brand-500 h-2 rounded-full transition-all duration-1000" style="width: {{ min(100, ($item->total_contributed / max(1, $product->price)) * 100) }}%"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Fractional Gifting Action -->
                                    <div x-data="{ showForm: false }">
                                        <div class="flex items-center justify-between">
                                            <a href="{{ route('artifact.show', $product->slug) }}" class="inline-flex items-center space-x-2 text-[10px] font-black uppercase tracking-[2px] text-gray-400 hover:text-onyx-900 transition-colors group/btn">
                                                <span>View Item</span>
                                            </a>
                                            <button @click="showForm = !showForm" class="px-5 py-2.5 bg-brand-50 text-brand-500 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-500 hover:text-white transition-all">
                                                Contribute
                                            </button>
                                        </div>
                                        
                                        <!-- Quick Contribution Form -->
                                        <div x-show="showForm" class="mt-4 p-4 bg-gray-50 border border-gray-100 rounded-2xl" style="display: none;" x-transition>
                                            <form action="{{ route('collection.contribute', $item->id) }}" method="POST">
                                                @csrf
                                                <input type="number" name="amount" min="100" max="{{ $product->price - $item->total_contributed }}" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-onyx-900 mb-3 outline-none focus:border-brand-500" placeholder="Amount (₹)">
                                                <input type="text" name="guest_name" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm font-bold text-onyx-900 mb-3 outline-none focus:border-brand-500" placeholder="Your Name">
                                                <button type="submit" class="w-full py-3 bg-onyx-900 text-white text-[10px] font-black uppercase tracking-[3px] rounded-xl hover:bg-black transition-all">
                                                    Fund Gift
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-4 p-4 bg-gray-50 border border-gray-100 rounded-2xl text-center">
                                        <span class="text-xs font-black uppercase tracking-widest text-onyx-900">Fully Funded by Guests</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-20 text-center bg-white rounded-[3rem] shadow-sm border border-gray-100">
                    <div class="h-24 w-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg class="h-12 w-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-onyx-900 uppercase tracking-widest italic mb-2">List is Empty</h3>
                    <p class="text-gray-400 font-medium max-w-sm mx-auto">This collection currently contains no items.</p></div>
                </div>
            @endif
        </div>

        <!-- Action Footer -->
        <div class="max-w-xl mx-auto mt-24 text-center">
            <div class="p-10 bg-onyx-900 rounded-[2.5rem] shadow-2xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 h-48 w-48 bg-brand-500/10 rounded-full blur-3xl"></div>
                <h3 class="text-xl font-bold text-white mb-4 italic">Love these pieces?</h3>
                <p class="text-white/60 text-sm mb-8 leading-relaxed">Create your own list of items and share it with your friends and family.</p>
                <a href="{{ route('register') }}" class="inline-block px-12 py-4 bg-brand-500 text-white text-[10px] font-black uppercase tracking-[3px] rounded-2xl hover:bg-brand-600 transition-all shadow-lg shadow-brand-500/20">
                    Create Your Collection
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
