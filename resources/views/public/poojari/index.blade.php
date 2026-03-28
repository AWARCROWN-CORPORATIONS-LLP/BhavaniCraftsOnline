@extends('layouts.public')

@section('title', 'Ritual Specialists | Bhavani Crafts')

@section('content')
<div class="bg-gray-50/50 min-h-screen pb-20">
    {{-- Hero Section --}}
    <div class="relative bg-onyx-900 pt-32 pb-24 overflow-hidden">
        <div class="absolute inset-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1545063914-a1a6ec821c88?auto=format&fit=crop&q=80" alt="Sacred Backdrop" class="w-full h-full object-cover">
        </div>
        <div class="relative container mx-auto px-6 text-center">
            <span class="text-brand-500 font-black uppercase tracking-[5px] text-xs mb-4 block">Meet the Practitioners</span>
            <h1 class="text-4xl md:text-6xl font-serif font-bold text-white mb-6">Our Sacred Ritual Specialists</h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-lg leading-relaxed">Connecting you with verified, experienced poojaris for your spiritual milestones. Every ritual is conducted with traditional precision and divine intention.</p>
        </div>
    </div>

    {{-- Listing Section --}}
    <div class="container mx-auto px-6 -mt-12 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($poojaris as $poojari)
                <div class="stagger-item group bg-white rounded-[2.5rem] border border-gray-100 hover:border-brand-500/30 overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-brand-500/10 transition-all duration-500 flex flex-col h-full">
                    <div class="relative aspect-[4/5] overflow-hidden">
                        <img src="{{ $poojari->profile_image ?: 'https://ui-avatars.com/api/?name='.urlencode($poojari->user->name).'&background=fdf8f6&color=c62828&size=512' }}" 
                             alt="{{ $poojari->user->name }}" 
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110">
                        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-onyx-900/80 to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <span class="text-brand-500 text-[10px] font-black uppercase tracking-[3px] mb-1 block">{{ $poojari->specializations ?: 'General Rituals' }}</span>
                            <h3 class="text-2xl font-serif font-bold text-white leading-tight">{{ $poojari->user->name }}</h3>
                        </div>
                    </div>
                    <div class="p-8 flex-grow flex flex-col">
                        <div class="flex items-center space-x-6 mb-6">
                            <div class="flex flex-col">
                                <span class="text-onyx-900 font-bold text-lg leading-none">{{ $poojari->experience_years }}+</span>
                                <span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Years Exp</span>
                            </div>
                            <div class="h-8 w-px bg-gray-100"></div>
                            <div class="flex flex-col">
                                <span class="text-onyx-900 font-bold text-lg leading-none">{{ $poojari->location ?: 'Delhi NCR' }}</span>
                                <span class="text-gray-400 text-[10px] font-bold uppercase tracking-wider">Base Region</span>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 mb-8">{{ $poojari->bio }}</p>
                        <div class="mt-auto">
                            <a wire:navigate href="{{ route('poojari.show', $poojari->slug) }}" 
                               class="w-full bg-onyx-900 text-white rounded-full py-4 text-center font-bold text-xs uppercase tracking-[2px] hover:bg-brand-500 transition-all duration-300 block">
                                View Profile & Book
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-xl font-serif font-bold text-onyx-900">No Specialists Verified Yet</h3>
                    <p class="text-gray-400">We are in the process of auditing our master poojaris. Check back soon.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
