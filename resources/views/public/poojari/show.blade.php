@extends('layouts.public')

@section('title', $profile->user->name . ' - Ritual Specialist')

@section('content')
<div class="bg-white min-h-screen pb-20 pt-32">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            
            {{-- Profile Sidebar --}}
            <div class="lg:col-span-4 sticky top-32">
                <div class="group relative aspect-[3/4] overflow-hidden rounded-[3rem] shadow-2xl shadow-brand-500/10 mb-8 border-4 border-white">
                    <img src="{{ $profile->profile_image ?: 'https://ui-avatars.com/api/?name='.urlencode($profile->user->name).'&background=fdf8f6&color=c62828&size=1024' }}" 
                         alt="{{ $profile->user->name }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">
                    <div class="absolute inset-x-0 bottom-0 p-8 bg-gradient-to-t from-onyx-900/80 to-transparent pt-20">
                        <div class="flex items-center space-x-2 bg-brand-500 text-white px-4 py-1.5 rounded-full w-max text-[8px] font-black uppercase tracking-[3px] mb-4">
                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                            <span>Verified Specialist</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-serif font-bold text-white mb-2">{{ $profile->user->name }}</h1>
                        <span class="text-brand-500 text-[10px] font-black uppercase tracking-[4px]">{{ $profile->specializations ?: 'Master Practitioner' }}</span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-[2rem] p-8 border border-gray-100">
                        <h4 class="text-xs font-black uppercase tracking-[3px] text-onyx-900 mb-6 pb-4 border-b border-gray-100">Practitioner Stats</h4>
                        <div class="grid grid-cols-2 gap-8">
                            <div>
                                <span class="block text-2xl font-serif font-bold text-onyx-900 leading-none mb-1">{{ $profile->experience_years }}+</span>
                                <span class="text-[9px] font-black uppercase tracking-wider text-gray-400">Years Exp.</span>
                            </div>
                            <div>
                                <span class="block text-2xl font-serif font-bold text-onyx-900 leading-none mb-1">4.9/5</span>
                                <span class="text-[9px] font-black uppercase tracking-wider text-gray-400">Rating</span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-lg font-serif font-bold text-onyx-900 leading-none mb-1">{{ $profile->location ?: 'Delhi NCR' }}</span>
                                <span class="text-[9px] font-black uppercase tracking-wider text-gray-400">Primary Serving Region</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-16">
                {{-- Bio --}}
                <div class="stagger-item">
                    <h2 class="text-xs font-black uppercase tracking-[5px] text-brand-500 mb-6">About the Specialist</h2>
                    <div class="prose prose-onyx max-w-none">
                        <p class="text-lg text-gray-600 leading-relaxed font-light italic">"{{ $profile->bio ?: 'A dedicated practitioner focused on upholding the sacred traditions of Hindu rituals with meticulous attention to scriptural accuracy and divine grace.' }}"</p>
                    </div>
                </div>

                {{-- Availability --}}
                @if($profile->availability)
                <div class="stagger-item anim-delay-100">
                    <h2 class="text-xs font-black uppercase tracking-[5px] text-brand-500 mb-8">Weekly Availability</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($profile->availability as $day => $slots)
                            <div class="bg-white border-2 border-brand-50 p-6 rounded-3xl text-center group hover:border-brand-500/20 transition-all duration-300">
                                <span class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3 group-hover:text-brand-500">{{ $day }}</span>
                                <div class="space-y-2">
                                    @foreach($slots as $slot)
                                        <span class="block text-xs font-bold text-onyx-900 py-2 bg-brand-50/30 rounded-full border border-brand-100/50">{{ $slot }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Booking Form --}}
                <div class="stagger-item anim-delay-200 bg-onyx-900 rounded-[3.5rem] p-12 lg:p-16 text-white shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/10 blur-[100px] pointer-events-none"></div>
                    
                    <div class="relative z-10 max-w-xl">
                        <span class="text-brand-500 font-black uppercase tracking-[5px] text-xs mb-4 block">Request Ritual Service</span>
                        <h2 class="text-3xl md:text-5xl font-serif font-bold mb-8">Secure Your Divine Consultation</h2>
                        <p class="text-gray-400 mb-12 text-lg">Enter your event details below. Our concierge team will review your request and contact you within 4 hours to finalize the poojari's schedule and ritual preparations.</p>

                        @if(session('success'))
                            <div class="bg-brand-500/20 border border-brand-500/30 rounded-3xl p-6 mb-8 flex items-center space-x-4">
                                <div class="h-12 w-12 bg-brand-500 text-white rounded-full flex items-center justify-center shrink-0">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <p class="text-brand-50 font-bold leading-tight">{{ session('success') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('poojari.book') }}" method="POST" class="space-y-8">
                            @csrf
                            <input type="hidden" name="poojari_user_id" value="{{ $profile->user_id }}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-[2px] text-gray-400 ml-4">Type of Ritual / Event</label>
                                    <input type="text" name="event_name" required placeholder="e.g., Griha Pravesh & Shanti Path" 
                                           class="w-full bg-white/5 border border-white/10 rounded-full py-4 px-8 text-white placeholder-white/30 focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-500/10 transition-all">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-[2px] text-gray-400 ml-4">Proposed Date</label>
                                    <input type="date" name="event_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="w-full bg-white/5 border border-white/10 rounded-full py-4 px-8 text-white placeholder-white/30 focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-500/10 transition-all">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[2px] text-gray-400 ml-4">Venue Address</label>
                                <textarea name="event_address" required rows="3" placeholder="Full address for the ritual location..." 
                                          class="w-full bg-white/5 border border-white/10 rounded-3xl py-6 px-8 text-white placeholder-white/30 focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-500/10 transition-all"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-[2px] text-gray-400 ml-4">Additional Special Requirements (Optional)</label>
                                <textarea name="additional_notes" rows="2" placeholder="Mention any specific samagri or traditions..." 
                                          class="w-full bg-white/5 border border-white/10 rounded-3xl py-6 px-8 text-white placeholder-white/30 focus:border-brand-500 focus:outline-none focus:ring-4 focus:ring-brand-500/10 transition-all"></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-brand-500 text-white rounded-full py-6 text-center font-black text-xs uppercase tracking-[4px] hover:bg-brand-600 transition-all duration-300 shadow-2xl shadow-brand-500/40">
                                Submit Booking Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
