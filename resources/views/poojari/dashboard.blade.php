@extends('layouts.public')

@section('title', 'Specialist Dashboard | Bhavani Crafts')

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <span class="text-brand-500 font-black uppercase tracking-[5px] text-xs mb-4 block">Practitioner Portal</span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-onyx-900 leading-none">Namaste, {{ Auth::user()->name }}</h1>
                <p class="text-gray-400 mt-4 max-w-xl">Welcome to your sacred dashboard. Manage your availability, profile, and upcoming ritual services from this portal.</p>
            </div>
            <div class="flex space-x-4">
                <a wire:navigate href="{{ route('poojari.profile.edit') }}" 
                   class="bg-white border border-gray-200 text-onyx-900 rounded-full px-8 py-4 font-bold text-xs uppercase tracking-[2px] hover:border-brand-500 transition-all">
                    Edit Ritual Profile
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Quick Stats --}}
            <div class="lg:col-span-4 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 stagger-item">
                    <h3 class="text-xs font-black uppercase tracking-[4px] text-brand-500 mb-8">Profile Performance</h3>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <span class="block text-3xl font-serif font-bold text-onyx-900 mb-1">12</span>
                            <span class="text-[9px] font-black uppercase tracking-wider text-gray-400">Total Rituals</span>
                        </div>
                        <div>
                            <span class="block text-3xl font-serif font-bold text-onyx-900 mb-1">4.9/5</span>
                            <span class="text-[9px] font-black uppercase tracking-wider text-gray-400">Divine Rating</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-100 stagger-item anim-delay-100">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-xs font-black uppercase tracking-[4px] text-brand-500">Your Availability</h3>
                        <a href="{{ route('poojari.profile.edit') }}" class="text-[10px] uppercase font-bold text-gray-300 hover:text-brand-500 transition-colors underline-offset-4 underline">Update</a>
                    </div>
                    @if($profile && $profile->availability)
                        <div class="space-y-4">
                            @foreach($profile->availability as $day => $slots)
                                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                                    <span class="text-xs font-bold text-onyx-900">{{ $day }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ count($slots) }} Slots Active</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 leading-relaxed italic">No availability set yet. Update your profile to start receiving bookings.</p>
                    @endif
                </div>
            </div>

            {{-- Upcoming Events --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-[3rem] p-10 lg:p-14 shadow-sm border border-gray-100 stagger-item anim-delay-200">
                    <h3 class="text-xs font-black uppercase tracking-[5px] text-brand-500 mb-10 pb-6 border-b border-gray-100">Upcoming Sacred Rituals</h3>
                    
                    @forelse($upcomingEvents as $event)
                        <div class="group relative flex flex-col md:flex-row md:items-center justify-between py-8 border-b border-gray-50 last:border-0 hover:bg-gray-50/50 -mx-4 px-4 rounded-3xl transition-all">
                            <div class="mb-4 md:mb-0">
                                <span class="text-brand-500 text-[9px] font-black uppercase tracking-widest block mb-2">{{ $event->event_date->format('l, d M Y') }}</span>
                                <h4 class="text-xl font-serif font-bold text-onyx-900 mb-2">{{ $event->event_name }}</h4>
                                <div class="flex items-center space-x-3 text-gray-400 text-sm">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate max-w-sm">{{ $event->event_address }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="bg-green-50 text-green-600 px-4 py-1.5 rounded-full text-[8px] font-black uppercase tracking-[2px] mb-4">Confirmed</span>
                                <span class="text-gray-400 text-xs font-medium">{{ $event->event_date->format('h:i A') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="h-8 w-8 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-lg font-serif font-bold text-onyx-900">No Imminent Rituals</h4>
                            <p class="text-gray-400 text-sm">Once the sanctuary team confirms a booking, it will appear here.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
